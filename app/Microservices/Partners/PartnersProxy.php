<?php

namespace App\Services\Microservices\Partners;

use App\Traits\HashTrait;
use Illuminate\Contracts\Cache\Repository as CacheRepository;

final class PartnersProxy implements Partners
{
    use HashTrait;

    /** @var CacheRepository */
    private $cacheRepository;

    /** @var Partners */
    private $partners;

    /** @var int */
    private $cacheTtl;

    public function __construct(CacheRepository $cacheRepository, Partners $partners)
    {
        $this->cacheRepository = $cacheRepository;
        $this->partners = $partners;
        $this->cacheTtl = (int) config('microservices.partners.cache_ttl');
    }

    public function getPureFilial(int $id): array
    {
        $cacheKey = $this->generateHash('partners-pure-filial-'.$id);

        return $this->cacheRepository->remember($cacheKey, $this->cacheTtl, function () use ($id) {
            return $this->partners->getPureFilial($id);
        });
    }

    /**
     * @param array $ids
     * @return array
     */
    public function getFilials(array $ids): array
    {
        $cacheKey = $this->generateHash('partners_filials_'.implode(',', $ids));

        return $this->cacheRepository->remember($cacheKey, $this->cacheTtl, function () use ($ids): array {
            return $this->partners->getFilials($ids);
        });
    }
}
