<?php

namespace App\Services\Microservices\User;

use App\Exceptions\Gateway\WrongDataException;
use App\Services\Microservices\BaseHttpService;
use Illuminate\Http\Client\RequestException;

final class UserConcrete extends BaseHttpService implements User
{
    /**
     * @return string
     */
    public function getHost(): string
    {
        return config('microservices.user.host');
    }

    /**
     * Получить данные пользователей по id.
     *
     * @param array $data
     * @return array
     * @throws RequestException
     */
    public function getUsers(array $data): array
    {
        $response = $this->client
            ->asForm()
            ->get(
                $this->getHost().'/user/v2/search',
                $data
            );

        $this->throw($response);

        if (! isset($response['data']['items'])) {
            report(new WrongDataException('items does not exists'));
        }

        return $response['data'] ?? [];
    }
}
