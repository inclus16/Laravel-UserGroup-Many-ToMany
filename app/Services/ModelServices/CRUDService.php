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

    public function delete(Model $model):void
    {
        $model->delete();
    }

}