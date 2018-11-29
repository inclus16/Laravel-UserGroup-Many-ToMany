<?php

namespace App\Http\Controllers;

use App\Group;
use App\Services\ModelServices\CRUDService;
use App\Services\LoggerBuilder;
use App\Services\ResponseBuilder;
use App\Services\ValidationService\ValidatorService;
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

    public function groupCreate(Request $request)
    {
        $address = $request->ip();
        $group = new Group();
        $validatorResult = $this->validator->validate($group, $request);
        if ($validatorResult) {
            return $this->buildResponse(false, 400, $validatorResult);
        } else {
            try {
                $this->crud->update($group, $request);
                return $this->buildResponse(true, 200);
            } catch (\Exception $exception) {
                $this->logger->critical("Неизвестная ошибка при попытке создать группу с IP {$address}, 
                сообщение ошибки: {$exception->getMessage()}");
                DB::rollBack();
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
        $validatorResult = $this->validator->validate($user, $request);
        if ($validatorResult) {
            return $this->buildResponse(false, 400, $validatorResult);
        } else {
            try {
                $this->crud->update($user, $request);
                return $this->buildResponse(true, 200);
            } catch (\Exception $exception) {
                $this->logger->critical("Неизвестная ошибка при попытке редактировать группу c ID {$id} с IP {$address}, 
                сообщение ошибки: {$exception->getMessage()}");
                DB::rollBack();
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
        $group = Group::find($id);
        if ($group===null){
            return $this->buildResponse(false,400,"Can't find group with ID {$id}");
        }
        $group->users()->detach($id);
        return $this->buildResponse(true,200);
    }
}
