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
use Illuminate\Support\Facades\Validator;


class GroupController extends Controller
{

    use  ResponseBuilder;
    private $logger;
    private $crud;
    private $validator;

    public function __construct()
    {
        $this->logger = LoggerBuilder::getApiLogger();
        $this->validator = new ValidatorService();
        $this->crud = new CRUDService();
    }

    /**
     * Вообще можно было всё прописать в контроллере (валидацию, CRUD моделей)
     * но у нас больше 1 сущности (а в реалиях немеренно может быть). Темболее
     * они слабо специфичны, так что проще было описать общую логику и
     * вынести её из контроллера.
     * см. папку app/Services
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function groupCreate(Request $request)
    {
        $address = $request->ip();
        $group = new Group();
        $validatorResult = $this->validator->validate($group, $request->all());
        if ($validatorResult) {
            return $this->buildResponse(false, 400, $validatorResult);
        } else {
            try {
                $this->crud->update($group, $request);
                return $this->buildResponse(true, 200);
            } catch (\Exception $exception) {
                $this->logger->critical("Неизвестная ошибка при попытке создать группу с IP {$address}, 
                сообщение ошибки: {$exception->getMessage()}");
                return $this->buildResponse(false, 500);
            }
        }
    }

    public function groupEdit(Request $request, int $id)
    {
        $address = $request->ip();
        $user = Group::find($id);
        if (is_null($user)) {
            return $this->buildResponse(false, 400, "Can't find group with ID {$id}");
        }
        $validatorResult = $this->validator->validate($user, $request->all());
        if ($validatorResult) {
            return $this->buildResponse(false, 400, $validatorResult);
        } else {
            try {
                $this->crud->update($user, $request);
                return $this->buildResponse(true, 200);
            } catch (\Exception $exception) {
                $this->logger->critical("Неизвестная ошибка при попытке редактировать группу c ID {$id} с IP {$address}, 
                сообщение ошибки: {$exception->getMessage()}");
                return $this->buildResponse(false, 500);
            }
        }
    }

    public function groupList()
    {
        $users = Group::all();
        return $this->buildResponse(true, 200, $users);
    }

    public function groupDelete(Request $request, int $id)
    {
        $address = $request->ip();
        try {
            $group = Group::find($id);
            if (is_null($group)) {
                return $this->buildResponse(false, 400, "Can't find group with ID {$id}");
            }
            $this->crud->delete($group);
            return $this->buildResponse(true, 200);
        } catch (\Exception $exception) {
            $this->logger->critical("Неизвестная ошибка при попытке удалить группу c ID {$id} с IP {$address}, 
                сообщение ошибки: {$exception->getMessage()}");
            return $this->buildResponse(false, 500);
        }
    }

    /**
     * Тут начинается many-to-many Group
     * Тоже можно было вынести логику.
     * Но на данный момент тут не так много кода.
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function groupUsersList(Request $request,int $id)
    {
        $group = Group::find($id);
        if ($group===null){
            return $this->buildResponse(false,400,"Can't find group with ID {$id}");
        }
        return $this->buildResponse(true,200,$group->users);
    }

    public function groupUserExclude(Request $request,int $id)
    {
        $validator = Validator::make($request->all(),
            [
                'user_id'=>'required|numeric'
            ]);
        if ($validator->fails()){
            return $this->buildResponse(false,400,$validator->errors());
        }
        $userId=$request->input('user_id');
        $group = Group::find($id);
        $user = User::find($userId);
        if ($group===null){
            return $this->buildResponse(false,400,"Can't find group with ID {$id}");
        }
        if($user === null)
        {
            return $this->buildResponse(false,400,"Can't find user with ID {$userId}");
        }
        $sqlResult=$group->users()->detach($userId);
        //TODO
        return DB::transaction(function () use ($sqlResult,$userId,$id) {
            if ($sqlResult) {
                return $this->buildResponse(true, 200);
            } else {
                return $this->buildResponse(false, 400, "User ID {$userId} not been joined to group ID {$id}");
            }
        });
    }
}
