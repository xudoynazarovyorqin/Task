<?php


namespace App\Http\Controllers\Traits;


use Illuminate\Support\Facades\Log;

trait ScopeTrait
{
    /**
     * @param $query
     * @return mixed
     */
    public function scopeSort($query){
        return $query->orderBy(request()->get('column', 'id'), request()->get('order', 'asc'));
    }

    /**
     * @param $query
     * @param $string
     * @return mixed
     */
    public function scopeSearch($query, $string){
        $columns = $this->search_columns;

        return $query->where(function ($query) use($string, $columns) {
            foreach ($columns as $column){
                $query->orwhere($column, 'ilike',  '%' . $string .'%');
            }
        });
    }

    public function scopeOrderByIdDesc($query){
        return $query->orderBy('id', 'DESC');
    }

    public function scopeFilter($query){
        return $query;
    }
}
