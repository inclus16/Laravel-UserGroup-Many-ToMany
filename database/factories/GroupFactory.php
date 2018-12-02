<?php
/**
 * Created by PhpStorm.
 * User: Анна
 * Date: 01.12.2018
 * Time: 17:05
 */
use Faker\Generator as Faker;
/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\Group::class, function (Faker $faker) {
    return [
        'name' => $faker->name
    ];
});