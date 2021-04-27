<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiResponse\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\ClientRequest;
use App\Http\Resources\ClientCollection;
use EllipseSynergie\ApiResponse\Laravel\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Interfaces\ClientInterface;
use App\Client;
use App\ClientCheckingAccount;
use App\ClientContactPerson;

class ClientController extends Controller implements ClientInterface
{
    protected $response;

    protected $per_page;

    protected $client;

    protected $apiResponse;

    private $message_not_found;

    public function __construct(Response $response, ApiResponse $apiResponse, Client $client)
    {
        $this->middleware('auth:api');
        $this->middleware('permission:clients.create')->only('store');
        $this->middleware('permission:clients.show')->only('show');
        $this->middleware('permission:clients.update')->only('update');
        $this->middleware('permission:clients.delete')->only(['destroy']);

        $this->response = $response;
        $this->client = $client;
        $this->apiResponse = $apiResponse;
        $this->per_page = request('per_page') ? request('per_page') : 10;
        $this->message_not_found = trans('messages.not_found',['name' => __('messages.client')]);
    }

    public function index()
    {
        $clients = $this->client;
        if ($str = \request('search'))
        {
            $clients = $clients->search($str);
        }

        $clients = $clients->filter()->sort();

        $clients = $clients->select(
            'clients.id as id','name', 'full_name', 'phone', 'email', 'clients.comment as comment',
            'object_name', 'district_id', 'quarter_id', 'object_street', 'object_home', 'object_corps', 'object_flat',
            'clients.created_at as created_at','clients.updated_at as updated_at'
        );

        $clients = $clients->with([
            'district:id,name',
            'quarter:id,name',
        ]);

        $clients = $clients->paginate($this->per_page);

        return $this->response->withArray([
           'result' => [
               'success' => true,
               'data'    => [
                   'clients'       => $clients->items(),
                   'pagination'    => [
                       'total'  => $clients->total(),
                       'current_page'  => $clients->currentPage()
                   ]
               ]
           ]
        ]);
    }

    public function inventory()
    {
        $clients = $this->client;

        if ($str = \request('search'))
        {
            $clients = $clients->search($str);
        }
        if ($str = \request('id'))
        {
            $clients = $clients->searchById($str);
        }

        $clients = $clients->filter();
        $clients = $clients->orderByIdDesc()->paginate($this->per_page);

        return $this->response->withArray([
           'result' => [
               'success' => true,
               'data'  => new \App\Http\Resources\Inventory\ClientCollection($clients)
           ],
        ]);
    }

    public function show(Client $client)
    {
        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'    => [
                    'client'  => new \App\Http\Resources\Client($client,true)
                ]
            ]
        ]);
    }

    public function store(ClientRequest $request)
    {
        $client = Client::create($request->all());

        if ($client_checking_accounts = $request['client_checking_accounts'])
        {
            if (is_array($client_checking_accounts))
            {
                foreach ($client_checking_accounts as $client_checking_account)
                {
                    ClientCheckingAccount::create([
                       'client_id'              => $client->id,
                       'bank'                   => $client_checking_account['bank'],
                       'address'                => $client_checking_account['address'],
                       'correspondent_account'  => $client_checking_account['correspondent_account'],
                       'checking_account'       => $client_checking_account['checking_account'],
                    ]);
                }
            }
        }

        if ($client_contact_persons = $request['client_contact_persons'])
        {
            if (is_array($client_contact_persons))
            {
                foreach ($client_contact_persons as $client_contact_person)
                {
                    ClientContactPerson::create([
                       'client_id'              => $client->id,
                       'full_name'              => $client_contact_person['full_name'],
                       'position'               => $client_contact_person['position'],
                       'phone'                  => $client_contact_person['phone'],
                       'email'                  => $client_contact_person['email'],
                       'comment'                => $client_contact_person['comment'],
                    ]);
                }
            }
        }

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.store_success',['name' => __('messages.client')]),
                'data'    => [
                    'client'  => new \App\Http\Resources\Client($client)
                ]
            ]
        ]);
    }

    public function update(ClientRequest $request, Client $client)
    {
        $client->update($request->all());

        $client->client_checking_accounts()->delete();

        if ($client_checking_accounts = $request['client_checking_accounts'])
        {
            if (is_array($client_checking_accounts))
            {
                foreach ($client_checking_accounts as $client_checking_account)
                {

                    ClientCheckingAccount::create([
                        'client_id'              => $client->id,
                        'bank'                   => $client_checking_account['bank'],
                        'address'                => $client_checking_account['address'],
                        'correspondent_account'  => $client_checking_account['correspondent_account'],
                        'checking_account'       => $client_checking_account['checking_account'],
                    ]);

                }
            }
        }

        $client->client_contact_persons()->delete();

        if ($client_contact_persons = $request['client_contact_persons'])
        {
            if (is_array($client_contact_persons))
            {
                foreach ($client_contact_persons as $client_contact_person)
                {
                    ClientContactPerson::create([
                        'client_id'              => $client->id,
                        'full_name'              => $client_contact_person['full_name'],
                        'position'               => $client_contact_person['position'],
                        'phone'                  => $client_contact_person['phone'],
                        'email'                  => $client_contact_person['email'],
                        'comment'                => $client_contact_person['comment'],
                    ]);
                }
            }
        }

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.update_success',['name' => __('messages.client')]),
                'data'    => [
                    'client'  => new \App\Http\Resources\Client($client)
                ]
            ]
        ]);
    }

    public function destroy(Client $client)
    {
        try {
            $client->delete();
        } catch (\Exception $e) {

        }

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.destroy_success',['name' => __('messages.client')]),
                'data'    => [
                    'message' => trans('messages.successfully_deleted')
                ]
            ]
        ]);
    }

    public function getTypes()
    {
        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'    => [
                    'types'  => Client::getTypes()
                ]
            ]
        ]);
    }

    public function getObjectData($id)
    {
        $client = $this->client->select('id', 'name', 'object_name', 'district_id', 'quarter_id', 'object_street', 'object_home', 'object_corps', 'object_flat')->find($id);

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'    => [
                    'client'  => $client
                ]
            ]
        ]);
    }

}
