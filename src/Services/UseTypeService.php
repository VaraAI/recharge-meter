<?php

namespace RechargeMeter\RechargeMeterService\Services;

use RechargeMeter\RechargeMeterService\Http\Clients\UseTypeClient;

class UseTypeService
{
    private UseTypeClient $client;
    private ?string $userId = null;
    private ?string $password = null;

    public function __construct(UseTypeClient $client)
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
     * Get use type list
     * API: GET /api/UseType/UseTypeList
     */
    public function getList(): array
    {
        try {
            return [
                'success' => true,
                'data' => $this->client->getList()
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Add use type
     * API: POST /api/UseType/AddUseType
     */
    public function add(
        string $useTypeId,
        string $useTypeName,
        int $meterType,
        float $price,
        float $vat
    ): array {
        try {
            $data = [
                'UseTypeId' => $useTypeId,
                'UseTypeName' => $useTypeName,
                'MeterType' => $meterType,
                'Price' => $price,
                'Vat' => $vat
            ];

            return [
                'success' => true,
                'data' => $this->client->add($data)
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Update use type
     * API: POST /api/UseType/UpdateUseType
     */
    public function update(
        string $useTypeId,
        float $price,
        float $vat
    ): array {
        try {
            $data = [
                'UseTypeId' => $useTypeId,
                'Price' => $price,
                'Vat' => $vat
            ];

            return [
                'success' => true,
                'data' => $this->client->update($data)
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Delete use type
     * API: POST /api/UseType/DeleteUseType
     */
    public function delete(string $useTypeId): array
    {
        try {
            return [
                'success' => true,
                'data' => $this->client->delete($useTypeId)
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
} 