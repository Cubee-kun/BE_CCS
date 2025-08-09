<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class BlockchainService
{
    protected $apiUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->apiUrl = config('services.blockchain.url');
        $this->apiKey = config('services.blockchain.key');
    }

    public function storeDataOnBlockchain(array $data)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post($this->apiUrl . '/store', $data);

        if ($response->successful()) {
            return $response->json();
        }

        return null;
    }

    public function getTransactionStatus(string $txHash)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
        ])->get($this->apiUrl . '/transaction/' . $txHash);

        if ($response->successful()) {
            return $response->json();
        }

        return null;
    }
}
