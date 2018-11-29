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

    /**
     * В теории, можно было не выносить это в трейт, а задефайнить в CRUDService,
     * и оттуда передавать в ValidatorServices. Но мне показалось что так лучше.
     * @param Model $model
     * @return array
     */
    protected function getColumns(Model $model):array
    {
        $columns = Schema::getColumnListing($model->getTable());
        unset($columns['id']);
        unset($columns['created_at']);
        unset($columns['updated_at']);
        return $columns;
    }
}