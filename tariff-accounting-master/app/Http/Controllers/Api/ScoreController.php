<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiResponse\ApiResponse;
use App\Http\Requests\ScoreRequest;
use App\Http\Resources\Inventory\ScoreCollection;
use App\Score;
use EllipseSynergie\ApiResponse\Laravel\Response;
use App\Http\Controllers\Controller;

class ScoreController extends Controller
{
    protected $response;

    protected $score;

    protected $per_page;

    protected $apiResponse;

    private $message_not_found;

    private $model;

    public function __construct(Response $response, ApiResponse $apiResponse,Score $score)
    {
        $this->middleware('auth:api');
        $this->middleware('permission:scores.create')->only('store');
        $this->middleware('permission:scores.show')->only('show');
        $this->middleware('permission:scores.update')->only('update');
        $this->middleware('permission:scores.delete')->only(['destroy']);

        $this->response = $response;
        $this->apiResponse = $apiResponse;
        $this->score = $score;
        $this->per_page = request()->get('per_page',1000000);
        $this->message_not_found = trans('messages.not_found',['name' => __('message.score')]);
        $this->model = $score;
    }

    public function index()
    {
        $scores = $this->score;

        if ($str = \request('search'))
        {
            $scores = $scores->search($str);
        }

        $scores = $scores->sort()->paginate($this->per_page);

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'    => new \App\Http\Resources\ScoreCollection($scores)
            ]
        ]);
    }

    public function inventory()
    {
        $scores = $this->score;

        if ($str = \request('search'))
        {
            $scores = $scores->search($str);
        }

        $scores = $scores->sort()->get();

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'  =>  new ScoreCollection($scores)
            ],
        ]);
    }

    public function store(ScoreRequest $request)
    {
        $score = Score::create($request->all());

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.store_success',['name' => __('messages.Score')]),
                'data'    => [
                    'score'  => new \App\Http\Resources\Score($score)
                ],
            ]
        ]);
    }

    public function show(Score $score)
    {
        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'    => [
                    'score'  => new \App\Http\Resources\Score($score)
                ]
            ]
        ]);
    }

    public function update(ScoreRequest $request, Score $score)
    {
        $score->update($request->all());

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.update_success',['name' => __('messages.Score')]),
                'data'    => [
                    'score'  => new \App\Http\Resources\Score($score)
                ],
            ]
        ]);
    }

    public function destroy(Score $score)
    {
        try {
            $score->delete();
        } catch (\Exception $e) {
        }

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => trans('messages.destroy_success',['name' => trans('messages.score')]),
            ]
        ]);

    }
}
