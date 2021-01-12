<?php

namespace App\Services\Microservices\User;

interface User
{
    /**
     * @param array $data
     * @return array
     */
    public function getUsers(array $data): array;
}
