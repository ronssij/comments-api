<?php

use App\Models\Blog;
use Faker\Generator as Faker;

$factory->define(Blog::class, function (Faker $faker) {
    return [
        'title' => $this->faker->text(20),
        'body'  => $this->faker->text(300),
    ];
});
