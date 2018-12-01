<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 28.11.2018
 * Time: 21:27
 */

namespace App\Services\ModelServices;


use App\Services\ModelServices\ModelParser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CRUDService
{
    use ModelParser;

    /**
     * Метод выполняет как создание так и редактирование сущностей.
     * Разница лишь в том, что при создании в метод передаётся новая модель,
     * а при редактировании - модель из реквеста.
     * При связях One-To-Many - надо указать необязательный параметр $parent.
     * @param Model $model
     * @param Request $request
     * @throws \Exception
     */

    public function update(Model $model, Request $request):void
    {
        $columns = $this->getColumns($model);
        DB::beginTransaction();
        try {
            foreach ($columns as $column) {
                if(!is_null($request->input($column))) {
                    $model->$column = $request->input($column);
                }
            }
            $model->save();
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    /**
     * В данный момент - можно было не выносить метод сюда а оставить в
     * контроллере. Просто зачастую сущности несколько сложнее, и требуются дополнительные
     * действия при удалении.
     * @param Model $model
     * @throws \Exception
     */
    public function delete(Model $model):void
    {
        DB::beginTransaction();
        $model->delete();
        DB::commit();
    }

}