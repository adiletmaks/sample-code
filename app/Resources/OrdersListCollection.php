<?php

namespace App\Http\Resources\V2;

use App\Services\Handlers\V2\OrdersCountsHandler;
use App\Traits\SuccessResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class OrdersListCollection extends ResourceCollection
{
    use SuccessResponseTrait;

    /** @var OrdersCountsHandler */
    private $ordersCountsHandler;

    /** @var string */
    private $filialIds;

    public function __construct($resource, string $filialIds, OrdersCountsHandler $ordersCountsHandler)
    {
        $this->filialIds = $filialIds;
        $this->ordersCountsHandler = $ordersCountsHandler;

        parent::__construct($resource);
    }
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     * @noinspection PhpMissingParamTypeInspection
     */
    public function toArray($request): array
    {
        return array_merge(
            $this->successResponse('Список заказов'),
            [
                'data' => [
                    'orders' => OrdersResource::collection($this->collection),
                    'amount' => new OrdersAmountResource($this->ordersCountsHandler->handle($this->filialIds)),
                ],
            ]
        );
    }
}
