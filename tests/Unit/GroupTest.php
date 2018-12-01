<?php

namespace Tests\Feature;

use App\Group;
use App\Services\Tests\FactoryHelper;
use Tests\TestCase;


class GroupTest extends TestCase
{
   use FactoryHelper;

    public function testGroupCreate()
    {

        $group = $this->getFakeModel(Group::class);
        $this->get(route('group.create',['name'=>$group->name]))
        ->assertJson(['status'=>'ok']);
    }

    public function testGroupEdit()
    {
        $group=$this->getExistingModel(Group::class);
        $this->get(route('group.edit',[$group->id,'name'=>$group->name]))
            ->assertJson(['status'=>'ok']);
    }

    public function testGroupList()
    {
        $this->get(route('group.list'))
        ->assertJson(['status'=>'ok',
            'description'=>array()]);
    }

    public function testGroupDelete()
    {
        $group = $this->getExistingModel(Group::class);
        $this->get(route('group.delete', $group->id))
            ->assertJson(['status' => 'ok']);
    }
}
