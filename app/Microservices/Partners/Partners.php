<?php

namespace App\Services\Microservices\Partners;

interface Partners
{
    /**
     * @param int $id
     * @return array
     */
    public function getPureFilial(int $id): array;

    /**
     * @param array $ids
     * @return array
     */
    public function getFilials(array $ids): array;
}
