<?php

namespace App\Services;

use App\Services\Contracts\HttpClientInterface;
use App\Services\Contracts\MarketplaceServiceInterface;
use Exception;
use Illuminate\Support\Facades\Log;

class MockoonService implements MarketplaceServiceInterface
{
    protected HttpClientInterface $client;


    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     *
     * @return int O número total de páginas.
     * @throws Exception
     */
    public function fetchTotalPages(): int
    {
        try {
            $this->client->get('offers', [
                'query' => [
                    'page' => 1
                ]
            ]);

            $offersResponse = $this->client->jsonToArray();
            return $offersResponse['pagination']['total_pages'] ?? 1;
        } catch (Exception $e) {
            $message = $e->getMessage();
            Log::error("Erro ao buscar o total de páginas do Mockoon: {$message}");
            throw $e;
        }
    }

    public function fetchTotalOffers(): int
    {
        try {
            $this->client->get('offers', [
                'query' => [
                    'page' => 1
                ]
            ]);

            $offersResponse = $this->client->jsonToArray();
            return $offersResponse['pagination']['total_records'] ?? 0;
        } catch (Exception $e) {
            $message = $e->getMessage();
            Log::error("Erro ao buscar o total de ofertas do Mockoon: {$message}");
            throw $e;
        }
    }

    public function fetchOffers(int $page = 1): array
    {
        try {
            $this->client->get('offers', [
                'query' => [
                    'page' => $page,
                ],
            ]);

            $offersIdsResponse = $this->client->jsonToArray();
            $offers = [];

            foreach ($offersIdsResponse['data']['offers'] as $offer_id) {
                $this->client->get("offers/{$offer_id}");
                $offersResponse = $this->client->jsonToArray()['data'];

                $this->client->get("offers/{$offer_id}/images");
                $offersResponse['images'] = $this->client->jsonToArray()['data']['images'];

                $this->client->get("offers/{$offer_id}/prices");
                $offersResponse['price'] = $this->client->jsonToArray()['data']['price'];

                $offers[] = $offersResponse;
            }

            return $offers;
        } catch (Exception $e) {
            $message = $e->getMessage();
            Log::error("Erro ao buscar ofertas do Mockoon: {$message}");
            throw $e;
        }
    }

    public function convertOffer(array $offer): array
    {
        return [
            'external_id' => $offer['id'],
            'title' => $offer['title'],
            'description' => $offer['description'],
            'status' => $this->convertStatus($offer['status']),
            'stock' => $offer['stock'],
            'images' => $this->convertImages($offer['images']),
            'price' => $this->convertPrice($offer['price']),
        ];
    }


    private function convertStatus(string $status): string
    {
        return match ($status) {
            'paused' => 'inactive',
            default => $status,
        };
    }


    private function convertImages(array $images): array
    {
        return array_map(function ($image) {
            return [
                'url' => $image['url'],
                'title' => strtok(strtok(basename($image['url']), '?'), '.'), // Pega o nome do arquivo sem a extensão.
                'extension' => strtok(pathinfo($image['url'], PATHINFO_EXTENSION), '?'), // Pega a extensão do arquivo.
            ];
        }, $images);
    }


    private function convertPrice(float $price): int
    {
        return (int) (round($price, 2) * 100);
    }
}
