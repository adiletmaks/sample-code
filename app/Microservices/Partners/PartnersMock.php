<?php

namespace App\Services\Microservices\Partners;

use Exception;
use Illuminate\Foundation\Testing\WithFaker;

final class PartnersMock implements Partners
{
    use WithFaker;

    public function __construct()
    {
        $this->setUpFaker();
    }

    /**
     * @param int $id
     *
     * @return array
     *
     * @throws Exception
     */
    public function getPureFilial(int $id): array
    {
        return [
            'id' => $id,
            'partner_id' => $this->faker->randomNumber(),
            'name' => $this->faker->word(),
            'address' => $this->faker->address,
            'create_at' => $this->faker->dateTime,
            'update_at' => $this->faker->dateTime,
            'is_active' => true,
            'coordinates' => $this->faker->latitude.','.$this->faker->longitude,
            'phones' => $this->faker->phoneNumber,
            'is_hidden' => false,
            'legal_entity_id' => $this->faker->randomNumber(),
            'town_id' => $this->faker->randomNumber(),
            'start_at' => $this->faker->dateTime,
            'end_at' => $this->faker->dateTime,
            'closed_date' => $this->faker->dateTime,
            'closed_staff' => 11560295,
            'closed_comment' => $this->faker->sentence,
            'has_campaign' => false,
            'description' => $this->faker->sentence,
            'website_url' => $this->faker->url,
            'social_networks' => null,
            'token' => $this->faker->md5,
            'manual_pay' => false,
        ];
    }

    /**
     * @param array $ids
     *
     * @return array
     *
     * @throws Exception
     */
    public function getFilials(array $ids): array
    {
        $arr = [];
        foreach ($ids as $id) {
            $arr[] = [
                'id' => $id,
                'name' => $this->faker->sentence,
                'town_id' => $this->faker->randomNumber(),
                'address' => [
                    'address' => $this->faker->address,
                ],
                'rating' => [
                    'mark' => 0,
                    'count' => 0,
                ],
                'partner' => [
                    'id' => $this->faker->randomNumber(),
                    'name' => $this->faker->word,
                    'logotype_url' => $this->faker->url,
                ],
            ];
        }

        return $arr;
    }
}
