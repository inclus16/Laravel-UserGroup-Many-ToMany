<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 28.11.2018
 * Time: 21:37
 */

namespace App\Services\ValidationService;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ValidatorService
{
    private $validationBuilder;

    public function __construct()
    {
        $this->validationBuilder=new ValidationBuilder();
    }

    public function validate(Model $model,array $request)
    {
        if($model->id===null) {
            $rules = $this->validationBuilder->getRulesCreate($model);
        }else{
            $rules=$this->validationBuilder->getRulesEdit($model,$request);
        }
        $validator = Validator::make($request,
            $rules);
        if ($validator->fails()){
            return $validator->errors();
        }else{
            return false;
        }
    }
}