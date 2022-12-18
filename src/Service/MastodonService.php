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
    private string $mastodonAccessToken;

    private ?string $token = null;

    public function __construct(
        HttpClientInterface $mastodonClient,
        string $mastodonClientId,
        string $mastodonClientSecret,
        string $mastodonAccessToken
    )
    {
        $this->mastodonClient = $mastodonClient;
        $this->mastodonClientId = $mastodonClientId;
        $this->mastodonClientSecret = $mastodonClientSecret;
        $this->mastodonAccessToken = $mastodonAccessToken;
    }

    private function api(): ScopingHttpClient
    {
        return $this->mastodonClient;
    }

    public function getToken(): ?string
    {
        if (!$this->token) {
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
            } catch (TransportExceptionInterface $e) {
                return null;
            }
        }

        return $this->token;
    }

    public function postStatus(string $status, string $visibility = 'public'): ?array
    {
        try {
            $response = $this->api()->request('POST', '/api/v1/statuses', [
                'headers' => [
                    'Authorization' => 'Bearer '. $this->mastodonAccessToken,
                    //'Idempotency-Key' => 'kissanpissa-'. time(),
                ],
                'body' => [
                    'status' => $status,
                    'visibility' => $visibility,
                ]
            ]);

            return $response->toArray();
        }
        catch (TransportExceptionInterface $e) {
            return null;
        }
    }
}
