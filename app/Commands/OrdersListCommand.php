<?php

namespace App\Services\Commands\V2;

final class OrdersListCommand
{
    /** @var int */
    private $userId;

    /** @var string */
    private $filialIds;

    /** @var int */
    private $status;

    /** @var int */
    private $perPage;

    /** @var int */
    private $page;

    /** @var string|null */
    private $dateStart;

    /** @var string|null */
    private $dateEnd;

    /** @var int|null */
    private $sumFrom;

    /** @var int|null */
    private $sumTo;

    /** @var string|null */
    private $searchText;

    /**
     * @param  int  $userId
     * @param  string  $filialIds
     * @param  int  $status
     * @param  int  $perPage
     * @param  int  $page
     * @param  string|null  $dateStart
     * @param  string|null  $dateEnd
     * @param  int|null  $sumFrom
     * @param  int|null  $sumTo
     * @param  string|null  $searchText
     */
    public function __construct(int $userId, string $filialIds, int $status, int $perPage, int $page, ?string $dateStart, ?string $dateEnd, ?int $sumFrom, ?int $sumTo, ?string $searchText)
    {
        $this->userId = $userId;
        $this->filialIds = $filialIds;
        $this->status = $status;
        $this->dateStart = $dateStart;
        $this->dateEnd = $dateEnd;
        $this->sumFrom = $sumFrom;
        $this->sumTo = $sumTo;
        $this->searchText = $searchText;
        $this->perPage = $perPage;
        $this->page = $page;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @return string
     */
    public function getFilialIds(): string
    {
        return $this->filialIds;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @return string|null
     */
    public function getDateStart(): ?string
    {
        return $this->dateStart;
    }

    /**
     * @return string|null
     */
    public function getDateEnd(): ?string
    {
        return $this->dateEnd;
    }

    /**
     * @return int|null
     */
    public function getSumFrom(): ?int
    {
        return $this->sumFrom;
    }

    /**
     * @return int|null
     */
    public function getSumTo(): ?int
    {
        return $this->sumTo;
    }

    /**
     * @return string|null
     */
    public function getSearchText(): ?string
    {
        return $this->searchText;
    }

    /**
     * @return int
     */
    public function getPerPage(): int
    {
        return $this->perPage;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }
}
