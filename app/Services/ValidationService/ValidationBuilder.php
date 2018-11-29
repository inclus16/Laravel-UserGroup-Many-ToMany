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
    private $rules=[
        'email' => 'required|email|unique:users,email',
        'last_name' => 'required',
        'first_name' => 'required',
        'state' => 'nullable|boolean',
        'name'=>'required'
    ];

    /**
     * @param Model $model
     * @return array
     */
    public function getRules(Model $model):array
    {
        $buildedRules=[];
        $columns = $this->getColumns($model);
        foreach ($this->rules as $input=>$rule)
        {
            if(in_array($input,$columns)){
                $buildedRules[$input]=$rule;
            }
        }
        return $buildedRules;
    }
}