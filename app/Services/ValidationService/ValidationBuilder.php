<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 28.11.2018
 * Time: 21:33
 */

namespace App\Services\ValidationService;


use App\Services\ModelServices\ModelParser;
use Illuminate\Database\Eloquent\Model;

class ValidationBuilder
{
    /**
     * Строит правила валидации.
     * Дополнительно можно указать $messages для
     * локализации и кастомизации сообщений
     * об ошибках валидации
     */
    use ModelParser;

    /**
     * @var array
     */
    private $rulesCreate=[
        'email' => 'required|email|unique:users,email',
        'last_name' => 'required',
        'first_name' => 'required',
        'state' => 'nullable|boolean',
        'name'=>'required|unique:groups,name'
    ];

    private $rulesEdit =[
        'email' => 'required|email|',
        'last_name' => 'required',
        'first_name' => 'required',
        'state' => 'nullable|boolean',
        'name'=>'required'
    ];

    /**
     * @param Model $model
     * @return array
     */
    public function getRulesCreate(Model $model):array
    {
        $buildedRules=[];
        $columns = $this->getColumns($model);
        foreach ($this->rulesCreate as $input=>$rule)
        {
            if(in_array($input,$columns)){
                $buildedRules[$input]=$rule;
            }
        }
        return $buildedRules;
    }

    public function getRulesEdit(Model $model):array
    {
        $buildedRules=[];
        $columns = $this->getColumns($model);
        foreach ($this->rulesEdit as $input=>$rule)
        {
            if(in_array($input,$columns)){
                $buildedRules[$input]=$rule;
            }
        }
        return $buildedRules;
    }
}