<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\ApiResponse\ApiResponse;
use EllipseSynergie\ApiResponse\Laravel\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Builder;
use App\Sale;
use App\Assembly;
use App\User;
use App\Payment;

class DashboardController extends Controller
{
    /**
     * @var Response
     */
    protected $response;

    /**
     * @var ApiResponse
     */
    protected $apiResponse;

    /**
     * AuthController constructor.
     * @param Response $response
     * @param ApiResponse $apiResponse
     */
    public function __construct(Response $response, ApiResponse $apiResponse)
    {
        $this->middleware('stock_token');
        $this->response = $response;
        $this->apiResponse = $apiResponse;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory
     */
    public function index()
    {
        $access_token = request()->header('AccessToken');
        $user = User::where('access_token',$access_token)->first();
        $user_id = $user->id;        

        $sales = Sale::whereHas('users_with_employee_group', function (Builder $query) use ($user_id) {
            $query->where('user_id', '=', $user_id);
        })->get();

        $assemblies = Assembly::whereHas('users_with_employee_group', function (Builder $query) use ($user_id) {
            $query->where('user_id', '=', $user_id);
        })->get();

        $count_sales = count($sales);
        $count_assemblies = count($assemblies);
        
        return $this->response->withArray(
            [
                'result' => [
                    'success' => true,
                    'data'    => [
                        'count_sales'     => $count_sales,
                        'count_assemblies'  => $count_assemblies,
                    ]
                ]
            ]);
    }
}
