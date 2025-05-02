<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class SpotifyService
{
    private HttpClientInterface $client;
    private string $token = '';

    public function __construct(HttpClientInterface $client, string $clientId, string $clientSecret)
    {
        $this->client = $client;

        $response = $this->client->request('POST', 'https://accounts.spotify.com/api/token', [
            'body' => [
                'grant_type' => 'client_credentials',
            ],
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode($clientId . ':' . $clientSecret),
            ],
        ]);

        $data = $response->toArray();
        $this->token = $data['access_token'];
    }

    public function searchArtist(string $name): ?array
    {
        $response = $this->client->request('GET', 'https://api.spotify.com/v1/search', [
            'query' => [
                'q' => $name,
                'type' => 'artist',
                'limit' => 1,
            ],
            'headers' => [
                'Authorization' => 'Bearer ' . $this->token,
            ],
        ]);

        $data = $response->toArray();

        return $data['artists']['items'][0] ?? null;
    }
}
