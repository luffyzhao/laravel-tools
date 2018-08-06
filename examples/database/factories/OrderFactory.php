<?php
use Faker\Generator as Faker;

$factory->define(App\Model\Order::class, function (Faker $faker) {
    return [
        'order_no' => $faker->uuid
    ];
});
