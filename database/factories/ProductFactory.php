<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Product;
use App\Category;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'slug' => $faker->name,
        'description' => $faker->paragraph($nbSentences = 3, $variableNbSentences = true),
        'price' => $faker->numberBetween($min = 100, $max = 9000),
        'image' => '5dfh4f455sdggdsfgsdfdfgfgdfg.jpg',
        'category_id' => factory(App\Category::class),
        'brand_id' => factory(App\Brand::class),
    ];
});
