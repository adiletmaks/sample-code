<?php

namespace App\Http\Resources\V2;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property int id
 * @property int rahmet_order_id
 * @property int cash_system_number
 * @property int total_price
 * @property int current_order_status_type
 * @property string created_at
 * @property array filial
 * @property array user
 * @property array current_order_status
 */
class OrdersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     * @noinspection PhpMissingParamTypeInspection
     */
    public function toArray($request): array
    {
        return [
            'id'                 => $this->id,
            'rahmet_order_id'    => $this->rahmet_order_id,
            'cash_system_number' => $this->cash_system_number,
            'total_price'        => $this->total_price,
            'created_at'         => Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),

            'current_order_status' => new CurrentOrderStatusResource($this->current_order_status),
            'filial'               => new FilialsResource($this->filial),
            'user'                 => new UsersResource($this->user),
        ];
    }
}
