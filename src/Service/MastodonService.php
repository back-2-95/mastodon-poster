<?php

namespace App\Service;

use Symfony\Component\HttpClient\ScopingHttpClient;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class MastodonService
{
    private HttpClientInterface $mastodonClient;
    private string $mastodonClientId;
    private string $mastodonClientSecret;

    public function __construct(
        HttpClientInterface $mastodonClient,
        string $mastodonClientId,
        string $mastodonClientSecret
    )
    {
        $this->mastodonClient = $mastodonClient;
        $this->mastodonClientId = $mastodonClientId;
        $this->mastodonClientSecret = $mastodonClientSecret;
    }

    private function api(): ScopingHttpClient
    {
        return $this->mastodonClient;
    }

    public function getToken(): ?string
    {
        try {
            $response = $this->api()->request('POST', '/oauth/token', [
                'body' => [
                    'grant_type' => 'client_credentials',
                    'client_id' => $this->mastodonClientId,
                    'client_secret' => $this->mastodonClientSecret,
                    'scope' => 'read write',
                ]
            ]);

            return $response->toArray()['access_token'];
        }
        catch (TransportExceptionInterface $e) {
            return null;
        }
    }
}
