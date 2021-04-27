<?php

namespace App\Http\Controllers\Api;

use App\Sale;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ChartController extends Controller
{
    public static function weekly($model, $column, $filter_column = null){

        $last_week = Carbon::now()->subWeek();

        $array = $model::selectRaw($column . ',created_at')
            //->search($filter_column)
            ->whereDate('created_at','>',$last_week)
            ->orderBy('created_at','asc')
            ->get()
            ->groupBy(function ($data){
                return Carbon::parse($data->created_at)->format('Y-m-d');
            });

        $data = [];

        for ($i=0; $i<7; $i++){
            $last_week = Carbon::parse($last_week)->addDay();
            $key = $last_week->format('Y-m-d');
            array_push($data,[
                'label' => __('messages.' . Carbon::parse($key)->format('l')),
                'sum'   => (isset($array[$key])) ? $array[$key]->sum($column) : 0,
                'date'  => $key
            ]);
        }

        return $data;
    }
    public static function monthly($model, $column, $filter_column = null){

        $last_month = Carbon::now()->subMonth();

        $array = $model::selectRaw($column . ',created_at')
            //->search($filter_column)
            ->whereDate('created_at','>',$last_month)
            ->orderBy('created_at','asc')
            ->get()
            ->groupBy(function ($data){
                return Carbon::parse($data->created_at)->format('Y-m-d');
            });

        $data = [];
        for ($i=0; $i<30; $i++){
            $last_month = Carbon::parse($last_month)->addDay();
            $key = $last_month->format('Y-m-d');
            array_push($data,[
                'label' => $last_month->format('m-d'),
                'sum'   => (isset($array[$key])) ? $array[$key]->sum($column) : 0,
                'date'  => $key
            ]);
        }

        return $data;

    }
    public static function yearly($model,$column, $filter_column = null){

        $last_year = Carbon::now()->subYear();
        $array = $model::selectRaw($column . ',created_at')
            //->search($filter_column)
            ->whereDate('created_at','>',$last_year)
            ->orderBy('created_at','asc')
            ->get()
            ->groupBy(function ($data){
                return Carbon::parse($data->created_at)->format('Y-m');
            });

        $data = [];

        for ($i=0; $i<12; $i++){
            $last_year = Carbon::parse($last_year)->addMonth();
            $key = $last_year->format('Y-m');
            array_push($data,[
                'label' => __('messages.' . Carbon::parse($key)->format('F')),
                'sum'   => (isset($array[$key])) ? $array[$key]->sum($column) : 0,
                'date'  => $key
            ]);
        }
        return $data;
    }

    public static function period($model, $from_date = null, $to_date = null, $column, $filter_column = null){
        if( $from_date == null) {
            $from_date = Carbon::now()->toDateString();
        }
        else {
            $from_date = date("Y-m-d", strtotime($from_date));
        }

        if( $to_date == null) {
            $to_date = Carbon::now()->toDateString();
        }
        else {
            $to_date = date("Y-m-d", strtotime($to_date));
        }

        $count_days = Carbon::parse($from_date)->diffInDays(Carbon::parse($to_date)) + 1;

        $array = $model::selectRaw($column . ',created_at')
            //->search($filter_column)
            ->whereDate('created_at','>=',$from_date)
            ->whereDate('created_at','<=',$to_date)
            ->orderBy('created_at','asc')
            ->get()
            ->groupBy(function ($data){
                return Carbon::parse($data->created_at)->format('Y-m-d');
            });

        $data = [];
        for ($i = 0; $i < $count_days; $i++){
            $from_date = Carbon::parse($from_date);
            $key = $from_date->format('Y-m-d');
            array_push($data,[
                'label' => $from_date->format('m-d'),
                'sum'   => (isset($array[$key])) ? $array[$key]->sum($column) : 0,
                'date'  => $key
            ]);
            $from_date = Carbon::parse($from_date)->addDay();
        }

        return $data;

    }

    public static function weekTotalSum($model, $column, $filter_column = null){

        $data = [];
        $data['before_total'] = 0;
        $data['before_count'] = 0;
        $data['last_total'] = 0;
        $data['last_count'] = 0;
        $last_week = Carbon::now()->subWeek()->startOfWeek();

       $modLast = $model::selectRaw($column . ',created_at')
            //->search($filter_column)
            ->whereDate('created_at','>=',$last_week)
            ->orderBy('created_at','asc');

        $data['last_total'] = $modLast->sum($column);
        $data['last_count'] = $modLast->count();

        $before_week = Carbon::now()->startOfWeek();

       $modBefore = $model::selectRaw($column . ',created_at')
            //->search($filter_column)
            ->whereDate('created_at','>=',$before_week)
            ->whereDate('created_at','<',$last_week)
            ->orderBy('created_at','asc');

        $data['before_total'] = $modBefore->sum($column);
        $data['before_count'] = $modBefore->count();

        $last_week = Carbon::now();
        $key = $last_week->format('Y-m-d');
        $data['label_week'] =__('messages.' . Carbon::parse($key)->format('l'));
        $data['label_month'] = __('messages.' . Carbon::parse($key)->format('F'));

        return $data;
    }
     public static function dayTotalSum($model, $column, $filter_column = null){

        $data = [];
        $data['before_total'] = 0;
        $data['before_count'] = 0;
        $data['last_total'] = 0;
        $data['last_count'] = 0;
        $before_day = Carbon::now()->subDay();
        $last_day = $before_day->addDay();

       $modBefore = $model::selectRaw($column . ',created_at')
            //->search($filter_column)
            ->whereDate('created_at','>=',$before_day)
            ->whereDate('created_at','<',$last_day)
             ->orderBy('created_at','asc');

        $data['before_total'] = $modBefore->sum($column);
        $data['before_count'] = $modBefore->count();

       $modLast = $model::selectRaw($column . ',created_at')
            //->search($filter_column)
            ->whereDate('created_at','>=',$last_day)
             ->orderBy('created_at','asc');

        $data['last_total'] = $modLast->sum($column);
        $data['last_count'] = $modLast->count();
        $last_year = Carbon::now();
        $key = $last_year->format('Y-m');
        $data['label_month'] = __('messages.' . Carbon::parse($key)->format('F'));
        $data['label_day'] = Carbon::now()->day;

        return $data;
    }
    public static function monthTotalSum($model, $column, $filter_column = null){

        $data = [];
        $data['before_total'] = 0;
        $data['before_count'] = 0;
        $data['last_total'] = 0;
        $data['last_count'] = 0;
        $last_month = Carbon::now()->startOfMonth();

        $modLast = $model::selectRaw($column . ',created_at')
            //->search($filter_column)
            ->whereDate('created_at','>=',$last_month)
            ->orderBy('created_at','asc');

        $data['last_total'] = $modLast->sum($column);
        $data['last_count'] = $modLast->count();

        $before_month = Carbon::now()->subMonth()->startOfMonth();

       $modBefore = $model::selectRaw($column . ',created_at')
            //->search($filter_column)
            ->whereDate('created_at','>=',$before_month)
            ->whereDate('created_at','<',$last_month)
            ->orderBy('created_at','asc');

        $data['before_total'] = $modBefore->sum($column);
        $data['before_count'] = $modBefore->count();

        $last_month = Carbon::now();
        $key = $last_month->format('Y-m-d');
        $data['label_week'] =__('messages.' . Carbon::parse($key)->format('l'));
        $data['label_month'] = __('messages.' . Carbon::parse($key)->format('F'));

        return $data;
    }
    public static function yearTotalSum($model, $column, $filter_column = null){

        $data = [];
        $data['before_total'] = 0;
        $data['before_count'] = 0;
        $data['last_total'] = 0;
        $data['last_count'] = 0;
        $last_month = Carbon::now()->startOfYear();

        $modLast = $model::selectRaw($column . ',created_at')
            //->search($filter_column)
            ->whereDate('created_at','>=',$last_month)
            ->orderBy('created_at','asc');

        $data['last_total'] = $modLast->sum($column);
        $data['last_count'] = $modLast->count();

        $before_month = Carbon::now()->subYear()->startOfYear();

       $modBefore = $model::selectRaw($column . ',created_at')
            //->search($filter_column)
            ->whereDate('created_at','>',$before_month)
            ->whereDate('created_at','<=',$last_month)
            ->orderBy('created_at','asc');

        $data['before_total'] = $modBefore->sum($column);
        $data['before_count'] = $modBefore->count();

        $last_month = Carbon::now();
        $key = $last_month->format('Y-m-d');
        $data['label_week'] =__('messages.' . Carbon::parse($key)->format('l'));
        $data['label_month'] = __('messages.' . Carbon::parse($key)->format('F'));

        return $data;
    }
}
