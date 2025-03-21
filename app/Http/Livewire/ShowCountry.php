<?php

namespace App\Http\Livewire;

use App\Models\City;
use App\Models\County;
use GuzzleHttp\Client;
use App\Models\Country;
use Livewire\Component;
use App\Models\Currency;
use App\Models\Store_Settings;
use Illuminate\Support\Facades\Cache;


class ShowCountry extends Component
{
    public $itemId;
    public $edititem = null;
    public $delete = false;
    public $record;
    public $currencies;
    public $fanUrl = 'https://api.fancourier.ro';

    public $url = 'http://api.sameday.ro/';

    public function render()
    {
        return view('livewire.show-country', [
            'country' => $this->country
        ]);
    }
    public function mount($itemId)
    {
        $this->itemId = $itemId;
        $this->currencies = Currency::all();
    }
    public function confirmItemRemoval()
    {
        $this->delete = true;
    }
    public function cancelItemRemoval()
    {
        $this->delete = false;
    }
    public function getCountryQueryProperty()
    {
        return Country::find($this->itemId);
    }
    public function getCountryProperty()
    {
        return $this->countryQuery;
    }
    public function edititem()
    {
        $this->record = [
            'name' => $this->country->name,
            'currency' => $this->country->currency,
            'status' => $this->country->status == 1 ? true : false,
            'iso_code' => $this->country->iso_code,
            'iso_code3' => $this->country->iso_code3,
            'phone_code' => $this->country->phone_code,


        ];
        $this->edititem = true;
    }
    public function cancelitem()
    {
        $this->edititem = null;
        $this->record = [];
    }
    public function saveitem()
    {
        $rec = $this->record ?? NULL;
        if (!is_null($rec)) {
            $new = Country::find($this->itemId);
            if (array_key_exists('name', $rec)) {
                if (!empty($rec['name'])) {
                    $new->name = $rec['name'];
                } else {
                    session()->flash('notification', [
                        'message' => 'Please provide a value!',
                        'type' => 'warning',
                        'title' => 'Missing Values'
                    ]);
                    return;
                }
            }
            if (array_key_exists('currency', $rec)) {
                if (!empty($rec['currency'])) {
                    $new->currency = $rec['currency'];
                } else {
                    session()->flash('notification', [
                        'message' => 'Please provide a value!',
                        'type' => 'warning',
                        'title' => 'Missing Values'
                    ]);
                    return;
                }
            }
            if (array_key_exists('status', $rec)) {
                $new->status = $rec['status'];
            }
            if (array_key_exists('iso_code', $rec)) {
                $new->iso_code = $rec['iso_code'];
            }
            if (array_key_exists('iso_code3', $rec)) {
                $new->iso_code3 = $rec['iso_code3'];
            }
            if (array_key_exists('phone_code', $rec)) {
                $new->phone_code = $rec['phone_code'];
            }
            $new->save();
            Cache::forget('active_countries');

            $this->emit('itemSaved');
            session()->flash('notification', [
                'message' => 'Record edited successfully!',
                'type' => 'success',
                'title' => 'Success'
            ]);
        }
        $this->record = [];
        $this->edititem = null;
    }
    // public function get_token()
    // {
    //     $user = "moldasolineAPI";
    //     $pass = "*YDF5t6m";
    //     $client = new Client();

    //     $response = $client->post($this->url . 'api/authenticate', [
    //         'headers' => [
    //             'X-AUTH-USERNAME' => $user,
    //             'X-AUTH-PASSWORD' => $pass,
    //         ],
    //         'json' => [
    //             'remember_me' => true,
    //         ],
    //         'curl' => [
    //             CURLOPT_SSL_VERIFYPEER => false,
    //         ],
    //     ]);

    //     if ($response->getStatusCode() == 200) {
    //         $responseBody = json_decode($response->getBody(), true);

    //         $token = $responseBody['token'];

    //         $parameter = Store_Settings::where('parameter', 'sam_token')->first();

    //         if ($parameter) {
    //             $parameter->update(['value' => $token]);
    //         }

    //         session()->flash('notification', [
    //             'message' => 'Token generated successfully!',
    //             'type' => 'success',
    //             'title' => 'Success'
    //         ]);

    //         return true;
    //     } else {
    //         session()->flash('notification', [
    //             'message' => 'Token not generated',
    //             'type' => 'warning',
    //             'title' => 'Warning'
    //         ]);

    //         return false;
    //     }
    // }
    public function get_fan_token()
    {
        $user = 'adtanase';
        $pass = 'WCsIC3yVToe2400qufAb';
        $client = new Client();

        $response = $client->post($this->fanUrl . '/login', [
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
            'query' => [
                'username' => $user,
                'password' => $pass,
            ],
            'curl' => [
                CURLOPT_SSL_VERIFYPEER => false,
            ],
        ]);

        if ($response->getStatusCode() == 200) {
            $response = json_decode($response->getBody(), true);
            $token = $response['data']['token'];
            $parameter = Store_Settings::where('parameter', 'fan_token')->first();
            if ($parameter) {
                $parameter->update(['value' => $token]);
            }
            session()->flash('notification', [
                'message' => 'Token generated successfully!',
                'type' => 'success',
                'title' => 'Success'
            ]);
            return true;
        } else {
            session()->flash('notification', [
                'message' => 'Token not generated',
                'type' => 'warning',
                'title' => 'warning'
            ]);
            return;
        }
    }
    public function city()
    {
        set_time_limit(0);

        $counties = County::where('country_id', $this->itemId)->get();

        foreach ($counties as $county) {
            $this->get_cities($county->name);
        }
        session()->flash('notification', [
            'message' => 'City import succesfully!',
            'type' => 'succes',
            'title' => 'Succes'
        ]);
    }

    public function get_cities($county = null)
    {
        $client = new Client();
        $token = Store_Settings::where('parameter', 'fan_token')->value('value');

        if (!$token) {
            $this->get_fan_token();
            return $this->get_cities($county);
        }

        $countyId = County::where('name', $county)->value('id');
        $queryParams = $county ? ['county' => $county] : [];

        try {
            $response = $client->get($this->fanUrl . '/reports/localities', [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $token,
                ],
                'query' => $queryParams,
                'curl' => [
                    CURLOPT_SSL_VERIFYPEER => false,
                ],
                'timeout' => 60,
            ]);

            $responseData = json_decode($response->getBody(), true);

            if (!isset($responseData['status']) || $responseData['status'] !== 'success' || empty($responseData['data'])) {
                session()->flash('notification', [
                    'message' => 'No cities found for ' . $county,
                    'type' => 'warning',
                    'title' => 'Warning'
                ]);
                return;
            }

            foreach ($responseData['data'] as $city) {
                City::updateOrCreate(
                    ['name' => $city['name'], 'county_id' => $countyId],
                    ['status' => true]
                );
            }

            session()->flash('notification', [
                'message' => 'Cities imported successfully for ' . $county,
                'type' => 'success',
                'title' => 'Success'
            ]);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            if (str_contains($e->getMessage(), 'credentials do not match')) {
                $this->get_fan_token(); // Refresh token and retry
                return $this->get_cities($county);
            }
        } catch (\Exception $e) {
            session()->flash('notification', [
                'message' => 'Request failed: ' . $e->getMessage(),
                'type' => 'error',
                'title' => 'Error'
            ]);
        }
    }
}
