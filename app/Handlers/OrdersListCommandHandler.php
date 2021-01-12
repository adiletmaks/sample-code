<?php

namespace App\Services\Handlers\V2;

use App\Exceptions\ErrorCodes;
use App\Exceptions\NoticeException;
use App\Http\Filters\V2\OrderFilter;
use App\Microservices\Proxies\PartnersProxy;
use App\Microservices\Proxies\UserProxy;
use App\Models\Order;
use App\Services\Commands\V2\OrdersListCommand;
use App\Traits\FilialIdsToArrayTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

final class OrdersListCommandHandler
{
    use FilialIdsToArrayTrait;

    /** @var AclFilialsHandler */
    private $aclFilialsHandler;

    /** @var UserProxy */
    private $userProxy;

    /** @var PartnersProxy */
    private $partnersProxy;

    /** @var CurrentOrderStatusHandler */
    private $currentOrderStatusHandler;

    public function __construct(
        AclFilialsHandler $aclFilialsHandler,
        UserProxy $userProxy,
        PartnersProxy $partnersProxy,
        CurrentOrderStatusHandler $currentOrderStatusHandler
    ) {
        $this->aclFilialsHandler = $aclFilialsHandler;
        $this->userProxy = $userProxy;
        $this->partnersProxy = $partnersProxy;
        $this->currentOrderStatusHandler = $currentOrderStatusHandler;
    }

    /**
     * @param  OrdersListCommand  $command
     * @return LengthAwarePaginator
     * @throws NoticeException
     */
    public function handle(OrdersListCommand $command): LengthAwarePaginator
    {
        $filialIds = $this->filialIdsToArray($command->getFilialIds());

        $this->checkPermissions($command->getUserId(), $filialIds);

        $query = Order::with('orderStatus:order_id,time,type')
            ->whereIn('status', [Order::STATUS_PAID, Order::STATUS_REFUNDED])
            ->whereIn('filial_id', $filialIds)
            ->select([
                'id',
                'rahmet_order_id',
                'cash_system_number',
                'filial_id',
                'user_id',
                'total_price',
                'status',
                'current_order_status_type',
                'paid_at',
                'created_at',
            ]);

        $query = $this->filterQuery($query, $filialIds, $command);

        $perPage = $command->getPerPage();
        $page = $command->getPage();
        $skip = $perPage * ($page - 1);
        $total = $query->count();

        $orders = $query
            ->orderByDesc('id')
            ->skip($skip)
            ->take($perPage)
            ->get();

        $orders = $this->addUserInfoToCollection($orders);
        $orders = $this->addFilialInfoToCollection($orders);
        $orders = $this->addCurrentOrderStatusToCollection($orders);

        $path = Str::after(Paginator::resolveCurrentPath(), config('app.url'));

        return new LengthAwarePaginator(
            $orders,
            $total,
            $perPage,
            $page,
            [
                'path' => $path
            ]
        );
    }

    /**
     * @param  Builder  $query
     * @param  array  $filialIds
     * @param  OrdersListCommand  $command
     * @return Builder
     */
    private function filterQuery(Builder $query, array $filialIds, OrdersListCommand $command): Builder
    {
        return (new OrderFilter(
            $query,
            [
                'filial_ids' => $filialIds,
                'status' => $command->getStatus(),
                'date_start' => $command->getDateStart(),
                'date_end' => $command->getDateEnd(),
                'sum_from' => $command->getSumFrom(),
                'sum_to' => $command->getSumTo(),
                'search_text' => $command->getSearchText(),
            ]
        ))->apply();
    }

    /**
     * @param  Collection  $orders
     * @return Collection
     */
    private function addUserInfoToCollection(Collection $orders): Collection
    {
        $ids = collect($orders)
            ->unique('user_id')
            ->implode('user_id', ',');
        $users = collect($this->userProxy->getUsers($ids)['items'] ?? []);
        /** @var Order $order */
        foreach ($orders as $order) {
            $orderUser = $users->where('id', $order->user_id)->first();
            $order->setAttribute('user', $orderUser);
        }

        return $orders;
    }

    /**
     * @param  Collection  $orders
     * @return Collection
     */
    private function addCurrentOrderStatusToCollection(Collection $orders): Collection
    {
        /** @var Order $order */
        foreach ($orders as $order) {
            $order->setAttribute(
                'current_order_status',
                $this->currentOrderStatusHandler->handle($order)
            );
        }

        return $orders;
    }

    /**
     * @param  Collection  $orders
     * @return Collection
     */
    private function addFilialInfoToCollection(Collection $orders): Collection
    {
        $ids = collect($orders)->pluck('filial_id')
            ->unique()
            ->values()
            ->toArray();
        $filials = collect($this->partnersProxy->getFilials($ids));
        /** @var Order $order */
        foreach ($orders as $order) {
            $orderFilial = $filials->where('id', $order->filial_id)->first();
            $order->setAttribute('filial', $orderFilial);
        }

        return $orders;
    }

    /**
     * @param  int  $userId
     * @param  array  $filialIds
     * @throws NoticeException
     */
    private function checkPermissions(int $userId, array $filialIds): void
    {
        $availableFilialIds = $this->aclFilialsHandler->getAllowedFilialIds($userId, config('acl_permissions.takeaway_order_fetch'));
        if (count(array_intersect($availableFilialIds, $filialIds)) !== count($filialIds)) {
            throw new NoticeException('У вас нет доступа к филиалу', ErrorCodes::FORBIDDEN);
        }
    }
}
