<?php

namespace App\Console\Commands;

use App\Admin;
use App\Role;
use App\Services\LoggerBuilder;
use App\Services\ValidationService\ValidatorService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CreateSupreme extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'CreateSupreme {name} {email} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Создаёт главного администратора. Главный администратор имеет полные \n
     права над всей системой";

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $name = $this->argument('name');
        $password = $this->argument('password');
        $email = $this->argument('email');
        $request =[
            'name'=>$name,
            'password'=>$password,
            'email'=>$email
        ];
        $validator = new ValidatorService();
        $admin = new Admin();
        $validatorResult=$validator->validate($admin,$request);
        if ($validatorResult){
            $this->error($validatorResult);
            return;
        }
        $supremeRole = Role::find(1);
        if($supremeRole===null){
            $this->error("Роли не определены в базе данных. Запустите команду, находясь в \n
            главной директории проекта, 'php artisan db:seed', или обратитесь к администратору проекта");
            return;
        }

        if (!$supremeRole->admins->isEmpty()){
            $this->error("В системе должен быть только 1 главный администратор.\n
            администратор с правами 'supreme' уже числиться в базе данных. По всем вопросам - обратитесь к администратору проекта");
            return;
        }
        try{
            DB::beginTransaction();
            $admin=new Admin();
            $admin->name=$name;
            $admin->email=$email;
            $admin->password=Hash::make($password);
            $admin->role_id=1;
            $admin->save();
            DB::commit();
            $this->info("Успешно создан администратор с правами 'supreme'.\n
            name:{$name}, email{$email}, password{$password}");
        }catch (\Exception $exception){
            DB::rollBack();
            LoggerBuilder::getApiLogger()->critical($exception->getMessage());
            $this->error($exception->getMessage());
            return;
        }
    }
}
