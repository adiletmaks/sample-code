<?php

namespace Tests\Feature\V2;

use App\Models\ItemAdditive;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\ApiTestCase;

class OrderTest extends ApiTestCase
{
    use RefreshDatabase, WithFaker;

    public const FILIAL_ID = 582;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpFaker();
    }

    /**
     * @return void
     */
    public function testGetOrders(): void
    {
        factory(Order::class, 10)->create([
            'filial_id' => self::FILIAL_ID,
        ]);

        $filialIds = implode(',', [self::FILIAL_ID]);

        $this
            ->json(
                'GET',
                route('v2.orders'),
                [
                    'filial_ids' => $filialIds,
                    'status'     => 1,
                ],
                [
                    'X-User' => $this->faker->numberBetween(1),
                ]
            )->assertStatus(200)
            ->assertJsonFragment([
                'status'     => 'success',
                'error_code' => 0
            ])->assertJsonStructure(
                $this->resourceStructureWithPagination([
                    'orders' => [
                        [
                            'id',
                            'rahmet_order_id',
                            'cash_system_number',
                            'total_price',
                            'current_order_status' => [
                                'type',
                                'created_at',
                                'seconds_left',
                            ],
                            'created_at',
                            'filial'               => [
                                'id',
                                'name',
                                'logotype_url',
                            ],
                            'user'                 => [
                                'first_name',
                                'phone',
                            ],
                        ],
                    ],
                    'amount' => [
                        'created',
                        'accepted',
                        'ready',
                    ],
                ])
            );
    }

    /**
     * @return void
     */
    public function testGetOrderDetails(): void
    {
        $order = factory(Order::class)->create([
            'filial_id' => self::FILIAL_ID,
        ]);
        $orderItem = factory(OrderItem::class)->create([
            'order_id' => $order->id,
        ]);
        factory(ItemAdditive::class)->create([
            'order_item_id' => $orderItem->id,
        ]);

        $this
            ->json(
                'GET',
                route('v2.order'),
                [
                    'rahmet_order_id' => $order->rahmet_order_id,
                ],
                [
                    'X-User' => $this->faker->numberBetween(1),
                ]
            )->assertStatus(200)
            ->assertJsonFragment([
                'status'     => 'success',
                'error_code' => 0
            ])->assertJsonStructure(
                $this->resourceStructure([
                    'id',
                    'rahmet_order_id',
                    'cash_system_number',
                    'total_price',
                    'current_order_status' => [
                        'type',
                        'created_at',
                        'seconds_left',
                    ],
                    'comment',
                    'created_at',
                    'order_items'          => [
                        [
                            'id',
                            'amount',
                            'name',
                            'price',
                            'images'         => [
                                [
                                    'id',
                                    'image_url',
                                ]
                            ],
                            'item_additives' => [
                                [
                                    'id',
                                    'name',
                                    'price',
                                ]
                            ],
                        ]
                    ],
                    'filial'               => [
                        'id',
                        'name',
                        'logotype_url',
                    ],
                    'user'                 => [
                        'first_name',
                        'phone',
                    ],
                ])
            );
    }
}
