<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 28.11.2018
 * Time: 21:29
 */

namespace App\Services\ModelServices;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

trait ModelParser
{

    protected function getColumns(Model $model):array
    {
        $columns = Schema::getColumnListing($model->getTable());
        unset($columns['id']);
        unset($columns['created_at']);
        unset($columns['updated_at']);
        return $columns;
    }
}