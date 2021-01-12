<?php

namespace App\Services\Microservices\Partners;

use App\Services\Microservices\BaseHttpService;
use Illuminate\Http\Client\RequestException;

final class PartnersConcrete extends BaseHttpService implements Partners
{
    /**
     * @param int $id
     * @return array<string, string>
     * @throws RequestException
     */
    public function getPureFilial(int $id): array
    {
        $response = $this->client->get(
            $this->getHost().'/partner/filial/pure/'.$id
        );

        $this->throw($response);

        return $response['data'] ?? [];
    }

    /**
     * @param array $ids
     * @return array
     * @throws RequestException
     */
    public function getFilials(array $ids): array
    {
        if (empty($ids)) {
            return [];
        }

        $response = $this->client->get(
            $this->getHost().'/partner/filials/cards/ids',
            [
                'ids' => $ids,
            ]
        );

        $this->throw($response);

        return $response['data'] ?? [];
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return config('microservices.partners.host');
    }
}
