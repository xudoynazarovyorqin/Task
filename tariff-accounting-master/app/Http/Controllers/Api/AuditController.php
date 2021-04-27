<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ApiModelNotFoundException;
use App\Http\Controllers\ApiResponse\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\AuditCollection;
use EllipseSynergie\ApiResponse\Laravel\Response;
use App\Audit;

class AuditController extends Controller
{
    protected $response;

    protected $per_page;

    protected $apiResponse;

    private $message_not_found;

    private $audit;

    public function __construct(Response $response, ApiResponse $apiResponse, Audit $audit)
    {
        $this->middleware('auth:api');
        $this->middleware('permission:audits.index')->only('index');

        $this->response = $response;
        $this->audit = $audit;
        $this->apiResponse = $apiResponse;
        $this->per_page = request()->get('per_page',100);
        $this->message_not_found = trans('strings.audit_not_found');
    }

    public function index()
    {
        $audits = $this->audit;

        if ($str = request('search'))
        {
            $audits = $audits->search($str);
        }

        $audits = $audits->filter();
        $audits = $audits->sort()->paginate($this->per_page);

        return $this->response->withArray([
           'result' => [
               'success' => true,
               'data'    => new AuditCollection($audits)
           ]
        ]);
    }

    public function show(Audit $audit)
    {
        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'    => [
                    'audit'  => $audit,
                ]
            ]
        ]);
    }

    public function downloadChangeValues($audit_id)
    {
        if (!$audit = $this->audit->find($audit_id)){
            throw new ApiModelNotFoundException($this->message_not_found);
        }

        // Write File
        $old_values = json_encode($audit->old_values, JSON_PRETTY_PRINT);
        file_put_contents(base_path('public/audits/changes_values.json'), "До:\n");
        file_put_contents(base_path('public/audits/changes_values.json'), stripslashes($old_values), FILE_APPEND);

        $new_values = json_encode($audit->new_values, JSON_PRETTY_PRINT);
        file_put_contents(base_path('public/audits/changes_values.json'), "\n\nПосле:\n", FILE_APPEND);
        file_put_contents(base_path('public/audits/changes_values.json'), stripslashes($new_values), FILE_APPEND);

        $changes = file_get_contents(base_path('public/audits/changes_values.json'));

        file_put_contents(base_path('public/audits/changes_values.json'), "");

        return $changes;
    }

    public function auditList(){

        $audits = Audit::latest('id')->limit(7)->get();

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'    => [
                    'audits'  => new \App\Http\Resources\Relation\AuditCollection($audits),
                ],
            ]
        ]);
    }
}
