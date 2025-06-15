<?php

namespace RechargeMeter\RechargeMeterService\Http\Clients;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RechargeClient
{
    private string $baseUrl;
    private ?string $userId = null;
    private ?string $password = null;
    private bool $simulate;
    private array $timeout;

    public function __construct()
    {
        $this->baseUrl = config('recharge.api_url');
        $this->simulate = config('recharge.simulate', false);
        $this->timeout = config('recharge.timeout', [
            'connect' => 10,
            'request' => 30,
        ]);
    }

    public function setCredentials(string $userId, string $password): self
    {
        $this->userId = $userId;
        $this->password = $password;
        return $this;
    }

    public function getVendingToken(string $meterCode, int $meterType, float $amount, int $vendingType = 0): array
    {
        $this->validateCredentials();
        
        if ($this->simulate) {
            return [
                'status' => 'success',
                'token' => 'simulated-token-' . time(),
                'amount' => $amount,
            ];
        }

        $response = $this->client()
            ->get('/api/Power/GetVendingToken', [
                'UserId' => $this->userId,
                'Password' => $this->password,
                'MeterCode' => $meterCode,
                'MeterType' => $meterType,
                'AmountOrQuantity' => $amount,
                'VendingType' => $vendingType
            ]);

        $this->validateResponse($response, 'vending token');
        return $response->json();
    }

    public function getClearCreditToken(string $meterCode, int $meterType): array
    {
        $this->validateCredentials();

        if ($this->simulate) {
            return [
                'status' => 'success',
                'token' => 'simulated-clear-token-' . time(),
            ];
        }

        $response = $this->client()
            ->get('/api/Power/GetClearCreditToken', [
                'UserId' => $this->userId,
                'Password' => $this->password,
                'MeterCode' => $meterCode,
                'MeterType' => $meterType
            ]);

        $this->validateResponse($response, 'clear credit token');
        return $response->json();
    }

    public function getClearTamperSignToken(string $meterCode, int $meterType): array
    {
        $this->validateCredentials();

        if ($this->simulate) {
            return [
                'status' => 'success',
                'token' => 'simulated-tamper-token-' . time(),
            ];
        }

        $response = $this->client()
            ->get('/api/Power/GetClearTamperSignToken', [
                'UserId' => $this->userId,
                'Password' => $this->password,
                'MeterCode' => $meterCode,
                'MeterType' => $meterType
            ]);

        $this->validateResponse($response, 'clear tamper sign token');
        return $response->json();
    }

    public function getContractInfo(string $meterCode, int $meterType): array
    {
        $this->validateCredentials();

        if ($this->simulate) {
            return [
                'status' => 'success',
                'contractInfo' => [
                    'meterCode' => $meterCode,
                    'meterType' => $meterType,
                ]
            ];
        }

        $response = $this->client()
            ->get('/api/Power/GetContractInfo', [
                'UserId' => $this->userId,
                'Password' => $this->password,
                'MeterCode' => $meterCode,
                'MeterType' => $meterType
            ]);

        $this->validateResponse($response, 'contract info');
        return $response->json();
    }

    public function registerMeter(array $data): array
    {
        $this->validateCredentials();

        if ($this->simulate) {
            return [
                'status' => 'success',
                'message' => 'Meter registered successfully'
            ];
        }

        $response = $this->client()
            ->asForm()
            ->post('/api/Power/MeterRegister', array_merge([
                'UserId' => $this->userId,
                'Password' => $this->password,
            ], $data));

        $this->validateResponse($response, 'meter registration');
        return $response->json();
    }

    public function updateMeter(array $data): array
    {
        $this->validateCredentials();

        if ($this->simulate) {
            return [
                'status' => 'success',
                'message' => 'Meter updated successfully'
            ];
        }

        $response = $this->client()
            ->asForm()
            ->post('/api/Power/MeterUpdate', array_merge([
                'UserId' => $this->userId,
                'Password' => $this->password,
            ], $data));

        $this->validateResponse($response, 'meter update');
        return $response->json();
    }

    private function validateCredentials(): void
    {
        if (!$this->userId || !$this->password) {
            throw new \Exception('User credentials not set. Call setCredentials() first.');
        }
    }

    private function validateResponse($response, string $operation): void
    {
        if (!$response->successful()) {
            throw new \Exception("{$operation} failed: " . $response->body());
        }

        $this->logResponse($operation, $response->json());
    }

    private function client(): PendingRequest
    {
        return Http::baseUrl($this->baseUrl)
            ->timeout($this->timeout['request'])
            ->connectTimeout($this->timeout['connect'])
            ->acceptJson();
    }

    private function logResponse(string $action, array $response): void
    {
        if (config('recharge.logging.enabled')) {
            Log::channel(config('recharge.logging.channel'))
                ->info("Recharge API {$action} response", $response);
        }
    }
} 