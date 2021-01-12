<?php

namespace App\Services\Microservices\User;

use Illuminate\Foundation\Testing\WithFaker;

final class UserMock implements User
{
    use WithFaker;

    public function __construct()
    {
        $this->setUpFaker();
    }

    /**
     * @param  array  $data
     * @return array
     */
    public function getUsers(array $data): array
    {
        $items = [];
        for ($i = 0, $iMax = count($data); $i < $iMax; $i++) {
            $items[] = [
                'first_name'    => $this->faker->firstName,
                'last_name'     => $this->faker->lastName,
                'gender'        => $this->faker->randomElement(['man', 'woman']),
                'bday'          => null,
                'email'         => $this->faker->email,
                'phone'         => $this->faker->phoneNumber,
                'id'            => $this->faker->randomNumber(),
                'phone_confirm' => 0,
                'status'        => 1,
                'create_at'     => $this->faker->dateTime,
                'update_at'     => $this->faker->dateTime,
                'avatar'        => null,
                'town_id'       => 1,
                'timezone'      => null,
            ];
        }

        return [
            'items'      => $items,
            'pagination' => [
                'total_items'  => count($data),
                'limit'        => 500,
                'current_page' => 1,
                'total_pages'  => 1,
            ],
        ];
    }
}
