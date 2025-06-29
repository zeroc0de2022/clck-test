<?php

namespace Database\Factories;

use App\Models\LinkVisit;
use Illuminate\Database\Eloquent\Factories\Factory;

class LinkVisitFactory extends Factory
{
    protected $model = LinkVisit::class;

    public function definition(): array
    {
        return [
            'link_id'    => null, // можно переопределить в тесте вручную
            'ip_address' => $this->faker->ipv4,
        ];
    }
}
