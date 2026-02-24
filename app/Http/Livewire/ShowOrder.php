<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use App\Models\Awbs;
use App\Models\Order;
use App\Models\Status;
use GuzzleHttp\Client;
use App\Models\Invoice;
use Livewire\Component;
use Illuminate\Support\Str;
use App\Models\Store_Settings;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;



class ShowOrder extends Component
{
  public $orderId;
  public $record = [];
  public $edititem = null;
  public bool $delete = false;
  public bool $sameday = false;
  public bool $fancourier = false;
  public $services = [];
  public $addresses = [];
  public $persons = [];
  public $statuses;
  public $person;
  public $service;
  public $pickup_point;
  public $invoice_sdatabase;
  public $storno_sdatabase;
  public $circle;
  public $fanUrl = 'https://api.fancourier.ro';
  public $samUrl = 'api.sameday.ro/';
  public $needupdatetokens = false;
  public $samedaymessage = null;
  public $fancouriermessage = null;
  public array $parcel = [
    'weight' => 0.1,
    'length' => 15,
    'width' => 10,
    'height' => 3,
  ];
  public array $recipe = [
    'name' => null,
    'phoneNumber' => null,
    'email' => null,
    'countyString' => null,
    'cityString' => null,
    'address' => null,
    'postalCode' => null,
    'companyName' => null,
    'companyOnrcNumber' => null,
    'companyIban' => null,
    'companyBank' => null,
    'companyCui' => null,
  ];

  protected $listeners = [
    'refreshComponent' => '$refresh'
  ];

  public function updatedFancourier($value)
  {
    if ($value) {
      $this->recipe = [
        'name' => $this->order->account->name,
        'phoneNumber' => $this->order->account->phone,
        'countyString' => $this->order->shipping->county,
        'cityString' => $this->order->shipping->city,
        'address' => $this->order->shipping->address1,
        'postalCode' => $this->order->shipping->zipcode,
        'email' => $this->order->account->email,
      ];
    }
  }

  public function updatedSameday($value)
  {
    if ($value) {
      $this->services = app()->has('global_sam_services') ? json_decode(app('global_sam_services'), true) : null;
      $this->addresses = app()->has('global_sam_addreses') ? json_decode(app('global_sam_addreses'), true) : null;

$this->service = $this->services[0]['id'] ?? null;

$this->pickup_point = !empty($this->addresses) ? end($this->addresses)['id'] : null;


$selectedAddress = collect($this->addresses)->firstWhere('id', $this->pickup_point);
$this->persons = $selectedAddress['contact_persons'] ?? [];
$this->person = !empty($this->persons) ? end($this->persons)['id'] : null;


      if (
        is_null($this->person) ||
        is_null($this->service) ||
        is_null($this->pickup_point) ||
        is_null($this->addresses) ||
        is_null($this->services)
      ) {
        $this->needupdatetokens = true;

        session()->flash('notification', [
          'message' => 'Please generate Tokens first!',
          'type' => 'danger',
          'title' => 'Error'
        ]);
        return;
      }
      $this->recipe = [
        'name' => $this->order->account->name,
        'phoneNumber' => $this->order->account->phone,
        'countyString' => $this->order->shipping->county,
        'cityString' => $this->order->shipping->city,
        'address' => $this->order->shipping->address1,
        'postalCode' => $this->order->shipping->zipcode,
        'email' => $this->order->account->email,
        'companyName' => $this->order->account->type != 'individual' ? $this->order->account->company_name : null,
        'companyOnrcNumber' => $this->order->account->type != 'individual' ? $this->order->account->registration_number : null,
        'companyIban' => $this->order->account->type != 'individual' ? $this->order->account->account : null,
        'companyBank' => $this->order->account->type != 'individual' ? $this->order->account->bank_name : null,
        'companyCui' => $this->order->account->type != 'individual' ? $this->order->account->registration_code : null,
      ];
    }
  }

  public function updatedPickupPoint($value)
  {
    $this->pickup_point = $value;
    $selectedAddress = collect($this->addresses)->firstWhere('id', $value);
    $this->persons = $selectedAddress['contact_persons'] ?? null;
    $this->person = $this->persons[0]['id'] ?? null;
  }

  public function generate_awb_fancourier()
  {
    $client = new Client();
    $client_id = Store_Settings::where('parameter', 'fan_client_id')->value('value');
    $token = Store_Settings::where('parameter', 'fan_token')->value('value');

    // Prepare the AWB data
    $awbData = [
      'clientId' => $client_id,
      'shipments' => [
        [
          'info' => [
            'service' => $this->order->payment->name === 'cash' ? 'Cont Colector' : 'Standard',
            'bank' => '',
            'bankAccount' => '',
            'packages' => [
              'parcel' => 1,
              'envelope' => 0,
            ],

            'weight' => $this->parcel['weight'],
            'cod' => $this->order->payment->name === 'cash' ? $this->order->final_amount : 0,
            'payment' => 'expeditor',
            'refund' => null,
            'returnPayment' => null,
            'observation' => 'POS',
            'content' => 'Comanda semintetop.ro',
            'dimensions' => [
              'length' => $this->parcel['length'],
              'height' => $this->parcel['height'],
              'width' => $this->parcel['width'],
            ],
          ],
          'recipient' => [
            'name' => $this->recipe['name'],
            'phone' => $this->recipe['phoneNumber'],
            'email' => $this->recipe['email'],
            'address' => [
              'county' => $this->recipe['countyString'],
              'locality' => $this->recipe['cityString'],
              'street' => $this->recipe['address'],
              'zipCode' => $this->recipe['postalCode'],
            ],
          ],
        ],
      ],
    ];
    try {
      // Make the API request
      $response = $client->post($this->fanUrl . '/intern-awb', [
        'headers' => [
          'Accept' => 'application/json',
          'Content-Type' => 'application/json',
          'Authorization' => 'Bearer ' . $token,
        ],
        'json' => $awbData,
        'curl' => [
          CURLOPT_SSL_VERIFYPEER => false,
        ],
      ]);
      $responseData = json_decode($response->getBody(), true);
      if ($responseData['response'][0]['errors'] == null) {
        $awbNumber = $responseData['response'][0]['awbNumber'];

        $pdfResponse = $client->get($this->fanUrl . '/awb/label', [
          'headers' => [
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/pdf',
          ],
          'query' => [
            'clientId' => $client_id,
            'awbs[]' => $awbNumber,
            'pdf' => 1,
          ],
          'curl' => [
            CURLOPT_SSL_VERIFYPEER => false,
          ],
        ]);

        $pdfContent = $pdfResponse->getBody()->getContents();
        $dir = public_path('documents');
        if (!file_exists($dir)) {
          mkdir($dir, 0777, true);
        }
        $pdfFilePath = $dir . '/awbfancourier_' . $this->order->order_number . '.pdf';

        file_put_contents($pdfFilePath, $pdfContent);
        $path = 'documents/awbfancourier_' . $this->order->order_number . '.pdf';
        Awbs::create([
          'order_id' => $this->order->id,
          'date' => now(config('app.timezone')),
          'type' => 'fancourier',
          'path' => $path
        ]);
        session()->flash('notification', [
          'message' => 'AWB generated successfully!',
          'type' => 'success',
          'title' => 'Success'
        ]);
        $this->fancourier = false;
        return;
      } else {
        $errors = $responseData['response'][0]['errors'];

        if (!empty($errors)) {
          $errorMessages = collect($errors)
            ->map(function ($messages, $field) {
              return implode(', ', $messages);
            })
            ->implode(' | ');

          session()->flash('notification', [
            'message' => $errorMessages,
            'type' => 'warning',
            'title' => 'Warning'
          ]);
          $this->fancouriermessage = $errorMessages;
          return;
        }
      }
    } catch (\Exception $e) {
      $errorMessage = $e->getMessage();
      if (str_contains($errorMessage, 'credentials') || str_contains($errorMessage, 'token expired')) {
        $newToken = $this->get_fan_token();
        return $this->generate_awb_fancourier();
      }

      session()->flash('notification', [
        'message' => 'AWB generation failed!',
        'type' => 'error',
        'title' => 'Error'
      ]);
    }
  }

  public function get_fan_token()
  {
    $user = 'adtanase';
    $pass = 'aPR&*(WU08Wf5STx';
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
      return  $response['data']['token'];
    } else {
      session()->flash('notification', [
        'message' => 'Token not generated',
        'type' => 'warning',
        'title' => 'warning'
      ]);
      return;
    }
  }

  public function get_services_sameday()
  {
    $token = Store_Settings::where('parameter', 'sam_token')->value('value');
    $client = new Client();
    try {
      $response = $client->get($this->samUrl . 'api/client/services', [
        'headers' => [
          'Accept' => 'application/json',
          'Content-Type' => 'application/json',
          'X-AUTH-TOKEN' => $token,
        ],
        'curl' => [
          CURLOPT_SSL_VERIFYPEER => false,
        ],
      ]);

      $response = json_decode($response->getBody(), true);

      $services = $response['data'];

      $formattedArray = collect($services)
        ->map(fn($service) => [
          'id' => $service['id'],
          'name' => $service['name'],
        ])
        ->toArray();

      $setting = Store_Settings::where('parameter', 'sam_services')->first();
      if ($setting) {
        $setting->update(['value' => $formattedArray]);
        Cache::forget('global_variables');
      } else {
        session()->flash('notification', [
          'message' => 'No sam_services parameter found!',
          'type' => 'error',
          'title' => 'Error'
        ]);
      }
      return;
    } catch (\Exception $e) {
      $errorMessage = $e->getMessage();

      if (str_contains($errorMessage, 'credentials do not match') || str_contains($errorMessage, 'token expired')) {
        $newToken = $this->get_sameday_token();
        return $this->get_services_sameday();
      }

      session()->flash('notification', [
        'message' => 'Something went wrong, please contact the support!',
        'type' => 'error',
        'title' => 'Error'
      ]);
    }
    return;
  }

  public function get_address_sameday()
  {
    $token = Store_Settings::where('parameter', 'sam_token')->value('value');
    $client = new Client();

    try {
      $response = $client->get($this->samUrl . 'api/client/pickup-points', [
        'headers' => [
          'Accept' => 'application/json',
          'Content-Type' => 'application/json',
          'X-AUTH-TOKEN' => $token,
        ],
        'curl' => [
          CURLOPT_SSL_VERIFYPEER => false,
        ],
      ]);

      $response = json_decode($response->getBody(), true);

      $pickup_points = $response['data'];

      $formattedAddresses = collect($pickup_points)->map(function ($pickup) {
        return [
          'id' => $pickup['id'] ?? null,
          'alias' => $pickup['alias'] ?? null,
          'address' => $pickup['address'] ?? null,
          'contact_persons' => collect($pickup['pickupPointContactPerson'] ?? [])
            ->map(fn($person) => [
              'id' => $person['id'],
              'name' => $person['name']
            ])->toArray()
        ];
      })->toArray();

      $setting = Store_Settings::where('parameter', 'sam_addreses')->first();

      if ($setting) {
        $setting->update(['value' => json_encode($formattedAddresses)]);
        Cache::forget('global_variables');
      } else {
        session()->flash('notification', [
          'message' => 'No sam_addreses parameter found!',
          'type' => 'error',
          'title' => 'Error'
        ]);
      }

      return;
    } catch (\Exception $e) {
      $errorMessage = $e->getMessage();

      if (str_contains($errorMessage, 'credentials do not match') || str_contains($errorMessage, 'token expired')) {
        $newToken = $this->get_sameday_token();
        return $this->get_address_sameday();
      }

      session()->flash('notification', [
        'message' => 'Something went wrong, please contact the support!',
        'type' => 'error',
        'title' => 'Error'
      ]);
    }

    return;
  }

  public function generate_awb_sameday()
  {
    $token = Store_Settings::where('parameter', 'sam_token')->value('value');
    $client = new \GuzzleHttp\Client();

    // Asigură-te că URL-ul are slash la final sau adaugă-l
    $apiUrl = rtrim($this->samUrl, '/') . '/api/awb';

    // Determină ID-ul taxei PDO în funcție de serviciu
    // Conform fișierului tău paste.txt (Feb 2026)
    $serviceId = (int) $this->service;
    $pdoTaxId = 566231; // Default pentru 24H (7) - Colet

    if (in_array($serviceId, [15, 24, 57])) {
       // Pentru Locker NextDay (15), Locker Retur (24), Pudo Nextday (57)
       $pdoTaxId = 566300;
    } elseif ($serviceId == 22) {
       // Colet la schimb (22)
       $pdoTaxId = 566267;
    }
    // ... poți adăuga altele dacă e cazul

    $shippingAddress = $this->order->shipping;
    $awbData = [
      'pickupPoint' => (int) $this->pickup_point,
      'contactPerson' => (int) $this->person,
      'service' => (int) $this->service,
      'packageType' => 0,
      'packageNumber' => 1,
      'packageWeight' => $this->parcel['weight'],
      'insuredValue' => 0,
      'cashOnDelivery' => $this->order->payment->name === 'cash' ? $this->order->final_amount : 0,
      'awbPayment' => 1,
      'thirdPartyPickup' => 0,
      'notifyRecipient' => true,
      
      // ✅ ID-ul corect selectat
      'serviceTaxes' => [$pdoTaxId],
      
      'awbRecipient' => array_merge([
        'name' => $this->recipe['name'],
        'phoneNumber' => $this->recipe['phoneNumber'],
        'email' => $this->recipe['email'],
        'personType' => $this->order->account->type === 'individual' ? 0 : 1,
        'countyString' => $this->recipe['countyString'],
        'cityString' => $this->recipe['cityString'],
        'address' => $this->recipe['address'],
        'postalCode' => $this->recipe['postalCode'],
      ], $this->order->account->type !== 'individual' ? [
        'companyName' => $this->recipe['companyName'],
        'companyOnrcNumber' => $this->recipe['companyOnrcNumber'],
        'companyIban' => $this->recipe['companyIban'],
        'companyBank' => $this->recipe['companyBank'],
        'companyCui' => $this->recipe['companyCui'],
      ] : []),

      'parcels' => [
        [
          'weight' => $this->parcel['weight'],
          'length' =>  $this->parcel['length'],
          'width' =>  $this->parcel['width'],
          'height' =>  $this->parcel['height'],
        ],
      ],
    ];

    try {
      // ✅ Folosim URL-ul corectat
      $response = $client->post($apiUrl, [
        'headers' => [
          'Accept' => 'application/json',
          'Content-Type' => 'application/x-www-form-urlencoded',
          'X-AUTH-TOKEN' => $token,
        ],
        'form_params' => $awbData,
        'curl' => [
          CURLOPT_SSL_VERIFYPEER => false,
        ],
      ]);

      $responseData = json_decode($response->getBody(), true);

      if (!empty($responseData['pdfLink'])) {
        $pdfResponse = $client->get($responseData['pdfLink'], [
          'headers' => [
            'X-AUTH-TOKEN' => $token,
          ],
          'curl' => [
            CURLOPT_SSL_VERIFYPEER => false,
          ],
        ]);

        $pdfContent = $pdfResponse->getBody()->getContents();

        $dir = public_path('documents');
        if (!file_exists($dir)) {
          mkdir($dir, 0777, true);
        }

        $awbNumber = $responseData['awbNumber'] ?? $this->order->order_number;
        $date = now(config('app.timezone'))->format('d-m-Y');
        $prefix = app()->has('label_xml_filename') ? app('label_xml_filename') : 'F_41903669';

        $fileName = $prefix . '_' . 
                    $this->order->invoice_series . '_' . 
                    $this->order->external_invoice_number . '_' . 
                    $date . '_' . $awbNumber . '.pdf';

        $pdfFilePath = $dir . '/' . $fileName;
        file_put_contents($pdfFilePath, $pdfContent);

        $path = 'documents/' . $fileName;

        Awbs::create([
          'order_id' => $this->order->id,
          'date' => now(config('app.timezone')),
          'type' => 'sameday',
          'path' => $path
        ]);

        $this->recipe = [
          'name' => null,
          'phoneNumber' => null,
          'email' => null,
          'countyString' => null,
          'cityString' => null,
          'address' => null,
          'postalCode' => null,
          'companyName' => null,
          'companyOnrcNumber' => null,
          'companyIban' => null,
          'companyBank' => null,
          'companyCui' => null,
        ];

        session()->flash('notification', [
          'message' => 'AWB generated and saved successfully!',
          'type' => 'success',
          'title' => 'Success'
        ]);
      } else {
        throw new \Exception('AWB generated but PDF link not found.');
      }
      $this->sameday = false;
      $this->recipe = [
        'name' => null,
        'phoneNumber' => null,
        'email' => null,
        'countyString' => null,
        'cityString' => null,
        'address' => null,
        'postalCode' => null,
        'companyName' => null,
        'companyOnrcNumber' => null,
        'companyIban' => null,
        'companyBank' => null,
        'companyCui' => null,
      ];
      session()->flash('notification', [
        'message' => 'AWB generated successfully!',
        'type' => 'success',
        'title' => 'Success'
      ]);
      return;
    } catch (\GuzzleHttp\Exception\RequestException $e) {
      Log::error('Sameday AWB generation failed: ' . $e->getMessage());

      if ($e->hasResponse()) {
        $errorBody = json_decode($e->getResponse()->getBody(), true);
        
        // Loghează eroarea completă ca să fii sigur
        Log::error('Sameday API Error Body: ', (array)$errorBody);

        if (
          isset($errorBody['error']['message']) &&
          $errorBody['error']['message'] === 'Invalid credentials.'
        ) {
          $token = $this->get_sameday_token();
          return $this->generate_awb_sameday();
        }
        
        // Verifică structura erorilor (uneori e 'errors', alteori 'error')
        $errorsList = $errorBody['errors']['children'] ?? [];
        $allErrors = $this->extractErrors($errorsList);
        
        $msg = $allErrors[0] ?? json_encode($errorBody); // Fallback message

        session()->flash('notification', [
          'message' => "AWB creation failed with the following:\n" . $msg,
          'type' => 'error',
          'title' => 'Validation Error',
        ]);
        $this->samedaymessage = $msg;
      }
      return;
    }
  }


  public function extractErrors(array $errors, string $prefix = ''): array
  {
    $flattened = [];

    foreach ($errors as $key => $value) {
      $path = $prefix === '' ? $key : "$prefix.$key";

      if (isset($value['errors']) && is_array($value['errors'])) {
        foreach ($value['errors'] as $msg) {
          $flattened[] = "$path - $msg";
        }
      }

      if (isset($value['children']) && is_array($value['children'])) {
        $flattened = array_merge($flattened, $this->extractErrors($value['children'], $path));
      }
    }

    return $flattened;
  }

  public function generate_tokens()
  {
    $this->get_fan_token();
    $this->get_sameday_token();
    $this->needupdatetokens = false;

    session()->flash('notification', [
      'message' => 'Tokens generated successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }

  public function get_sameday_token()
  {
    $user = 'moldasolineAPI';
    $pass = '*YDF5t6m';
    $url = 'api.sameday.ro/';
    $client = new Client();
    $response = $client->post($url . 'api/authenticate', [
      'headers' => [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
        'X-AUTH-USERNAME' => $user,
        'X-AUTH-PASSWORD' => $pass,
      ],
      'curl' => [
        CURLOPT_SSL_VERIFYPEER => false,
      ],
    ]);
    if ($response->getStatusCode() == 200) {
      $response = json_decode($response->getBody(), true);
      $token = $response['token'];
      $parameter = Store_Settings::where('parameter', 'sam_token')->first();
      if ($parameter) {
        $parameter->update([
          'value' => $token,
          'updated_at' => now(config('app.timezone'))
        ]);
      }
      $services = $this->get_services_sameday();
      $addresses = $this->get_address_sameday();
      session()->flash('notification', [
        'message' => 'Token generated successfully!',
        'type' => 'success',
        'title' => 'Success'
      ]);
      return;
    } else {
      session()->flash('notification', [
        'message' => 'Token not generated',
        'type' => 'warning',
        'title' => 'warning'
      ]);
      return;
    }
  }

public function instant_invoice()
  {
    $number = $this->generate_invoice_number(true);
    
    if (empty($this->order->invoice_date)) {
        $this->order->invoice_date = Carbon::now(config('app.timezone'))->format('Y-m-d');
        $this->order->save();
    }
    
    $this->generate_invoice();
  }

public function instant_storno()
  {
    $number = $this->generate_storno_number(true);

    if (empty($this->order->storno_date)) {
        $this->order->storno_date = Carbon::now(config('app.timezone'))->format('Y-m-d');
        $this->order->save();
    }

    $this->generate_storno();
  }

public function generate_invoice_number($auto = false)
  {
    if (!empty($this->order->invoice_series) && !empty($this->order->external_invoice_number)) {
        return; 
    }

    $existingInvoice = DB::connection('mysql_invoice')
      ->table('invoices')
      ->where('order_number', $this->order->order_number)
      ->where('type', 'invoice')
      ->first();

    if ($existingInvoice) {
      $this->order->external_invoice_number = str_pad($existingInvoice->number, 4, '0', STR_PAD_LEFT);
      $this->order->invoice_series = $existingInvoice->series;
      $this->order->save();

      if (!$auto) {
        session()->flash('notification', [
          'message' => 'Existing Invoice number retrieved successfully!',
          'type' => 'success',
          'title' => 'Success'
        ]);
      }
    } else {
      $currentSeries = app('global_invoice_series');

      $latestInvoice = DB::connection('mysql_invoice')
        ->table('invoices')
        ->where('series', $currentSeries)
        ->orderByDesc('number')
        ->first();

      if ($latestInvoice) {
        $newNumber = $latestInvoice->number + 1;
      } else {
        $newNumber = 1;
      }

      DB::connection('mysql_invoice')->table('invoices')->insert([
        'number' => $newNumber,
        'order_number' => $this->order->order_number,
        'series' => $currentSeries,
        'type' => 'invoice'
      ]);

      $this->order->invoice_series = $currentSeries;
      $this->order->external_invoice_number = str_pad($newNumber, 4, '0', STR_PAD_LEFT);
      $this->order->save();

      if (!$auto) {
        session()->flash('notification', [
          'message' => 'New Invoice number generated successfully!',
          'type' => 'success',
          'title' => 'Success'
        ]);
      }
    }
  }

  public function generate_storno_number($auto = false)
  {
      if (!empty($this->order->invoice_series) && !empty($this->order->external_storno_number)) {
          return;
      }

      $existingStorno = DB::connection('mysql_invoice')
          ->table('invoices')
          ->where('order_number', $this->order->order_number)
          ->where('type', 'storno')
          ->first();

      if ($existingStorno) {
          $this->order->external_storno_number = str_pad($existingStorno->number, 4, '0', STR_PAD_LEFT);
          $this->order->invoice_series = $existingStorno->series; 
          $this->order->save();

          session()->flash('notification', [
              'message' => 'Existing Storno number retrieved successfully!',
              'type' => 'success',
              'title' => 'Success'
          ]);
      } else {
          $currentSeries = app('global_invoice_series');

          $latestInvoice = DB::connection('mysql_invoice')
              ->table('invoices')
              ->where('series', $currentSeries)
              ->orderByDesc('number')
              ->first();

          $newNumber = $latestInvoice ? $latestInvoice->number + 1 : 1;

          DB::connection('mysql_invoice')->table('invoices')->insert([
              'number' => $newNumber,
              'order_number' => $this->order->order_number,
              'series' => $currentSeries,
              'type' => 'storno'
          ]);

          $this->order->invoice_series = $currentSeries;
          $this->order->external_storno_number = str_pad($newNumber, 4, '0', STR_PAD_LEFT);
          $this->order->save();

          session()->flash('notification', [
              'message' => 'New Storno number generated successfully!',
              'type' => 'success',
              'title' => 'Success'
          ]);
      }
  }

  public function generate_invoice()
  {
    if (!$this->order->external_invoice_number) {
      session()->flash('notification', [
        'message' => 'Please generate invoice number first!',
        'type' => 'warning',
        'title' => 'Information missing'
      ]);
      return;
    }
    if (!$this->order->invoice_date) {
      session()->flash('notification', [
        'message' => 'Please select invoice date first!',
        'type' => 'warning',
        'title' => 'Information missing'
      ]);
      return;
    }

    $invoiceDate = Carbon::createFromFormat('Y-m-d', $this->order->invoice_date);
    $invoicePath = 'invoices/';
    $yearMonthPath = $invoicePath . $invoiceDate->year . '/' . $invoiceDate->format('F');
    if (!\App\Helpers\MediaHelper::exists($yearMonthPath)) {
      File::makeDirectory($yearMonthPath, 0755, true);
    }

    $date = $invoiceDate->format('d-m-Y');

    $filePath = $yearMonthPath . "/" . (app()->has('label_xml_filename') ? app('label_xml_filename') : 'F_41903669') . '_' .
      $this->order->invoice_series . "_" . $this->order->external_invoice_number . "_" . $date . ".pdf";
    if (file_exists($filePath)) {
      $i = 1;
      $newpath = $yearMonthPath . "/" . (app()->has('label_xml_filename') ? app('label_xml_filename') : 'F_41903669') . '_' .
        $this->order->invoice_series . "_" . $this->order->external_invoice_number . "_" . $date . "(" . $i . ")" . ".pdf";
      while (file_exists($newpath)) {
        $i++;
        $newpath = $yearMonthPath . "/" . (app()->has('label_xml_filename') ? app('label_xml_filename') : 'F_41903669') . '_' .
          $this->order->invoice_series . "_" . $this->order->external_invoice_number . "_" . $date . "(" . $i . ")" . ".pdf";
      }
      $filePath = $newpath;
    }
    if ($this->order->account->type === 'individual') {
      $acc = $this->order->account->name;
      $adress = $this->order->billing->address1 . ",<br> " .
        $this->order->billing->city . ", " .
        $this->order->billing->county . "<br>" .
        $this->order->billing->country . ", " .
        $this->order->billing->zipcode;
    } else {
      $acc = $this->order->account->company_name;
      $adress = "Reg. Com:" . $this->order->account->registration_number . "<br>" .
        "CIF:" . $this->order->account->registration_code . "<br>" .
        $this->order->billing->address1 . ", " .
        $this->order->billing->city . ", " .
        $this->order->billing->county;
    }


    $htmlContent = "
        <html>
        <head>
        <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"/>

        <style>
        *{ font-family: DejaVu Sans !important;
        font-size:10px;
            }
        table {
                width: 100%;
                border-collapse: collapse;
            }
            th, td {
                padding: 8px;
                word-wrap: break-word;
            }

        </style>
         </head>
        <body>
        <table class='info'>
            <tr>

                <td class='ff'>" . (app()->has('label_invoice_title') ? app('label_invoice_title') : 'Invoice') . "</td>
            </tr>
            <tr>
                <td class='ff'>" . (app()->has('label_invoice_series') ? app('label_invoice_series') : 'Series: ') .
      $this->order->invoice_series . " - " .
      (app()->has('label_invoice_number') ? app('label_invoice_number') : 'Number:') .
      $this->order->external_invoice_number . "</td>
            </tr>
            <tr>
                <td class='ff'>" . (app()->has('label_invoice_date') ? app('label_invoice_date') : 'Date: ') . $this->order->invoice_date . "</td>
            </tr>
            <tr>
                <td class='ff'></td>
                <td class='ff'></td>
            </tr>
            <tr>
                <td class='ff'></td>
                <td class='ff'></td>
            </tr>
            <tr>
                <td class='ff'></td>
                <td class='ff'></td>
            </tr>
            <tr>
                <td class='infotd'>" . (app()->has('label_invoice_furnizor') ? app('label_invoice_furnizor') : 'Furnizor: ') . "</td>
                <td class='infotd'>" . (app()->has('label_invoice_client') ? app('label_invoice_client') : 'Client: ') . "</td>
            </tr>
            <tr>
                <td class='infotd'>" . (app()->has('global_invoice_furnizor') ? app('global_invoice_furnizor') : 'Ceva nu a mers bine, verifica setarile') . "</td>
                <td class='infotd'>" . $acc . "<br> " . $adress . "</td>
            </tr>
         </table>
            <br></br>

            <table border='1' cellpadding='5' cellspacing='0' width='100%''>
                <thead>
                    <tr>
                        <th>" . (app()->has('label_invoice_th_nr') ? app('label_invoice_th_nr') : 'Nr. Crt.') . "</th>
                        <th>" . (app()->has('label_invoice_th_name') ? app('label_invoice_th_name') : 'Denumire Articol/Serviciu') . "</th>
                        <th>" . (app()->has('label_invoice_th_um') ? app('label_invoice_th_um') : 'U.M') . "</th>
                        <th>" . (app()->has('label_invoice_th_vat') ? app('label_invoice_th_vat') : 'TVA') . "</th>
                        <th>" . (app()->has('label_invoice_th_quantity') ? app('label_invoice_th_quantity') : 'Cantitate') . "</th>
                        <th>" . (app()->has('label_invoice_th_pu') ? app('label_invoice_th_pu') : 'Pret Unitar - RON') . "</th>
                        <th>" . (app()->has('label_invoice_th_val') ? app('label_invoice_th_val') : 'Valoare - RON') . "</th>
                        <th>" . (app()->has('label_invoice_th_valvat') ? app('label_invoice_th_valvat') : 'Valoare TVA - RON') . "</th>
                        <th>" . (app()->has('label_invoice_th_total') ? app('label_invoice_th_total') : 'Total') . "</th>
                    </tr>
                </thead>
            <tbody>";

    $voucherValue = $this->order->voucher_value + $this->order->promotion_value;
    $totalval = 0;
    $i = 0;
    if ($voucherValue &&  $voucherValue != 0) {
      $vatGroups = [];
      $amountnovoucher = $this->order->final_amount + $voucherValue - $this->order->delivery_price;
    }
    // dd( $this->order->orderItemsSorted );
    foreach ($this->order->orderItemsSorted as $item) {
      $vatRate = (int) $item->vat;
      $pu = $item->price / (1 + ($vatRate / 100));
      $totalval += $pu * $item->quantity;

      if ($voucherValue &&  $voucherValue != 0) {
        if (!isset($vatGroups[$vatRate])) {
          $vatGroups[$vatRate] = [
            'totalpu' => 0,
            'total' => 0,
          ];
        }
        $vatGroups[$vatRate]['totalpu'] += (($item->price / $amountnovoucher) * $item->quantity * $voucherValue) / (1 + ($vatRate / 100));
        $vatGroups[$vatRate]['total'] += ($item->price / $amountnovoucher) * $item->quantity * $voucherValue;
      }



      $htmlContent .= "
                        <tr>
                            <td>" . ($i + 1) . "</td>
                            <td>" . $item->product->name . "<br> (" . $item->product->sku . ")</td>
                            <td>" . (app()->has('label_invoice_um_text') ? app('label_invoice_um_text') : 'buc.') . "</td>
                            <td>" . $vatRate . "</td>
                            <td>" . $item->quantity . "</td>
                            <td>" . number_format($pu, 2) . "</td>
                            <td>" . number_format($pu * $item->quantity, 2) . "</td>
                            <td>" . number_format(($item->price - $pu) * $item->quantity, 2) . "</td>
                            <td>" . number_format($item->price * $item->quantity, 2) . "</td>
                        </tr>";
      $i++;
    }
    if ($voucherValue &&  $voucherValue != 0) {
      foreach ($vatGroups as $vatRate => $group) {
        $totalval -= $group['totalpu'];
        $htmlContent .= "
                            <tr>
                                <td>" . ($i + 1) . "</td>
                                <td>" . (app()->has('label_invoice_th_voucher') ? app('label_invoice_th_voucher') : 'Reducere') . "</td>
                                <td>" . (app()->has('label_invoice_um_text') ? app('label_invoice_um_text') : 'buc.') . "</td>
                                <td>" . $vatRate . "</td>
                                <td>1</td>
                                <td>" . number_format(-$group['totalpu'], 2) . "</td>
                                <td>" . number_format(-$group['totalpu'], 2) . "</td>
                                <td>" . number_format(- ($group['total'] - $group['totalpu']), 2) . "</td>
                                <td>" . number_format(-$group['total'], 2) . "</td>
                            </tr>";
        $i++;
      }
    }
    // delivery sistem
    $delivery_vat = (int) (
      $this->order->delivery_price_vat
      ?? (app()->bound('global_delivery_price_vat') ? app('global_delivery_price_vat') : 19)
    );
    $htmlContent .= "
                <tr>
                    <td>" . ($i + 1) . "</td>
                    <td>" . (app()->has('label_invoice_th_delivery') ? app('label_invoice_th_delivery') : 'Transport') . "</td>
                    <td>" . (app()->has('label_invoice_um_text') ? app('label_invoice_um_text') : 'buc.') . "</td>
                    <td>" . $delivery_vat . "</td>
                    <td>1</td>
                    <td>" . number_format(($this->order->delivery_price / (1 + ($delivery_vat / 100))), 2) . "</td>
                    <td>" . number_format(($this->order->delivery_price / (1 + ($delivery_vat / 100))), 2) . "</td>
                    <td>" . number_format(($this->order->delivery_price - ($this->order->delivery_price / (1 + ($delivery_vat / 100)))), 2) . "</td>
                    <td>" . number_format($this->order->delivery_price, 2) . "</td>
                </tr>";
    $totalval += $this->order->delivery_price / (1 + ($delivery_vat / 100));
    // total row
    $htmlContent .= "
                <tr>
                    <td colspan='6' style='font-weight: 700;text-align:right'><span>" . (app()->has('label_invoice_total_prev') ? app('label_invoice_total_prev') : 'Total') . "</span></td>
                    <td style='font-weight: 700;'><span>" . number_format($totalval, 2) . "</span></td>
                    <td style='font-weight: 700;'><span>" . number_format($this->order->final_amount - $totalval, 2) . "</span></td>
                    <td style='font-weight: 700;'><span>" . number_format($this->order->final_amount, 2) . "</span></td>
                </tr>";

    $htmlContent .= "
            </tbody>
        </table>
        <p style='text-align:right'><strong>" . (app()->has('label_invoice_th_totalfinal') ? app('label_invoice_th_totalfinal') : 'Total Plata ') . " " . number_format($this->order->final_amount, 2) . " " . (app()->has('global_currency_primary_symbol') ? app('global_currency_primary_symbol') : 'lei') . "</strong></p><br>
        <p>" . (app()->has('label_invoice_cf') ? app('label_invoice_cf') : 'Cf. Comanda') . $this->order->order_number . "(". $this->order->quantity_amount .")"."<br>" . (app()->has('label_invoice_footer') ? app('label_invoice_footer') : 'Please check invoice footer label') . "</p></body></html>";

    $pdf = PDF::loadHTML($htmlContent);
    \App\Helpers\MediaHelper::put($filePath, $pdf->output());


    Invoice::create([
      'account_id' => $this->order->account_id,
      'order_id' => $this->order->id,
      'date' => $this->order->invoice_date,
      'type' => 'invoice',
      'path' => $filePath
    ]);
    $vat = $this->order->final_amount - $totalval;
    $type = 'invoice_xml';
    $this->generate_invoice_xml($totalval, $vat, $type);

    session()->flash('notification', [
      'message' => 'Invoice generate successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }

  public function generate_invoice_xml($valoare, $vat, $type)
  {
    if ($type === 'invoice_xml') {
      $date = Carbon::createFromFormat('Y-m-d', $this->order->invoice_date)->format('d-m-Y');
      $serie = $this->order->external_invoice_number;
    } else {
      $date = Carbon::createFromFormat('Y-m-d', $this->order->storno_date)->format('d-m-Y');
      $serie = $this->order->external_storno_number;
    }

    if ($this->order->account->type === 'individual') {

      $invoiceData = [
        'FurnizorNume' => (app()->has('label_xml_FurnizorNume') ? app('label_xml_FurnizorNume') : 'MOLDASO LINE SRL'),
        'FurnizorCIF' => (app()->has('label_xml_FurnizorCIF') ? app('label_xml_FurnizorCIF') : 'RO41903669'),
        'FurnizorNrRegCom' => (app()->has('label_xml_FurnizorNrRegCom') ? app('label_xml_FurnizorNrRegCom') : 'J40/15607/2019'),
        'FurnizorCapital' => (app()->has('label_xml_FurnizorCapital') ? app('label_xml_FurnizorCapital') : '200.00'),
        'FurnizorAdresa' => (app()->has('label_xml_FurnizorAdresa') ? app('label_xml_FurnizorAdresa') : 'BUCURESTI sect. 1 str. BLV.BUCURESTII NOI nr. 50A bl. TRS.A+C ap. 64'),
        'FurnizorBanca' => '',
        'FurnizorIBAN' => '',
        'FurnizorInformatiiSuplimentare' => (app()->has('label_xml_FurnizorInformatiiSuplimentare') ? app('label_xml_FurnizorInformatiiSuplimentare') : 'Tel.0757.527.656'),
        'ClientNume' => Str::ascii($this->order->account->name),
        'ClientInformatiiSuplimentare' => '',
        'ClientCIF' => '',
        'ClientNrRegCom' => '',
        'ClientJudet' => $this->order->billing->county_iso,
        'ClientLocalitate' => Str::ascii($this->order->billing->city),
        'ClientTara' => $this->order->billing->country_iso,
        'ClientAdresa' => Str::ascii($this->order->billing->address1),
        'ClientTelefon' => $this->order->account->phone,
        'ClientEmail' => $this->order->account->email,
        'FacturaNumar' => $this->order->invoice_series . ' - ' . $serie,
        'FacturaData' => $date,
        'FacturaScadenta' =>  $date,
        'FacturaMoneda' => $this->order->currency->name,
        'FacturaGreutate' => 0,
        'FacturaAccize' => 0,
        'FacturaIndexSPV' => '',
        'Detalii' => [],
        'Sumar' => [
          'TotalValoare' => $valoare,
          'TotalTVA' => $vat,
          'Total' => $this->order->final_amount,
        ],
      ];
    } else {
      $invoiceData = [
        'FurnizorNume' => (app()->has('label_xml_FurnizorNume') ? app('label_xml_FurnizorNume') : 'MOLDASO LINE SRL'),
        'FurnizorCIF' => (app()->has('label_xml_FurnizorCIF') ? app('label_xml_FurnizorCIF') : 'RO41903669'),
        'FurnizorNrRegCom' => (app()->has('label_xml_FurnizorNrRegCom') ? app('label_xml_FurnizorNrRegCom') : 'J40/15607/2019'),
        'FurnizorCapital' => (app()->has('label_xml_FurnizorCapital') ? app('label_xml_FurnizorCapital') : '200.00'),
        'FurnizorAdresa' => (app()->has('label_xml_FurnizorAdresa') ? app('label_xml_FurnizorAdresa') : 'BUCURESTI sect. 1 str. BLV.BUCURESTII NOI nr. 50A bl. TRS.A+C ap. 64'),
        'FurnizorBanca' => '',
        'FurnizorIBAN' => '',
        'FurnizorInformatiiSuplimentare' => (app()->has('label_xml_FurnizorInformatiiSuplimentare') ? app('label_xml_FurnizorInformatiiSuplimentare') : 'Tel.0757.527.656'),
        'ClientNume' => Str::ascii($this->order->account->company_name),
        'ClientInformatiiSuplimentare' => '',
        'ClientCIF' => Str::ascii($this->order->account->registration_code),
        'ClientNrRegCom' => Str::ascii($this->order->account->registration_number),
        'ClientJudet' => $this->order->billing->county_iso,
        'ClientLocalitate' => Str::ascii($this->order->billing->city),
        'ClientTara' => $this->order->billing->country_iso,
        'ClientAdresa' => Str::ascii($this->order->billing->address1),
        'ClientTelefon' => $this->order->account->phone,
        'ClientEmail' => '',
        'ClientBanca' => '',
        'ClientIBAN' => '',
        'FacturaNumar' => $this->order->invoice_series . ' - ' . $serie,
        'FacturaData' => $date,
        'FacturaScadenta' =>  $date,
        'FacturaTaxareInversa' => 'Nu',
        'FacturaTVAIncasare' => 'Nu',
        'FacturaInformatiiSuplimentare' => '',
        'FacturaMoneda' => $this->order->currency->name,
        'FacturaGreutate' => 0,
        'FacturaAccize' => 0,
        'FacturaIndexSPV' => '',
        'Detalii' => [],
        'Sumar' => [
          'TotalValoare' => $valoare,
          'TotalTVA' => $vat,
          'Total' => $this->order->final_amount,
        ],
      ];
    }

    foreach ($this->order->orderItemsSorted as $index => $item) {
      $vatRate = (int) $item->vat;
      $priceWithoutVAT = $item->price / (1 + ($vatRate / 100));

      if (!isset($vatSubtotals[$vatRate])) {
        $vatSubtotals[$vatRate] = 0;
      }
      $vatSubtotals[$vatRate] += $item->price * $item->quantity;
      if ($type === 'invoice_xml') {

        $invoiceData['Detalii'][] = [
          'LinieNrCrt' => $index + 1,
          'Descriere' => Str::ascii(
            $item->product->name
          ),
          'CodArticolFurnizor' => strtoupper($item->product->sku),
          'CodArticolClient' => '',
          'CodBare' => '',
          'InformatiiSuplimentare' => '',
          'UM' => 'BUC',
          'Cantitate' => number_format($item->quantity, 4),
          'Pret' => number_format($priceWithoutVAT, 4),
          'Valoare' => number_format($priceWithoutVAT * $item->quantity, 4),
          'ProcTVA' => number_format($vatRate, 4),
          'TVA' => number_format(($item->price - $priceWithoutVAT) * $item->quantity, 4),
        ];
      } else {
        $invoiceData['Detalii'][] = [
          'LinieNrCrt' => $index + 1,
          'Descriere' => Str::ascii(
            $item->product->name
          ),
          'CodArticolFurnizor' => strtoupper($item->product->sku),
          'CodArticolClient' => '',
          'CodBare' => '',
          'InformatiiSuplimentare' => '',
          'UM' => 'BUC',
          'Cantitate' => '-' . number_format($item->quantity, 4),
          'Pret' => number_format($priceWithoutVAT, 4),
          'Valoare' => '-' . number_format($priceWithoutVAT * $item->quantity, 4),
          'ProcTVA' => number_format($vatRate, 4),
          'TVA' => '-' . number_format(($item->price - $priceWithoutVAT) * $item->quantity, 4),
        ];
      }
    }
    $deliveryPrice = $this->order->delivery_price;
    $delivery_vat = (int) (
      $this->order->delivery_price_vat
      ?? (app()->bound('global_delivery_price_vat') ? app('global_delivery_price_vat') : 19)
    );
    $deliveryPriceWithoutVAT = $deliveryPrice / (1 + ($delivery_vat / 100));

    if ($deliveryPrice > 0) {
      if ($type === 'invoice_xml') {

        $invoiceData['Detalii'][] = [
          'LinieNrCrt' => count($invoiceData['Detalii']) + 1,
          'Descriere' => 'TRANSPORT',
          'CodArticolFurnizor' => '000001',
          'CodArticolClient' => '',
          'CodBare' => '',
          'InformatiiSuplimentare' => '',
          'UM' => 'BUC',
          'Cantitate' => '1.0000',
          'Pret' => number_format($deliveryPriceWithoutVAT, 4),
          'Valoare' => number_format($deliveryPriceWithoutVAT, 4),
          'ProcTVA' => number_format($delivery_vat, 2),
          'TVA' => number_format($this->order->delivery_price - $deliveryPriceWithoutVAT, 4),
        ];
      } else {
        $invoiceData['Detalii'][] = [
          'LinieNrCrt' => count($invoiceData['Detalii']) + 1,
          'Descriere' => 'TRANSPORT',
          'CodArticolFurnizor' => '000001',
          'CodArticolClient' => '',
          'CodBare' => '',
          'InformatiiSuplimentare' => '',
          'UM' => 'BUC',
          'Cantitate' => '-' . '1.0000',
          'Pret' => number_format($deliveryPriceWithoutVAT, 4),
          'Valoare' => '-' . number_format($deliveryPriceWithoutVAT, 4),
          'ProcTVA' => number_format($delivery_vat, 2),
          'TVA' => '-' . number_format($this->order->delivery_price - $deliveryPriceWithoutVAT, 4),
        ];
      }
    }
    $voucherValue = $this->order->voucher_value + $this->order->promotion_value;
    if ($voucherValue > 0) {
      $amountNoVoucher = array_sum($vatSubtotals); // Total amount without voucher
      $vatGroups = [];

      foreach ($this->order->orderItemsSorted as $item) {
        $vatRate = (int) $item->vat;
        $priceWithoutVAT = $item->price / (1 + ($vatRate / 100));

        if (!isset($vatGroups[$vatRate])) {
          $vatGroups[$vatRate] = [
            'totalNet' => 0,
            'totalVoucherNet' => 0,
            'totalVoucher' => 0,
          ];
        }

        $voucherImpactNet = (($item->price / $amountNoVoucher) * $item->quantity * $voucherValue) / (1 + ($vatRate / 100));
        $voucherImpactTotal = ($item->price / $amountNoVoucher) * $item->quantity * $voucherValue;

        $vatGroups[$vatRate]['totalNet'] += $priceWithoutVAT * $item->quantity;
        $vatGroups[$vatRate]['totalVoucherNet'] += $voucherImpactNet;
        $vatGroups[$vatRate]['totalVoucher'] += $voucherImpactTotal;
      }

      foreach ($vatGroups as $vatRate => $group) {
        if ($type === 'invoice_xml') {

          $invoiceData['Detalii'][] = [
            'LinieNrCrt' => count($invoiceData['Detalii']) + 1,
            'Descriere' => 'DISCOUNT ACORDAT',
            'CodArticolFurnizor' => '000002',
            'CodArticolClient' => '',
            'CodBare' => '',
            'InformatiiSuplimentare' => '',
            'UM' => 'BUC',
            'Cantitate' => '1.0000',
            'Pret' => '-' . number_format($group['totalVoucherNet'], 4),
            'Valoare' => '-' . number_format($group['totalVoucherNet'], 4),
            'ProcTVA' => number_format($vatRate, 4),
            'TVA' => '-' . number_format($group['totalVoucher'] - $group['totalVoucherNet'], 4),
          ];
        } else {
          $invoiceData['Detalii'][] = [
            'LinieNrCrt' => count($invoiceData['Detalii']) + 1,
            'Descriere' => 'DISCOUNT ACORDAT',
            'CodArticolFurnizor' => '000002',
            'CodArticolClient' => '',
            'CodBare' => '',
            'InformatiiSuplimentare' => '',
            'UM' => 'BUC',
            'Cantitate' => '1.0000',
            'Pret' => number_format($group['totalVoucherNet'], 4),
            'Valoare' => number_format($group['totalVoucherNet'], 4),
            'ProcTVA' => number_format($vatRate, 4),
            'TVA' => number_format($group['totalVoucher'] - $group['totalVoucherNet'], 4),
          ];
        }
      }
    }



    $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?>
    <Facturi />');
    $factura = $xml->addChild('Factura');
    $antet = $factura->addChild('Antet');
    foreach ($invoiceData as $key => $value) {
      if (is_array($value)) continue; // Skip arrays for now
      $antet->addChild($key, htmlspecialchars($value, ENT_XML1 | ENT_COMPAT, 'UTF-8'));
    }

    $detalii = $factura->addChild('Detalii')->addChild('Continut');
    foreach ($invoiceData['Detalii'] as $detail) {
      $linie = $detalii->addChild('Linie');
      foreach ($detail as $key => $value) {
        $linie->addChild($key, htmlspecialchars($value, ENT_XML1 | ENT_COMPAT, 'UTF-8'));
      }
    }

    $sumar = $factura->addChild('Sumar');
    foreach ($invoiceData['Sumar'] as $key => $value) {
      $sumar->addChild($key, htmlspecialchars(number_format($value, 2), ENT_XML1 | ENT_COMPAT, 'UTF-8'));
    }

    if ($type === 'invoice_xml') {
      $invoiceDate = Carbon::createFromFormat('Y-m-d', $this->order->invoice_date);
    } else {
      $invoiceDate = Carbon::createFromFormat('Y-m-d', $this->order->storno_date);
    }
    $invoicePath = 'invoices/';
    $yearMonthPath = $invoicePath . $invoiceDate->year . '/' . $invoiceDate->format('F');

    if (!\App\Helpers\MediaHelper::exists($yearMonthPath)) {
      File::makeDirectory($yearMonthPath, 0755, true);
    }


    $xmlPath = $yearMonthPath . "/" . (app()->has('label_xml_filename') ? app('label_xml_filename') : 'F_41903669') . '_' .
      $this->order->invoice_series . "_" . $serie . "_" . $date . ".xml";
    if (file_exists($xmlPath)) {
      $i = 1;
      $newpath = $yearMonthPath . "/" . (app()->has('label_xml_filename') ? app('label_xml_filename') : 'F_41903669') . '_' .
        $this->order->invoice_series . "_" . $serie . "_" . $date . "(" . $i . ")" . ".xml";
      while (file_exists($newpath)) {
        $i++;
        $newpath = $yearMonthPath . "/" . (app()->has('label_xml_filename') ? app('label_xml_filename') : 'F_41903669') . '_' .
          $this->order->invoice_series . "_" . $serie . "_" . $date . "(" . $i . ")" . ".xml";
      }
      $xmlPath = $newpath;
    }

    if ($type === 'invoice_xml') {
      $data = $this->order->invoice_date;
    } else {
      $data = $this->order->storno_date;
    }
    \App\Helpers\MediaHelper::put($xmlPath, $xml->asXML());
    Invoice::create([
      'account_id' => $this->order->account_id,
      'order_id' => $this->order->id,
      'date' => $data,
      'type' => $type,
      'path' => $xmlPath
    ]);
  }

  public function generate_storno()
  {
    if (!$this->order->external_storno_number) {
      session()->flash('notification', [
        'message' => 'Please generate storno number first!',
        'type' => 'warning',
        'title' => 'Information missing'
      ]);
      return;
    }

    if (!$this->order->storno_date) {
      session()->flash('notification', [
        'message' => 'Please select storno date first!',
        'type' => 'warning',
        'title' => 'Information missing'
      ]);
      return;
    }

    // Folder system
    $StornoPath = 'invoices/';
    $stornoDate = Carbon::createFromFormat('Y-m-d', $this->order->storno_date); // Parse the invoice_date
    $yearMonthPath = $StornoPath . $stornoDate->year . '/' . $stornoDate->format('F');

    if (!\App\Helpers\MediaHelper::exists($yearMonthPath)) {
      File::makeDirectory($yearMonthPath, 0755, true);
    }



    $date = $stornoDate->format('d-m-Y');

    $filePath = $yearMonthPath . "/" . (app()->has('label_xml_filename') ? app('label_xml_filename') : 'F_41903669') . '_' .
      $this->order->invoice_series . "_" . $this->order->external_storno_number . "_" . $date . ".pdf";
    if (file_exists($filePath)) {
      $i = 1;
      $newpath = $yearMonthPath . "/" . (app()->has('label_xml_filename') ? app('label_xml_filename') : 'F_41903669') . '_' .
        $this->order->invoice_series . "_" . $this->order->external_storno_number . "_" . $date . "(" . $i . ")" . ".pdf";
      while (file_exists($newpath)) {
        $i++;
        $newpath = $yearMonthPath . "/" . (app()->has('label_xml_filename') ? app('label_xml_filename') : 'F_41903669') . '_' .
          $this->order->invoice_series . "_" . $this->order->external_storno_number . "_" . $date . "(" . $i . ")" . ".pdf";
      }
      $filePath = $newpath;
    }

    if ($this->order->account->type === 'individual') {
      $acc = $this->order->account->name;
      $adress = $this->order->billing->address1 . ",<br> " .
        $this->order->billing->city . ", " .
        $this->order->billing->county . "<br>" .
        $this->order->billing->country . ", " .
        $this->order->billing->zipcode;
    } else {
      $acc = $this->order->account->company_name;
      $adress = "Reg. Com:" . $this->order->account->registration_number . "<br>" .
        "CIF:" . $this->order->account->registration_code . "<br>" .
        $this->order->billing->address1 . ", " .
        $this->order->billing->city . ", " .
        $this->order->billing->county;
    }

    // generate PDF
    $htmlContent = "
      <html>

      <head>
          <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />

          <style>
          * {
              font-family: DejaVu Sans !important;
              font-size: 12px;
          }
          </style>
      </head>

      <body>
      <table class='info'>
          <tr>
              <td class='ff'></td>
              <td class='ff'>" . (app()->has('label_invoice_title') ? app('label_invoice_title') : 'Invoice') . "</td>
          </tr>
          <tr>
              <td class='ff'></td>
              <td class='ff'>" . (app()->has('label_invoice_series') ? app('label_invoice_series') : 'Series: ') .
      $this->order->invoice_series . " - " .
      (app()->has('label_invoice_number') ? app('label_invoice_number') : 'Number:') .
      $this->order->external_storno_number . "</td>
          </tr>
          <tr>
              <td class='ff'></td>
              <td class='ff'>" . (app()->has('label_invoice_date') ? app('label_invoice_date') : 'Date: ') .
      $this->order->storno_date . "</td>
          </tr>
          <tr>
              <td class='ff'></td>
              <td class='ff'></td>
          </tr>
          <tr>
              <td class='ff'></td>
              <td class='ff'></td>
          </tr>
          <tr>
              <td class='ff'></td>
              <td class='ff'></td>
          </tr>
          <tr>
              <td class='infotd'>" . (app()->has('label_invoice_furnizor') ? app('label_invoice_furnizor') : 'Furnizor: ')
      . "</td>
              <td class='infotd'>" . (app()->has('label_invoice_client') ? app('label_invoice_client') : 'Client: ') . "
              </td>
          </tr>
          <tr>
              <td class='infotd'>" . (app()->has('global_invoice_furnizor') ? app('global_invoice_furnizor') : 'Ceva nu a
                  mers bine, verifica setarile') . "</td>
              <td class='infotd'>" . $acc . "<br> " . $adress . "</td>
          </tr>
      </table>
      <br></br>

      <table border='1' cellpadding='5' cellspacing='0' width='100%''>
                    <thead>
                        <tr>
                            <th>" . (app()->has(' label_invoice_th_nr') ? app('label_invoice_th_nr') : 'Nr. Crt.') . "</th>
                            <th>" . (app()->has('label_invoice_th_name') ? app('label_invoice_th_name') : 'Denumire
        Articol/Serviciu') . "</th>
        <th>" . (app()->has('label_invoice_th_um') ? app('label_invoice_th_um') : 'U.M') . "</th>
        <th>" . (app()->has('label_invoice_th_vat') ? app('label_invoice_th_vat') : 'TVA') . "</th>
        <th>" . (app()->has('label_invoice_th_quantity') ? app('label_invoice_th_quantity') : 'Cantitate') . "
        </th>
        <th>" . (app()->has('label_invoice_th_pu') ? app('label_invoice_th_pu') : 'Pret Unitar - RON') . "</th>
        <th>" . (app()->has('label_invoice_th_val') ? app('label_invoice_th_val') : 'Valoare - RON') . "</th>
        <th>" . (app()->has('label_invoice_th_valvat') ? app('label_invoice_th_valvat') : 'Valoare TVA - RON') .
      "</th>
        <th>" . (app()->has('label_invoice_th_total') ? app('label_invoice_th_total') : 'Total') . "</th>
        </tr>
        </thead>
        <tbody>";

    $voucherValue = $this->order->voucher_value + $this->order->promotion_value;
    $totalval = 0;
    $i = 0;
    if ($voucherValue && $voucherValue != 0) {
      $vatGroups = [];
      $amountnovoucher = $this->order->final_amount + $voucherValue - $this->order->delivery_price;
    }
    foreach ($this->order->orderItemsSorted as $item) {
      $vatRate = (int) $item->vat;
      $pu = $item->price / (1 + ($vatRate / 100));
      $totalval += $pu * $item->quantity;

      if ($voucherValue && $voucherValue != 0) {
        if (!isset($vatGroups[$vatRate])) {
          $vatGroups[$vatRate] = [
            'totalpu' => 0,
            'total' => 0,
          ];
        }
        $vatGroups[$vatRate]['totalpu'] += (($item->price / $amountnovoucher) * $item->quantity * $voucherValue) /
          (1 + ($vatRate / 100));
        $vatGroups[$vatRate]['total'] += ($item->price / $amountnovoucher) * $item->quantity * $voucherValue;
      }



      $htmlContent .= "
            <tr>
                <td>" . ($i + 1) . "</td>
                <td>" . $item->product->name . "<br> (" . $item->product->sku . ")</td>
                <td>" . (app()->has('label_invoice_um_text') ? app('label_invoice_um_text') : 'buc.') . "</td>
                <td>" . $vatRate . "</td>
                <td>" . -$item->quantity . "</td>
                <td>" . number_format($pu, 2) . "</td>
                <td> -" . number_format($pu * $item->quantity, 2) . "</td>
                <td>" . -number_format(($item->price - $pu) * $item->quantity, 2) . "</td>
                <td> - " . number_format($item->price * $item->quantity, 2) . "</td>
            </tr>";
      $i++;
    }
    if ($voucherValue && $voucherValue != 0) {
      foreach ($vatGroups as $vatRate => $group) {
        $totalval -= $group['totalpu'];
        $htmlContent .= "
            <tr>
                <td>" . ($i + 1) . "</td>
                <td>" . (app()->has('label_invoice_th_voucher') ? app('label_invoice_th_voucher') : 'Reducere') . "</td>
                <td>" . (app()->has('label_invoice_um_text') ? app('label_invoice_um_text') : 'buc.') . "</td>
                <td>" . $vatRate . "</td>
                <td>1</td>
                <td>" . +number_format(+$group['totalpu'], 2) . "</td>
                <td>" . +number_format(+$group['totalpu'], 2) . "</td>
                <td>" . +number_format(+ ($group['total'] - $group['totalpu']), 2) . "</td>
                <td>" . +number_format(+$group['total'], 2) . "</td>
            </tr>";
        $i++;
      }
    }
    // delivery sistem
    $delivery_vat = (int) (
      $this->order->delivery_price_vat
      ?? (app()->bound('global_delivery_price_vat') ? app('global_delivery_price_vat') : 19)
    );


    $htmlContent .= "
            <tr>
                <td>" . ($i + 1) . "</td>
                <td>" . (app()->has('label_invoice_th_delivery') ? app('label_invoice_th_delivery') : 'Transport') . "
                </td>
                <td>" . (app()->has('label_invoice_um_text') ? app('label_invoice_um_text') : 'buc.') . "</td>
                <td>" . $delivery_vat . "</td>
                <td>-1</td>
                <td>" . number_format(($this->order->delivery_price / (1 + ($delivery_vat / 100))), 2) . "</td>
                <td>" . -number_format(($this->order->delivery_price / (1 + ($delivery_vat / 100))), 2) . "</td>
                <td>" . -number_format(($this->order->delivery_price - ($this->order->delivery_price / (1 + ($delivery_vat /
      100)))), 2) . "</td>
                <td>" . -number_format($this->order->delivery_price, 2) . "</td>
            </tr>";
    $totalval += $this->order->delivery_price / (1 + ($delivery_vat / 100));
    // total row
    $htmlContent .= "
            <tr>
                <td colspan='6' style='font-weight: 700;text-align:right'><span>" .
      (app()->has('label_invoice_total_prev') ? app('label_invoice_total_prev') : 'Total') . "</span>
                </td>
                <td style='font-weight: 700;'><span> -" . number_format($totalval, 2) . "</span></td>
                <td style='font-weight: 700;'><span>" . -number_format($this->order->final_amount - $totalval, 2) .
      "</span></td>
                <td style='font-weight: 700;'><span> -" . number_format($this->order->final_amount, 2) . "</span></td>
            </tr>";

    $htmlContent .= "
        </tbody>
    </table>
    <p style='text-align:right'><strong>" . (app()->has('label_invoice_th_totalfinal') ?
      app('label_invoice_th_totalfinal') : 'Total Plata ') . " - " . number_format($this->order->final_amount, 2)
      .
      " " . (app()->has('global_currency_primary_symbol') ? app('global_currency_primary_symbol') : 'lei') .
      "</strong></p><br>
    <p>" . (app()->has('label_invoice_cf') ? app('label_invoice_cf') : 'Cf. Comanda') . $this->order->order_number ." (". $this->order->quantity_amount .")".
      "<br>" . (app()->has('label_invoice_footer') ? app('label_invoice_footer') : 'Please check invoice footer
        label') . "</p>
    </body>

      </html>";

    $pdf = PDF::loadHTML($htmlContent);
    \App\Helpers\MediaHelper::put($filePath, $pdf->output());

    Invoice::create([
      'account_id' => $this->order->account_id,
      'order_id' => $this->order->id,
      'date' => $this->order->storno_date,
      'type' => 'storno',
      'path' => $filePath
    ]);

    $vat = $this->order->final_amount - $totalval;
    $type = 'storno_xml';
    $this->generate_invoice_xml($totalval, $vat, $type);

    session()->flash('notification', [
      'message' => 'Storno generate successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }

  public function render()
  {
    return view('livewire.show-order', [
      'order' => $this->order
    ]);
  }

  public function getOrderProperty()
  {
    return $this->orderQuery;
  }

  public function getOrderQueryProperty()
  {
    return Order::with([
      'orderItemsSorted.product' => function ($query) {
        $query->withCount([
          'orders_item as interim_quantity' => function ($query) {
            $query->whereHas('order', function ($q) {
              $q->where(
                'status_id',
                app('global_statuses')['order_processing']
              );
            })->select(DB::raw('SUM(quantity)'));
          }
        ]);
      },
      'status',
      'account',
      'cart',
      'currency',
      'voucher',
      'payment'
    ])->find($this->orderId);
  }


  public function mount($orderId)
  {
    $this->circle = "#37583b";
    $this->orderId = $orderId;
    foreach ($this->order->orders as $orderItem) {

      $product = $orderItem->product;
      $interimQuantity = $product->quantity + $product->interim_quantity;

      if ($interimQuantity < $orderItem->quantity) {
        $this->circle = "#4a0a0f";
      }
    }
  }

  public function canceledit()
  {
    $this->edititem = null;
    $this->record = [];
  }

  public function edititem()
  {
    $this->statuses = Status::where('type', 'order')->get();
    $this->record = [
      'comments' => $this->order->comments,
      'status_id' => $this->order->status_id,
      'invoice_date' => $this->order->invoice_date,
      'storno_date' => $this->order->storno_date,
      'promotion_value' => $this->order->promotion_value,
      'voucher_value' => $this->order->voucher_value,
      'delivery_price' => $this->order->delivery_price,
      'delivery_price_vat' => $this->order->delivery_price_vat,

    ];
    $this->edititem = true;
  }

  public function saveitem()
  {
    $new = $this->record ?? null;

    if (!is_null($new)) {
      $order = Order::find($this->orderId);
      if (!$order) {
        session()->flash('notification', [
          'message' => 'Order not found!',
          'type' => 'error',
          'title' => 'Error'
        ]);
        return;
      }

      $oldStatus = $order->status_id;

      $statusCloseId = Status::where('type', 'order')->where('name', 'canceled')->value('id');
      $checkPaymentId = Status::where('type', 'order')->where('name', 'check_payment')->value('id');

      $updatableFields = [
        'comments',
        'promotion_value',
        'voucher_value',
        'delivery_price',
        'delivery_price_vat',
      ];
      foreach ($updatableFields as $field) {
        if (isset($new[$field])) {
          $order->$field = $new[$field];
        }
      }

      if (array_key_exists('storno_date', $new)) {
        $order->storno_date = $new['storno_date'] === '' ? null : $new['storno_date'];
      }
      if (array_key_exists('invoice_date', $new)) {
        $order->invoice_date = $new['invoice_date'] === '' ? null : $new['invoice_date'];
      }


      if (isset($new['promotion_value']) || isset($new['voucher_value']) || isset($new['delivery_price'])) {
        $order->final_amount = max(
          0,
          ($order->sum_amount ?? 0) + ($order->delivery_price ?? 0) - ($order->promotion_value ?? 0) - ($order->voucher_value
            ?? 0)
        );
      }

      // Handle status change logic
      if (isset($new['status_id']) && $oldStatus !== $new['status_id']) {
        $order->status_id = $new['status_id'];
        $order->updated_at = now(config('app.timezone'));

        $shouldIncreaseStock = false;
        $shouldDecreaseStock = false;

        if ($oldStatus == $statusCloseId && $new['status_id'] != $checkPaymentId) {
          // If changing from "canceled" to any other status, reduce stock
          $shouldDecreaseStock = true;
        } elseif ($new['status_id'] == $statusCloseId && $oldStatus != $checkPaymentId) {
          // If changing to "canceled", restore stock
          $shouldIncreaseStock = true;
        } elseif ($oldStatus == $checkPaymentId && $new['status_id'] != $statusCloseId) {
          // If leaving "check_payment" and not going to "canceled", reduce stock
          $shouldDecreaseStock = true;
        } elseif ($oldStatus != $statusCloseId && $new['status_id'] == $checkPaymentId) {
          // If moving to "check_payment" from any other status, increase stock
          $shouldIncreaseStock = true;
        }

        foreach ($order->orders as $orderItem) {
          if ($shouldIncreaseStock) {
            $orderItem->product->quantity += $orderItem->quantity;
          } elseif ($shouldDecreaseStock) {
            $orderItem->product->quantity -= $orderItem->quantity;
          }
          $orderItem->product->save();
        }
      }

      $order->save();

      $this->emit('itemSaved');
      session()->flash('notification', [
        'message' => 'Record edited successfully!',
        'type' => 'success',
        'title' => 'Success'
      ]);
    }

    // Reset form data
    $this->record = [];
    $this->edititem = null;
  }

  public function confirmItemRemoval()
  {
    $this->delete = true;
  }

  public function cancelItemRemoval()
  {
    $this->delete = false;
  }

  public function deleteRecord()
  {
    $order = Order::findOrFail($this->orderId);
    foreach ($order->orders as $orderitem) {
      $orderitem->product->quantity += $orderitem->quantity;
      $orderitem->product->save();
      $orderitem->delete();
    }

    $invoices = Invoice::where('order_id', $this->orderId)->get();
    foreach ($invoices as $invoice) {
      $del = Invoice::find($invoice->id);
      if (\App\Helpers\MediaHelper::exists($del->path)) {
        \App\Helpers\MediaHelper::delete($del->path);
      }
      $del->delete();
    }
    $order->delete();
    $this->delete = false;
    return redirect()->route('orders')->with('notification', [
      'message' => 'Record deleted successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }
}
