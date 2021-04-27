<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiResponse\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProviderRequest;
use App\Http\Resources\ProviderCollection;
use EllipseSynergie\ApiResponse\Laravel\Response;
use App\Http\Controllers\Interfaces\ProviderInterface;
use App\Provider;
use App\ProviderCheckingAccount;
use App\ProviderContactPerson;

class ProviderController extends Controller implements ProviderInterface
{
    protected $response;
    protected $per_page;
    protected $provider;
    protected $apiResponse;
    private $message_not_found;

    public function __construct(Response $response, ApiResponse $apiResponse, Provider $provider)
    {
        $this->middleware('auth:api');
        $this->middleware('permission:providers.create')->only('store');
        $this->middleware('permission:providers.show')->only('show');
        $this->middleware('permission:providers.update')->only('update');
        $this->middleware('permission:providers.delete')->only(['destroy']);

        $this->response = $response;
        $this->provider = $provider;
        $this->apiResponse = $apiResponse;
        $this->per_page = request()->get('per_page' , 1000000);
        $this->message_not_found = trans('messages.not_found',['name' => __('messages.provider')]);
    }

    public function index()
    {
        $providers = $this->provider;
        if ($str = \request('search'))
        {
            $providers = $providers->search($str);
        }

        $providers = $providers->filter();
        $providers = $providers->sort()->paginate($this->per_page);

        return $this->response->withArray([
           'result' => [
               'success' => true,
               'data'    => new ProviderCollection($providers)
           ]
        ]);
    }

    public function inventory(){

        $providers = $this->provider;

        if ($str = \request('search'))
        {
            $providers = $providers->search($str);
        }

        $providers = $providers->sort()->paginate($this->per_page);

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'  => new \App\Http\Resources\Inventory\ProviderCollection($providers)
            ],
        ]);
    }

    public function show(Provider $provider)
    {
        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'    => [
                    'provider'  => new \App\Http\Resources\Provider($provider,true)
                ]
            ]
        ]);
    }

    public function store(ProviderRequest $request)
    {
        $provider = Provider::create($request->all());

        if ($provider_checking_accounts = $request['provider_checking_accounts'])
        {
            if (is_array($provider_checking_accounts))
            {
                foreach ($provider_checking_accounts as $provider_checking_account)
                {
                    ProviderCheckingAccount::create([
                       'provider_id'            => $provider->id,
                       'bank'                   => $provider_checking_account['bank'],
                       'address'                => $provider_checking_account['address'],
                       'correspondent_account'  => $provider_checking_account['correspondent_account'],
                       'checking_account'       => $provider_checking_account['checking_account'],
                    ]);
                }
            }
        }

        if ($provider_contact_persons = $request['provider_contact_persons'])
        {
            if (is_array($provider_contact_persons))
            {
                foreach ($provider_contact_persons as $provider_contact_person)
                {
                    ProviderContactPerson::create([
                       'provider_id'            => $provider->id,
                       'full_name'              => $provider_contact_person['full_name'],
                       'position'               => $provider_contact_person['position'],
                       'phone'                  => $provider_contact_person['phone'],
                       'email'                  => $provider_contact_person['email'],
                       'comment'                => $provider_contact_person['comment'],
                    ]);
                }
            }
        }

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.store_success',['name' => __('messages.provider')]),
                'data'    => [
                    'provider'  => new \App\Http\Resources\Provider($provider,true)
                ]
            ]
        ]);
    }

    public function update(ProviderRequest $request, Provider $provider)
    {
        $provider->update($request->all());

        ProviderCheckingAccount::where('provider_id','=',$provider->id)->delete();
        ProviderContactPerson::where('provider_id','=',$provider->id)->delete();

        if ($provider_checking_accounts = $request['provider_checking_accounts'])
        {
            if (is_array($provider_checking_accounts))
            {
                foreach ($provider_checking_accounts as $provider_checking_account)
                {
                    ProviderCheckingAccount::create([
                       'provider_id'            => $provider->id,
                       'bank'                   => $provider_checking_account['bank'],
                       'address'                => $provider_checking_account['address'],
                       'correspondent_account'  => $provider_checking_account['correspondent_account'],
                       'checking_account'       => $provider_checking_account['checking_account'],
                    ]);
                }
            }
        }

        if ($provider_contact_persons = $request['provider_contact_persons'])
        {
            if (is_array($provider_contact_persons))
            {
                foreach ($provider_contact_persons as $provider_contact_person)
                {
                    ProviderContactPerson::create([
                       'provider_id'            => $provider->id,
                       'full_name'              => $provider_contact_person['full_name'],
                       'position'               => $provider_contact_person['position'],
                       'phone'                  => $provider_contact_person['phone'],
                       'email'                  => $provider_contact_person['email'],
                       'comment'                => $provider_contact_person['comment'],
                    ]);
                }
            }
        }

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.update_success',['name' => __('messages.provider')]),
                'data'    => [
                    'provider'  => new \App\Http\Resources\Provider($provider,true)
                ]
            ]
        ]);
    }

    public function destroy(Provider $provider)
    {
        try {
            $provider->delete();
        } catch (\Exception $e) {

        }

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.destroy_success',['name' => __('messages.provider')]),
                'data'    => [
                    'message' => trans('messages.successfully_deleted')
                ]
            ]
        ]);
    }
}
