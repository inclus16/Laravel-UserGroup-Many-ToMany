<?php

namespace App\Http\Controllers;


use App\Group;
use App\Services\ModelServices\CRUDService;
use App\Services\LoggerBuilder;
use App\Services\ResponseBuilder;
use App\Services\ValidationService\ValidatorService;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;


class UsersController extends Controller
{
    use  ResponseBuilder;
    private $logger;
    private $crud;
    private $validator;

    public function __construct()
    {
        $this->logger = LoggerBuilder::getApiLogger();
        $this->validator=new ValidatorService();
        $this->crud=new CRUDService();
    }

    public function userCreate(Request $request)
    {
        $address = $request->ip();
        $user = new User();
        $validatorResult=$this->validator->validate($user,$request->all());
        if($validatorResult){
            return $this->buildResponse(false,400,$validatorResult);
        }else{
            try {
                $this->crud->update($user, $request);
                return $this->buildResponse(true,200);
            }catch (\Exception $exception){
                $this->logger->critical("Неизвестная ошибка при попытке создать пользователя с IP {$address}, 
                                сообщение ошибки: {$exception->getMessage()}");
                return $this->buildResponse(false,500);
            }
        }
    }

    public function userEdit(Request $request,int $id)
    {
        $address = $request->ip();
        $user = User::find($id);
        if(is_null($user)){
            return $this->buildResponse(false,400,"Can't find user with ID {$id}");
        }
        $validatorResult=$this->validator->validate($user,$request->all());
        if($validatorResult){
            return $this->buildResponse(false,400,$validatorResult);
        }else{
            try {
                $this->crud->update($user, $request);
                return Response::json($this->successResponse,200,[],$this->jsonOptions);
            }catch (\Exception $exception){
                $this->logger->critical("Неизвестная ошибка при попытке редактировать пользователя c ID {$id} с IP {$address}, 
                сообщение ошибки: {$exception->getMessage()}");
                return $this->buildResponse(false,500);
            }
        }
    }

    public function userList()
    {
        $users = User::all();
        return $this->buildResponse(true,200,$users);
    }

    public function userDelete(Request $request,int $id)
    {
        $address=$request->ip();
        try{
            $user=User::find($id);
            if(is_null($user)){
                return $this->buildResponse(false,400,"Can't find user with ID {$id}");
            }
            $this->crud->delete($user);
            return $this->buildResponse(true,200);
        }catch (\Exception $exception){
            $this->logger->critical("Неизвестная ошибка при попытке удалить пользователя c ID {$id} с IP {$address}, 
                сообщение ошибки: {$exception->getMessage()}");
            return $this->buildResponse(false,500);
        }
    }

    /**
     * Тут начинается many-to-many User
     * Тоже можно было вынести логику.
     * Но на данный момент тут не так много кода.
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function userJoin(Request $request,int $id)
    {
        $validator = Validator::make($request->all(),
            [
                'group_id' => 'required|numeric',
            ]);
        if ($validator->fails()) {
            return $this->buildResponse(false, 400, $validator->errors());
        }
        $address = $request->ip();
        try {
            $groupId=$request->input('group_id');
            $user = User::find($id);
            if ($user===null){
                return $this->buildResponse(false,400,"Can't find user with ID {$id}");
            }
            if (Group::find($groupId)===null){
                return $this->buildResponse(false,400,"Can't find group with ID {$groupId}");
            }
            DB::beginTransaction();
            $testDuplicateRow = $user->groups()->find($groupId);
            if($testDuplicateRow!==null){
                return $this->buildResponse(false,400,"Already joined");
            }
            $user->groups()->attach($groupId);
            DB::commit();
            return $this->buildResponse(true, 200);
        } catch (\Exception $exception) {
            DB::rollBack();
            $this->logger->critical("Неизвестная ошибка при попытке добавить пользователя c ID {$id} 
             в группу {$groupId} с IP {$address}, 
                сообщение ошибки: {$exception->getMessage()}");
            return $this->buildResponse(false, 500);
        }
    }

    public function userJoinedList(Request $request,int $id)
    {
        $user = User::find($id);
        if ($user===null){
            return $this->buildResponse(false,400,"Can't find user with ID {$id}");
        }
        return $this->buildResponse(true,200,$user->groups);
    }
}
