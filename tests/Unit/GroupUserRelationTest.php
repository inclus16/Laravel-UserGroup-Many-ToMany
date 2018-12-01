<?php

namespace Tests\Feature;

use App\Group;
use App\Services\Tests\FactoryHelper;
use App\User;
use Tests\TestCase;

class GroupUserRelationTest extends TestCase
{

    use FactoryHelper;

    public function testUserListGroups()
    {
        $user = $this->getExistingModel(User::class);
        $this->get(route('user.group.list', $user->id))
            ->assertJson(['status' => 'ok',
                'description' => array()]);
    }

    public function testGroupListUsers()
    {
        $group = $this->getExistingModel(Group::class);
        $this->get(route('group.user.list', $group->id))
            ->assertJson(['status' => 'ok',
                'description' => array()]);
    }

    public function testUserJoinGroup()
    {
        $user = $this->getExistingModel(User::class);
        $group = $this->getExistingModel(Group::class);
        $this->get(route('user.join', [$user->id, 'group_id' => $group->id]))
            ->assertJson(['status' => 'ok']);
    }
    public function testGroupExcludeUser()
    {
        $user = $this->getExistingModel(User::class);
        $group=$this->getExistingModel(Group::class);
        $this->get(route('group.exclude',['id'=>$group->id,'user_id'=>$user->id]))
            ->assertJson(['status'=>'ok']);
    }
}
