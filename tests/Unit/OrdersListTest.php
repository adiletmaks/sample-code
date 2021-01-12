<?php

namespace Tests\Unit\V2;

use App\Exceptions\NoticeException;
use App\Models\Order;
use App\Services\Commands\V2\OrdersListCommand;
use App\Services\Handlers\V2\OrdersListCommandHandler;
use App\Traits\FilialIdsToArrayTrait;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrdersListTest extends TestCase
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
     * @throws \Exception
     */
    public function testGetOrders(): void
    {
        factory(Order::class, 3)->create([
            'filial_id' => self::FILIAL_ID,
        ]);

        $filialIds = [self::FILIAL_ID];
        $status = 1;
        $command = new OrdersListCommand(
            $this->faker->numberBetween(1),
            implode(',', [self::FILIAL_ID]),
            $status,
            15,
            1,
            null,
            null,
            null,
            null,
            null
        );
        $orders = app(OrdersListCommandHandler::class)->handle($command);

        foreach ($orders as $order) {
            $this->assertDatabaseHas('orders', [
                'id'                        => $order->id,
                'rahmet_order_id'           => $order->rahmet_order_id,
                'cash_system_number'        => $order->cash_system_number,
                'total_price'               => $order->total_price,
                'current_order_status_type' => $order->current_order_status_type,
            ]);
            self::assertEquals($order->current_order_status_type, $status);
            self::assertContains($order->filial_id, $filialIds);
            self::assertNotEmpty($order->filial['id']);
            self::assertNotEmpty($order->filial['name']);
            self::assertNotEmpty($order->user['first_name']);
            self::assertNotEmpty($order->user['phone']);
        }
    }

    /**
     * @throws NoticeException
     */
    public function testCheckPermissionsFail(): void
    {
        $filialId = $this->faker->numberBetween(1);
        factory(Order::class, 3)->create([
            'filial_id' => $filialId,
        ]);

        $filialIds = [$filialId];
        $status = 1;
        $command = new OrdersListCommand(
            $this->faker->numberBetween(1),
            implode(',', $filialIds),
            $status,
            15,
            1,
            null,
            null,
            null,
            null,
            null
        );
        $this->expectException(NoticeException::class);
        app(OrdersListCommandHandler::class)->handle($command);
    }

    public function testFilialIdsToArray(): void
    {
        $class = new class {
            use FilialIdsToArrayTrait;

            public function test(string $filialIds): array
            {
                return $this->filialIdsToArray($filialIds);
            }
        };
        $filialIds = '1,2';
        self::assertEquals([1, 2], (new $class)->test($filialIds));
    }

    /**
     * @throws NoticeException
     */
    public function testOrdersFilterByRahmetOrderId(): void
    {
        $rahmetOrderId = $this->faker->numberBetween(1);
        factory(Order::class)->create([
            'rahmet_order_id' => $rahmetOrderId,
            'filial_id'       => self::FILIAL_ID,
        ]);
        factory(Order::class, 10)->create([
            'filial_id' => self::FILIAL_ID,
        ]);

        $filialIds = [self::FILIAL_ID];
        $status = 1;
        $command = new OrdersListCommand(
            $this->faker->numberBetween(1),
            implode(',', $filialIds),
            $status,
            15,
            1,
            null,
            null,
            null,
            null,
            $rahmetOrderId
        );
        $orders = app(OrdersListCommandHandler::class)->handle($command);

        $expectedOrder = $orders[0];
        self::assertEquals($expectedOrder->rahmet_order_id, $rahmetOrderId);
    }

    /**
     * @throws NoticeException
     */
    public function testOrdersFilterByCashSystemNumber(): void
    {
        $cashSystemNumber = $this->faker->numberBetween(1);
        factory(Order::class)->create([
            'cash_system_number' => $cashSystemNumber,
            'filial_id'          => self::FILIAL_ID,
        ]);
        factory(Order::class, 10)->create([
            'filial_id' => self::FILIAL_ID,
        ]);

        $filialIds = [self::FILIAL_ID];
        $status = 1;
        $command = new OrdersListCommand(
            $this->faker->numberBetween(1),
            implode(',', $filialIds),
            $status,
            15,
            1,
            null,
            null,
            null,
            null,
            $cashSystemNumber
        );
        $orders = app(OrdersListCommandHandler::class)->handle($command);

        $expectedOrder = $orders[0];
        self::assertEquals($expectedOrder->cash_system_number, $cashSystemNumber);
    }
}
