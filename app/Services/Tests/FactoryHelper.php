<?php
/**
 * Created by PhpStorm.
 * User: Анна
 * Date: 01.12.2018
 * Time: 17:28
 */

namespace app\Services\Tests;


use App\Group;
use App\User;
use http\Exception\InvalidArgumentException;

trait FactoryHelper
{
    protected function getExistingModel(string $model)
    {
        switch ($model) {
            case 'App\Group':
                {
                    $group = Group::first();
                    if ($group === null) {
                        $group = factory(Group::class)->create();
                    }
                    return $group;
                }
            case 'App\User':
                {
                    $user = User::first();
                    if ($user === null) {
                        $user = factory(User::class)->create();
                    }
                    return $user;
                }
            default:
                {
                    throw new InvalidArgumentException("Undefined model: {$model}");
                }
        }
    }

    protected function getFakeModel(string $model)
    {
        switch ($model) {
            case 'App\Group':
                {
                    return factory(Group::class)->make();
                }
            case 'App\User':
                {
                    return factory(User::class)->make();
                }
            default:
                {
                    throw new InvalidArgumentException("Undefined model: {$model}");
                }
        }
    }

}