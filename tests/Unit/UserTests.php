<?php

namespace Tests\Unit;

use App\Services\Tests\FactoryHelper;
use App\User;
use Tests\TestCase;

class UserTests extends TestCase
{

    use FactoryHelper;


    public function testUserCreate()
    {
        $user = $this->getFakeModel(User::class);
        $this->get(route('user.create',
            ['email' =>  $user->email, 'last_name' => $user->last_name, 'first_name' => $user->first_name]))
        ->assertJson(['status'=>'ok']);
    }

    public function testUserEdit()
    {
        $user = $this->getExistingModel(User::class);
        $this->get(route('user.edit',
            [$user->id,'email' =>  $user->email, 'last_name' => $user->last_name, 'first_name' => $user->first_name,'state'=>$user->state?false:true]))
        ->assertJson(['status'=>'ok']);
    }

    public function testUserList()
    {
        $this->get(route('user.list'))
            ->assertJson(['status'=>'ok',
                'description'=>array()]);
    }

    public function testUserDelete()
    {
        $user = $this->getExistingModel(User::class);
        $this->get(route('user.delete',$user->id))
        ->assertJson(['status'=>'ok']);
    }

}
