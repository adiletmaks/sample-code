<?php

namespace App\Http\Controllers\V2;

use App\Exceptions\NoticeException;
use App\Http\Requests\V2\OrderRequest;
use App\Http\Requests\V2\OrdersRequest;
use App\Http\Resources\V2\OrderResource;
use App\Http\Resources\V2\OrdersListCollection;
use App\Services\Commands\V2\OrdersListCommand;
use App\Services\Handlers\V2\OrderDetailsCommandHandler;
use App\Services\Handlers\V2\OrdersCountsHandler;
use Illuminate\Bus\Dispatcher;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderController extends Controller
{
    /**
     * @OA\Get (
     *      path="/v2/orders",
     *      operationId="v2-getOrders",
     *      tags={"orders v2"},
     *      summary="Получение списка заказов v2",
     *      description="Получение списка заказов для мерчантапп v2",
     *      parameters={
     *        {"name":"X-User","in":"header","type":"integer","required":true,"description":"Id юзера"},
     *        {"name":"filial_ids","in":"query","type":"string","required":true,"description":"Id филиалов через запятую"},
     *        {"name":"date_start","in":"query","type":"date","required":false,"description":"Дата с начала которой достается список заказов"},
     *        {"name":"date_end","in":"query","type":"date","required":false,"description":"Дата до которой достается список заказов"},
     *        {"name":"sum_from","in":"query","type":"integer","required":false,"description":"Сумма с которой начинаются заказы"},
     *        {"name":"sum_to","in":"query","type":"integer","required":false,"description":"Сумма до которой достается список заказов"},
     *        {"name":"status","in":"query","type":"string","required":true,"description":"Статуc 1,2,3,4"},
     *        {"name":"search_text","in":"query","type":"string","required":false,"description":"Поиск"},
     *        {"name":"limit","in":"query","type":"integer","required":false,"description":"orders amount limit - default 15"},
     *        {"name":"page","in":"query","type":"integer","required":false,"description":"page number"}
     *      },
     *      @OA\Response(response=200, description="Список заказов"),
     *      @OA\Response(response=400, description="Что-то не так")
     * )
     *
     * @param  OrdersRequest  $request
     * @param  Dispatcher  $dispatcher
     * @param  OrdersCountsHandler  $ordersCountsHandler
     * @return JsonResource
     * @throws NoticeException
     */
    public function getOrders(OrdersRequest $request, Dispatcher $dispatcher, OrdersCountsHandler $ordersCountsHandler): JsonResource
    {
        return new OrdersListCollection(
            $dispatcher->dispatch(
                new OrdersListCommand(
                    $this->getUserId(),
                    $request->get('filial_ids'),
                    $request->get('status'),
                    $request->get('per_page', 15),
                    $request->get('page', 1),
                    $request->get('date_start'),
                    $request->get('date_end'),
                    $request->get('sum_from'),
                    $request->get('sum_to'),
                    $request->get('search_text')
                )
            ),
            $request->get('filial_ids'),
            $ordersCountsHandler
        );
    }

    /**
     * @OA\Get (
     *      path="/v2/order",
     *      operationId="getOrder_v2",
     *      tags={"orders v2"},
     *      summary="Получение деталей заказа v2",
     *      description="Получение деталей заказа v2",
     *      parameters={
     *        {"name":"id","in":"query","type":"integer","required":true,"description":"Id заказа"},
     *      },
     *      @OA\Response(response=200, description="Детали заказа"),
     *      @OA\Response(response=400, description="Что-то не так")
     * )
     *
     * @param  OrderRequest  $request
     * @param  OrderDetailsCommandHandler  $handler
     * @return JsonResponse
     * @throws NoticeException
     */
    public function getOrder(OrderRequest $request, OrderDetailsCommandHandler $handler): JsonResponse
    {
        return $this->response(
            'Детали заказа',
            new OrderResource($handler->handle($this->getUserId(), $request->get('rahmet_order_id')))
        );
    }
}
