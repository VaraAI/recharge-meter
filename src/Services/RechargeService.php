<?php

namespace RechargeMeter\RechargeMeterService\Services;

use RechargeMeter\RechargeMeterService\Http\Clients\RechargeClient;
use RechargeMeter\RechargeMeterService\Models\RechargeHistory;

class RechargeService
{
    private RechargeClient $client;
    private ?string $userId = null;
    private ?string $password = null;

    public function __construct(RechargeClient $client)
    {
        $this->client = $client;
    }

    public function setCredentials(string $userId, string $password): self
    {
        $this->userId = $userId;
        $this->password = $password;
        $this->client->setCredentials($userId, $password);
        return $this;
    }

    /**
     * Get vending token (recharge token)
     * API: GET /api/Power/GetVendingToken
     */
    public function getVendingToken(string $meterCode, int $meterType, float $amountOrQuantity, int $vendingType = 0): array
    {
        try {
            // Call the vending API
            $response = $this->client->getVendingToken($meterCode, $meterType, $amountOrQuantity, $vendingType);

            // Create recharge history record
            $history = RechargeHistory::create([
                'meter_code' => $meterCode,
                'meter_type' => $meterType,
                'amount' => $amountOrQuantity,
                'response_token' => $response['token'] ?? null,
                'response_status' => $response['status'] ?? null,
                'raw_response' => $response,
            ]);

            return [
                'success' => true,
                'data' => $response,
                'history_id' => $history->id,
            ];
        } catch (\Exception $e) {
            // Log the error and create a failed history record
            $history = RechargeHistory::create([
                'meter_code' => $meterCode,
                'meter_type' => $meterType,
                'amount' => $amountOrQuantity,
                'response_status' => 'error',
                'raw_response' => [
                    'error' => $e->getMessage(),
                ],
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'history_id' => $history->id,
            ];
        }
    }

    /**
     * Process recharge (alias for getVendingToken for backward compatibility)
     * API: GET /api/Power/GetVendingToken
     */
    public function process(string $meterCode, int $meterType, float $amount, int $vendingType = 0): array
    {
        return $this->getVendingToken($meterCode, $meterType, $amount, $vendingType);
    }

    /**
     * Get clear credit token
     * API: GET /api/Power/GetClearCreditToken
     */
    public function getClearCreditToken(string $meterCode, int $meterType): array
    {
        try {
            $response = $this->client->getClearCreditToken($meterCode, $meterType);

            $history = RechargeHistory::create([
                'meter_code' => $meterCode,
                'meter_type' => $meterType,
                'amount' => 0,
                'response_token' => $response['token'] ?? null,
                'response_status' => $response['status'] ?? null,
                'raw_response' => $response,
            ]);

            return [
                'success' => true,
                'data' => $response,
                'history_id' => $history->id,
            ];
        } catch (\Exception $e) {
            $history = RechargeHistory::create([
                'meter_code' => $meterCode,
                'meter_type' => $meterType,
                'amount' => 0,
                'response_status' => 'error',
                'raw_response' => [
                    'error' => $e->getMessage(),
                ],
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'history_id' => $history->id,
            ];
        }
    }

    /**
     * Get clear tamper sign token
     * API: GET /api/Power/GetClearTamperSignToken
     */
    public function getClearTamperSignToken(string $meterCode, int $meterType): array
    {
        try {
            $response = $this->client->getClearTamperSignToken($meterCode, $meterType);

            $history = RechargeHistory::create([
                'meter_code' => $meterCode,
                'meter_type' => $meterType,
                'amount' => 0,
                'response_token' => $response['token'] ?? null,
                'response_status' => $response['status'] ?? null,
                'raw_response' => $response,
            ]);

            return [
                'success' => true,
                'data' => $response,
                'history_id' => $history->id,
            ];
        } catch (\Exception $e) {
            $history = RechargeHistory::create([
                'meter_code' => $meterCode,
                'meter_type' => $meterType,
                'amount' => 0,
                'response_status' => 'error',
                'raw_response' => [
                    'error' => $e->getMessage(),
                ],
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'history_id' => $history->id,
            ];
        }
    }

    /**
     * Get contract information
     * API: GET /api/Power/GetContractInfo
     */
    public function getContractInfo(string $meterCode, int $meterType): array
    {
        try {
            return [
                'success' => true,
                'data' => $this->client->getContractInfo($meterCode, $meterType)
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Register meter
     * API: POST /api/Power/MeterRegister
     */
    public function registerMeter(array $data): array
    {
        try {
            return [
                'success' => true,
                'data' => $this->client->registerMeter($data)
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Update meter
     * API: POST /api/Power/MeterUpdate
     */
    public function updateMeter(array $data): array
    {
        try {
            return [
                'success' => true,
                'data' => $this->client->updateMeter($data)
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
} 