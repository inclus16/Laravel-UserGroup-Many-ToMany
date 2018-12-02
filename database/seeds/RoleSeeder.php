<?php

use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{

    private $roles = [
        1 => ['name' => 'supreme',
            'description' => 'Полные права над всей системой: создание, удаление, редактирование, просмотр других администраторов, пользателей, групп'],
        2 => ['name' => 'executor',
            'description' => 'Права создания,удаления,редактирования и просмотра пользователей и групп'],
        3 => ['name' => 'watcher',
            'description' => 'Право просмотра пользователей и групп']
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try{
            \Illuminate\Support\Facades\DB::beginTransaction();
            foreach ($this->roles as $role){
                (new \App\Role(['name'=>$role['name'],'description'=>$role['description']]))->save();
            }
            \Illuminate\Support\Facades\DB::commit();
        }catch (Exception $exception){
            \Illuminate\Support\Facades\DB::rollBack();
            \App\Services\LoggerBuilder::getApiLogger()->critical($exception->getMessage());
        }
    }
}
