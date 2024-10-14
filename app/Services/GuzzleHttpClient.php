<?php

namespace App\Services;

use App\Services\Contracts\HttpClientInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class GuzzleHttpClient implements HttpClientInterface
{
    protected Client $client;
    protected $response;

    /**
     * Construtor do GuzzleHttpClient.
     *
     * @param string $baseUri A URL base para realizar requisições.
     */
    public function __construct(string $baseUri = '')
    {
        $this->client = new Client([
            'base_uri' => $baseUri,
        ]);
    }

   /**
     * Submete uma requisição HTTP (GET, POST, etc.).
     *
     * @param string $method O método HTTP (GET, POST, etc.).
     * @param string $url A URL para a requisição.
     * @param array $options Opções da requisição, como query params ou body.
     * @return string Resposta da requisição.
     * @throws RequestException
     */
    public function submit(string $method, string $url, array $options = []): string
    {
        try {
            $this->response = null;
            $response = $this->client->request($method, $url, $options);
            $this->response = $response->getBody()->getContents();
            return $this->response;
        } catch (RequestException $e) {
            Log::error("Erro ao realizar requisição {$method} para {$url}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Executa uma requisição HTTP GET.
     *
     * @param string $url A URL para a requisição.
     * @param array $options Opções da requisição, como query params.
     * @return string Resposta da requisição.
     * @throws RequestException
     */
    public function get(string $url, array $options = []): string
    {
        return $this->submit('GET', $url, $options);
    }

    /**
     * Executa uma requisição HTTP POST.
     *
     * @param string $url A URL para a requisição.
     * @param array $options Opções da requisição, como query params ou body.
     * @return string Resposta da requisição.
     * @throws RequestException
     */
    public function post(string $url, array $options = []): string
    {
        return $this->submit('POST', $url, $options);
    }

    /**
     * Converte a resposta da requisição para um array.
     *
     * @return array Resposta da requisição convertida para um array.
     */
    public function jsonToArray(): array
    {
        return json_decode($this->response, true);
    }
}
