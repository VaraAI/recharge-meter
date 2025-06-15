<?php

namespace RechargeMeter\RechargeMeterService\Http\Clients;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UseTypeClient
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

    public function getList(): array
    {
        $this->validateCredentials();

        if ($this->simulate) {
            return [
                'status' => 'success',
                'data' => [
                    [
                        'useTypeId' => 'RES',
                        'useTypeName' => 'Residential',
                        'meterType' => 1,
                        'price' => 100.00,
                        'vat' => 18.00
                    ]
                ]
            ];
        }

        $response = $this->client()
            ->get('/api/UseType/UseTypeList', [
                'userId' => $this->userId,
                'password' => $this->password
            ]);

        $this->validateResponse($response, 'get use type list');
        return $response->json();
    }

    public function add(array $data): array
    {
        $this->validateCredentials();

        if ($this->simulate) {
            return [
                'status' => 'success',
                'message' => 'Use type added successfully'
            ];
        }

        $response = $this->client()
            ->asForm()
            ->post('/api/UseType/AddUseType', array_merge([
                'UserId' => $this->userId,
                'Password' => $this->password
            ], $data));

        $this->validateResponse($response, 'add use type');
        return $response->json();
    }

    public function update(array $data): array
    {
        $this->validateCredentials();

        if ($this->simulate) {
            return [
                'status' => 'success',
                'message' => 'Use type updated successfully'
            ];
        }

        $response = $this->client()
            ->asForm()
            ->post('/api/UseType/UpdateUseType', array_merge([
                'UserId' => $this->userId,
                'Password' => $this->password
            ], $data));

        $this->validateResponse($response, 'update use type');
        return $response->json();
    }

    public function delete(string $useTypeId): array
    {
        $this->validateCredentials();

        if ($this->simulate) {
            return [
                'status' => 'success',
                'message' => 'Use type deleted successfully'
            ];
        }

        $response = $this->client()
            ->asForm()
            ->post('/api/UseType/DeleteUseType', [
                'UserId' => $this->userId,
                'Password' => $this->password,
                'UseTypeId' => $useTypeId
            ]);

        $this->validateResponse($response, 'delete use type');
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
                ->info("UseType API {$action} response", $response);
        }
    }
} 