<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\AllJob;
use Livewire\Component;
use App\Models\Listview;
use Illuminate\Support\Str;
use App\Models\CsvImportJob;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;
use App\Jobs\CalculateOrdersCost;

use App\Jobs\DynamicCsvImportJob;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Orderstable extends Component
{
  use WithFileUploads;
  // importdata
  public $importdata = false;
  public $csvimportdata;
  use WithPagination;
  public $loadAmount;
  public $search = '';
  public $orderBy;
  public $orderAsc;
  public $checked = [];
  public $selectPage = false;
  public $selectAll = false;
  public $columns;
  public $selectedColumns = [];
  public $idbeingremoved = null;
  public $single = false;
  public $multiple = false;
  public $row = null;
  public $status31Only = false;
  public $xmlinvoicesmodal = false;
  public $xmlstornomodal = false;
  public $filteractive = false;
  public $start_date_filter;
  public $end_date_filter;
  public $start_date;
  public $end_date;

  // listview variables
  public $relation = false;
  public $editlistview = false;
  public $tableName;
  public $activelistview;
  public $addlistview = false;
  public string $selectedAvailable = '';
  public string $selectedVisible = '';
  public bool $edit = true;
  public bool $filter = false;
  public bool $filterlogic = false;
  public array $availableFields = [];
  public array $listview = [
    'name' => null,
    'logic' => null,
    'model' => null,
    'columns' => [],
    'filters' => [],
    'sort' => [
      'column' => null,
      'direction' => null,
    ],
  ];
  public $addfilter = [
    'column' => null,
    'operator' => null,
    'value' => null,
  ];

  protected $rules = [
    'start_date' => 'required|date',
    'end_date' => 'required|date|after_or_equal:start_date',
  ];

  protected $messages = [
    'start_date.required' => 'Start date is required.',
    'end_date.required' => 'The end date is required.',
    'end_date.after_or_equal' => 'The end date must be after or equal to the start date.',
  ];

  public function render()
  {
    $activeId = $this->activelistview?->id;

    return view('livewire.orderstable', [
      'orders' => $this->orders,
      'listviews' => $this->listviews->filter(function ($view) use ($activeId) {
        return $view->id !== $activeId;
      }),

    ]);
  }
  public function mount($tableName)
  {
    $this->loadAmount = app()->bound('global_dashboard_limit_load')
      ? app('global_dashboard_limit_load') ?? 50
      : 50;

    $this->tableName = $tableName;
    $this->columns = Schema::getColumnListing($tableName);
    sort($this->columns);


    $listviews = $this->getListviewsProperty();
    $this->activelistview = $listviews->first();

    if (!$this->activelistview) {
      $defaultColumns = ['id', 'created_at', 'updated_at'];
      $preferredColumn = 'name';

      if (in_array($preferredColumn, $this->columns)) {
        $nextColumn = $preferredColumn;
      } else {
        $idIndex = array_search('id', $this->columns);
        $nextColumn = null;

        if ($idIndex !== false && isset($this->columns[$idIndex + 1])) {
          $nextCandidate = $this->columns[$idIndex + 1];
          if (!in_array($nextCandidate, $defaultColumns)) {
            $nextColumn = $nextCandidate;
          }
        }

        if (!$nextColumn) {
          $nextColumn = collect($this->columns)
            ->reject(fn($col) => in_array($col, $defaultColumns))
            ->first();
        }
      }

      $selectedColumns = array_filter([
        'id',
        $nextColumn,
        'created_at',
        'updated_at',
      ]);

      Listview::create([
        'user_id' => Auth::id(),
        'name' => 'default',
        'model' => $this->tableName,
        'columns' => $selectedColumns,
        'filters' => [],
        'sort' => [
          'column' => 'id',
          'direction' => 'asc',
        ],
        'logic' => null,
      ]);

      $listviews = $this->getListviewsProperty();
      $this->activelistview = $listviews->first();
    }

    if ($this->activelistview) {
      $sorts = is_array($this->activelistview?->sorts) ? $this->activelistview->sorts : [];

      $this->listview = [
        'name' => $this->activelistview->name ?? '',
        'logic' => $this->activelistview->logic ?? '',
        'model' => $this->activelistview->model ?? $this->tableName,
        'columns' => $this->activelistview->columns ?? [],
        'filters' => $this->activelistview->filters ?? [],
        'sort' => [
          'column' => $sorts['column'] ?? 'id',
          'direction' => $sorts['direction'] ?? 'asc',
        ],
      ];
    }

    $this->availableFields = array_values(array_diff($this->columns ?? [], $this->listview['columns'] ?? []));
    sort($this->availableFields);

    $this->orderBy = $this->listview['sort']['column'] ?? 'id';
    $this->orderAsc = ($this->listview['sort']['direction'] ?? 'asc') === 'asc' ? '1' : '0';
    $this->selectedColumns = $this->listview['columns'] ?? [];
  }
  public function getOrdersProperty()
  {
    return $this->ordersQuery->paginate($this->loadAmount);
  }
  public function getOrdersQueryProperty()
  {
    $query = Order::search($this->search)
      ->with([
        'orders.product' => function ($query) {
          $query->withCount(['orders_item as interim_quantity' => function ($query) {
            $query->whereHas('order', function ($q) {
              $q->where('status_id', app('global_order_processing'));
            })->select(DB::raw('sum(quantity)'));
          }]);
        },
        'status',
        'account',
        'cart',
        'currency',
        'voucher',
        'payment'
      ]);
    $this->applyFilters($query);
    if ($this->status31Only) {
      $query = $query->where('status_id', app('global_order_processing'));
    }

    if ($this->start_date_filter && $this->end_date_filter) {
      $query = $query->whereBetween('invoice_date', [$this->start_date_filter, $this->end_date_filter]);
    }

    return $query->orderBy($this->listview['sort']['column'] ?? 'created_at', $this->listview['sort']['direction'] ?? 'desc');
  }

  // listview functions
  public function updatingAddlistview($value)
  {
    if ($value) {
      $this->listview['name'] = null;
    }
  }
  public function updatingEditlistview($value)
  {
    if ($value) {
      $this->listview['name'] = $this->activelistview?->name ?? '';
    }
  }
  public function applyFilters($query)
  {
    $filters = $this->activelistview?->filters ?? [];
    $logic = $this->activelistview?->logic ?? null;

    if (empty($filters)) return $query;

    $closures = [];

    foreach ($filters as $index => $filter) {
      $column = $filter['column'];
      $operator = strtolower($filter['operator']);
      $value = $filter['value'];

      if (in_array($value, [true, 'true', '1'], true)) {
        $type = 'boolean';
        $value = 1;
      } elseif (in_array($value, [false, 'false', '0'], true)) {
        $type = 'boolean';
        $value = 0;
      } elseif (is_numeric($value)) {
        $type = 'number';
        $value = (float) $value;
      } elseif (preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
        $type = 'date';
        try {
          $value = \Carbon\Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
          continue;
        }
      } else {
        $type = 'string';
      }

      $closures[$index] = function ($q) use ($column, $operator, $value, $type) {
        if ($operator === 'like') {
          $q->where($column, 'like', "%$value%");
        } elseif ($type === 'date' && in_array($operator, ['=', '!=', '<', '>', '<=', '>='])) {
          $q->whereDate($column, $operator, $value);
        } else {
          $q->where($column, $operator, $value);
        }
      };
    }


    if ($logic === null) {
      return $query->where(function ($q) use ($closures) {
        foreach ($closures as $closure) {
          $closure($q);
        }
      });
    }

    $tokens = preg_split('/\s+/', trim(str_replace(['(', ')'], [' ( ', ' ) '], $logic)));

    $buildExpression = function (&$tokens) use (&$buildExpression, $closures) {
      $stack = [];

      while (!empty($tokens)) {
        $token = array_shift($tokens);

        if ($token === '(') {
          $stack[] = $buildExpression($tokens);
        } elseif ($token === ')') {
          break;
        } elseif (strtoupper($token) === 'AND' || strtoupper($token) === 'OR') {
          $stack[] = strtoupper($token);
        } elseif (is_numeric($token)) {
          $stack[] = $closures[(int) $token] ?? null;
        }
      }

      $reduce = function ($stack) use (&$reduce) {
        while (count($stack) > 1) {
          $a = array_shift($stack);
          $op = array_shift($stack);
          $b = array_shift($stack);

          $combined = function ($q) use ($a, $b, $op) {
            if ($op === 'AND') {
              $q->where(function ($q) use ($a) {
                $a($q);
              })->where(function ($q) use ($b) {
                $b($q);
              });
            } elseif ($op === 'OR') {
              $q->where(function ($q) use ($a) {
                $a($q);
              })->orWhere(function ($q) use ($b) {
                $b($q);
              });
            }
          };

          array_unshift($stack, $combined);
        }

        return $stack[0];
      };

      return $reduce($stack);
    };

    $final = $buildExpression($tokens);

    return $query->where(function ($q) use ($final) {
      $final($q);
    });
  }
  public function clearAllFilters()
  {
    $this->listview['filters'] = [];
    $this->listview['logic'] = '';
  }
  public function save_filter()
  {
    $this->validate([
      'addfilter.column' => 'required|string',
      'addfilter.operator' => 'required|string',
      'addfilter.value' => 'required|string',
    ]);

    $operatorLabels = [
      '=' => 'equals',
      'like' => 'contains',
      '>' => 'greater than',
      '<' => 'less than',
    ];

    $operator = $this->addfilter['operator'];
    $column = $this->addfilter['column'];
    $value = $this->addfilter['value'];

    $newFilter = [
      'column' => $column,
      'operator' => $operator,
      'value' => $value,
      'label' => $operatorLabels[$operator] ?? $operator,
      'display' => $column . ' ' . ($operatorLabels[$operator] ?? $operator) . ' ' . $value,
    ];

    $this->listview['filters'][] = $newFilter;
    $index = count($this->listview['filters']) - 1;
    $logic = trim($this->listview['logic'] ?? '');
    if ($logic === '' || $logic === null) {
      $logic = (string) $index;
    } else {
      $logic .= ' AND ' . $index;
    }
    $this->listview['logic'] = $logic;

    $this->addfilter = [
      'column' => null,
      'operator' => null,
      'value' => null,
    ];
  }
  public function removeFilter($index)
  {
    unset($this->listview['filters'][$index]);

    $this->listview['filters'] = array_values($this->listview['filters']);

    if ($this->activelistview) {
      $originalLogic = $this->listview['logic'] ?? '';
      $updatedLogic = $originalLogic;

      $oldToNew = [];
      $filterCountBefore = count($this->listview['filters']) + 1;
      for ($i = 0, $j = 0; $i < $filterCountBefore; $i++) {
        if ($i == $index) continue;
        $oldToNew[$i] = $j++;
      }

      $updatedLogic = preg_replace_callback('/\d+/', function ($matches) use ($oldToNew, $index) {
        $oldIndex = (int) $matches[0];
        if ($oldIndex === $index) {
          return '__REMOVED__';
        }
        return $oldToNew[$oldIndex] ?? $matches[0];
      }, $updatedLogic);

      $updatedLogic = preg_replace([
        '/\bAND\s+__REMOVED__\b/',
        '/\bOR\s+__REMOVED__\b/',
        '/\b__REMOVED__\s+AND\b/',
        '/\b__REMOVED__\s+OR\b/',
        '/\b__REMOVED__\b/',
      ], '', $updatedLogic);

      $updatedLogic = preg_replace('/\(\s*(\d+)\s*\)/', '$1', $updatedLogic);

      $updatedLogic = preg_replace('/\(\s*\)/', '', $updatedLogic);

      $updatedLogic = trim(preg_replace('/\s+/', ' ', $updatedLogic));
      $updatedLogic = preg_replace('/^(AND|OR)\s+/', '', $updatedLogic);
      $updatedLogic = preg_replace('/\s+(AND|OR)$/', '', $updatedLogic);

      $this->listview['logic'] = $updatedLogic;
    }
  }
  public function toggle($item)
  {
    if ($item == 'edit') {
      $this->edit = !$this->edit;
      $this->filter = !$this->filter;
    } else {
      $this->edit = !$this->edit;
      $this->filter = !$this->filter;
    }
  }
  public function delete_listview()
  {
    if ($this->activelistview) {
      $this->activelistview->delete();
      $this->editlistview = false;

      session()->flash('notification', [
        'message' => 'Listview deleted successfully!',
        'type' => 'success',
        'title' => 'Success'
      ]);
    } else {
      $this->editlistview = false;

      session()->flash('notification', [
        'message' => 'Listview not found!',
        'type' => 'danger',
        'title' => 'Error'
      ]);
    }
    return $this->mount($this->tableName);
  }
  public function save_listview($recurency = false)
  {
    $this->validate([
      'listview.name' => [
        'required',
        'string',
        'max:255'
      ],
    ]);

    if (!$this->isValidLogicExpression($this->listview['logic'])) {
      session()->flash('notification', [
        'message' => 'Invalid logic expression. Only numbers, AND, OR, and balanced parentheses are allowed.',
        'type' => 'error',
        'title' => 'Validation Error'
      ]);
      return;
    }

    if ($this->activelistview) {
      $this->activelistview->update([
        'name' => $this->listview['name'],
        'logic' => $this->listview['logic'],
        'columns' => $this->listview['columns'],
        'filters' => $this->listview['filters'],
        'sorts' => [
          'column' => $this->orderBy ?? 'id',
          'direction' => $this->orderAsc ? 'asc' : 'desc',
        ],
      ]);
    } else {
      Listview::create([
        'user_id' => Auth::id(),
        'name' => $this->listview['name'],
        'model' => $this->tableName,
        'columns' => $this->listview['columns'],
        'filters' => $this->listview['filters'],
        'sorts' => $this->listview['sort'],
        'logic' => $this->listview['logic'],
      ]);
    }

    if (!$recurency) {
      $this->editlistview = false;

      session()->flash('notification', [
        'message' => 'Listview saved successfully!',
        'type' => 'success',
        'title' => 'Success'
      ]);
    }

    return $this->mount($this->tableName);
  }
  protected function isValidLogicExpression(string $logic): bool
  {
    $logic = trim($logic);
    if ($logic === '') {
      return true;
    }

    $tokens = preg_split('/\s+/', str_replace(['(', ')'], [' ( ', ' ) '], $logic));
    $tokens = array_values(array_filter($tokens, fn($t) => $t !== ''));

    $validTokens = ['AND', 'OR', '(', ')'];
    $openParens = 0;
    $prev = null;

    foreach ($tokens as $token) {
      $upper = strtoupper($token);

      if (!is_numeric($token) && !in_array($upper, $validTokens)) {
        return false;
      }

      if ($token === '(') {
        $openParens++;
      } elseif ($token === ')') {
        $openParens--;
        if ($openParens < 0) return false;
      }

      if ($prev !== null) {
        if ((is_numeric($prev) || $prev === ')') && (is_numeric($token) || $token === '(')) {
          return false;
        }

        if (in_array(strtoupper($prev), ['AND', 'OR']) && in_array($upper, ['AND', 'OR', ')'])) {
          return false;
        }

        if ($prev === '(' && in_array($upper, ['AND', 'OR', ')'])) {
          return false;
        }

        if ($token === ')' && in_array(strtoupper($prev), ['AND', 'OR', '('])) {
          return false;
        }
      }

      $prev = $token;
    }

    return $openParens === 0;
  }
  public function getListviewsProperty()
  {
    if (!Schema::hasTable('listviews')) {
      Artisan::call('ensure:listviews-table');
    }

    return Listview::where('user_id', Auth::id())
      ->where('model', $this->tableName)
      ->orderBy('updated_at', 'desc')
      ->get();
  }
  public function moveToVisible()
  {
    if ($this->selectedAvailable !== '') {
      $this->listview['columns'][] = $this->selectedAvailable;
      $this->availableFields = array_filter($this->availableFields, fn($field) => $field !== $this->selectedAvailable);
      $this->listview['columns'] = array_values(array_unique($this->listview['columns']));
      $this->selectedAvailable = '';
    }
  }
  public function moveToAvailable()
  {
    if ($this->selectedVisible !== '') {
      $this->availableFields[] = $this->selectedVisible;
      $this->listview['columns'] = array_filter($this->listview['columns'], fn($field) => $field !== $this->selectedVisible);
      $this->availableFields = array_values(array_unique($this->availableFields));
      $this->selectedVisible = '';
    }
  }
  public function moveVisibleFieldUp()
  {
    $index = array_search($this->selectedVisible, $this->listview['columns']);

    if ($index !== false && $index > 0) {
      [$this->listview['columns'][$index - 1], $this->listview['columns'][$index]] =
        [$this->listview['columns'][$index], $this->listview['columns'][$index - 1]];
    }
  }
  public function moveVisibleFieldDown()
  {
    $index = array_search($this->selectedVisible, $this->listview['columns']);

    if ($index !== false && $index < count($this->listview['columns']) - 1) {
      [$this->listview['columns'][$index + 1], $this->listview['columns'][$index]] =
        [$this->listview['columns'][$index], $this->listview['columns'][$index + 1]];
    }
  }
  public function add_listview()
  {
    $this->validate([
      'listview.name' => [
        'required',
        'string',
        'max:255',
        Rule::unique('listviews', 'name')
          ->where(
            fn($query) => $query
              ->where('user_id', Auth::id())
              ->where('model', $this->tableName)
          ),
      ],
    ]);

    ListView::create([
      'user_id' => Auth::id(),
      'name' => $this->listview['name'],
      'model' => $this->tableName,
    ]);

    $this->listview['name'] = '';
    $this->addlistview = false;


    session()->flash('notification', [
      'message' => 'Listview added successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }
  public function setActiveListview($id)
  {
    $this->activelistview = Listview::find($id);

    if (!$this->activelistview) {
      session()->flash('notification', [
        'message' => 'Listview not found.',
        'type' => 'error',
        'title' => 'Error'
      ]);
      return;
    }

    $this->activelistview->update([
      'updated_at' => now(),
    ]);

    $this->listview = [
      'name' => $this->activelistview->name,
      'columns' => $this->activelistview->columns ?? [],
      'filters' => $this->activelistview->filters ?? [],
      'sort' => $this->activelistview->sorts ?? [
        'column' => 'id',
        'direction' => 'asc',
      ],
    ];

    $this->search = '';
    $this->mount($this->tableName);
  }
  public function sortBy($columnName)
  {
    if ($this->orderBy === $columnName) {
      $this->orderAsc = $this->swapSortDirection();
    } else {
      $this->orderAsc = '1';
    }
    $this->orderBy = $columnName;
    $this->save_listview(true);
  }

  public function generate_xml_invoice()
  {
    $this->validate();

    $ordersQuery = \App\Models\Order::query();

    if ($this->start_date && $this->end_date) {
      if ($this->start_date === $this->end_date) {
        $ordersQuery->whereDate('invoice_date', $this->start_date);
      } else {
        $ordersQuery->whereBetween('invoice_date', [$this->start_date, $this->end_date]);
      }
    }

    $orders = $ordersQuery->get();
    $type = 'invoice_xml';
    $path = $this->generate_invoice_xml($orders, $type);

    if ($path) {
      session()->flash('message', 'XML Invoice generated successfully.');
      $this->resetModal();
      return response()->download($path);
    } else {
      $this->resetModal();
      session()->flash('warning', 'No order found');
      return;
    }
  }
  public function generate_invoice_xml($orders, $type)
  {
    if ($orders->isEmpty()) {
      session()->flash('notification', [
        'message' => 'No orders found for the specified criteria!',
        'type' => 'warning',
        'title' => 'No Orders'
      ]);
      return;
    }
    $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><Facturi />');


    foreach ($orders as $order) {

      if ($type === 'invoice_xml') {
        $date = Carbon::createFromFormat('Y-m-d', $order->invoice_date)->format('d-m-Y');
        $serie = $order->external_invoice_number;
      } else {
        $date = Carbon::createFromFormat('Y-m-d', $order->storno_date)->format('d-m-Y');
        $serie = $order->external_storno_number;
      }

      $pu = 0;
      $va = 0;
      $vatSubtotals = [];
      foreach ($order->orders as $index => $item) {
        $vatRate = (int) $item->vat;
        $priceWithoutVAT = $item->price / (1 + ($vatRate / 100));
        $pu += $priceWithoutVAT;
        $va += ($item->price - $priceWithoutVAT);
      }
      $invoiceData = [
        'FurnizorNume' => (app()->has('label_xml_FurnizorNume') ? app('label_xml_FurnizorNume') : 'MOLDASO LINE SRL'),
        'FurnizorCIF' => (app()->has('label_xml_FurnizorCIF') ? app('label_xml_FurnizorCIF') : 'RO41903669'),
        'FurnizorNrRegCom' => (app()->has('label_xml_FurnizorNrRegCom') ? app('label_xml_FurnizorNrRegCom') : 'J40/15607/2019'),
        'FurnizorCapital' => (app()->has('label_xml_FurnizorCapital') ? app('label_xml_FurnizorCapital') : '200.00'),
        'FurnizorAdresa' => (app()->has('label_xml_FurnizorAdresa') ? app('label_xml_FurnizorAdresa') : 'BUCURESTI'),
        'FurnizorBanca' => '',
        'FurnizorIBAN' => '',
        'FurnizorInformatiiSuplimentare' => (app()->has('label_xml_FurnizorInformatiiSuplimentare') ?
          app('label_xml_FurnizorInformatiiSuplimentare') : 'Tel. 0757.527.656'),
        'ClientNume' => Str::ascii(
          $order->account->name
        ),
        'ClientInformatiiSuplimentare' => '',
        'ClientCIF' => '',
        'ClientNrRegCom' => '',
        'ClientJudet' => $order->billing->county_iso,
        'ClientLocalitate' => Str::ascii(
          $order->billing->city
        ),
        'ClientTara' => $order->billing->country_iso,
        'ClientAdresa' => Str::ascii(
          $order->billing->address1
        ),
        'ClientTelefon' => $order->account->phone,
        'ClientEmail' => $order->account->email,
        'FacturaNumar' => $order->invoice_series . ' - ' . $serie,
        'FacturaData' => $date,
        'FacturaScadenta' => $date,
        'FacturaMoneda' => $order->currency->name,
        'FacturaGreutate' => 0,
        'FacturaAccize' => 0,
        'FacturaIndexSPV' => '',
        'Detalii' => [],
        'Sumar' => [
          'TotalValoare' => $pu,
          'TotalTVA' => $va,
          'Total' => $order->final_amount,
        ],


      ];


      foreach ($order->orders as $index => $item) {
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
      $deliveryPrice = $order->delivery_price;
      $deliveryPriceVat = (int) ($order->delivery_price_vat ?: (app('global_delivery_price_vat') ?: 21));

      $deliveryPriceWithoutVAT = $deliveryPrice / (1 + ($deliveryPriceVat / 100));

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
            'Pret' => number_format(
              $deliveryPriceWithoutVAT,
              4
            ),
            'Valoare' => number_format($deliveryPriceWithoutVAT, 4),
            'ProcTVA' => number_format($deliveryPriceVat, 2),
            'TVA' => number_format($order->delivery_price - $deliveryPriceWithoutVAT, 4),
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
            'Pret' => number_format(
              $deliveryPriceWithoutVAT,
              4
            ),
            'Valoare' => '-' . number_format($deliveryPriceWithoutVAT, 4),
            'ProcTVA' => number_format($deliveryPriceVat, 2),
            'TVA' => '-' . number_format($order->delivery_price - $deliveryPriceWithoutVAT, 4),
          ];
        }
      }
      $voucherValue = $order->voucher_value + $order->promotion_value;
      if ($voucherValue > 0) {
        $amountNoVoucher = array_sum($vatSubtotals);
        $vatGroups = [];

        foreach ($order->orders as $item) {
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

        if ($type === 'invoice_xml') {
          $sign = '-';
        } else {
          $sign = '';
        }
        foreach ($vatGroups as $vatRate => $group) {
          $invoiceData['Detalii'][] = [
            'LinieNrCrt' => count($invoiceData['Detalii']) + 1,
            'Descriere' => 'DISCOUNT ACORDAT',
            'CodArticolFurnizor' => '000002',
            'CodArticolClient' => '',
            'CodBare' => '',
            'InformatiiSuplimentare' => '',
            'UM' => 'BUC',
            'Cantitate' => '1.0000',
            'Pret' => $sign . number_format($group['totalVoucherNet'], 4),
            'Valoare' => $sign . number_format($group['totalVoucherNet'], 4),
            'ProcTVA' => number_format($vatRate, 4),
            'TVA' => $sign . number_format($group['totalVoucher'] - $group['totalVoucherNet'], 4),
          ];
        }
      }


      $factura = $xml->addChild('Factura');
      $antet = $factura->addChild('Antet');
      foreach ($invoiceData as $key => $value) {
        if (is_array($value)) continue;
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
    }

    $invoicePath = 'invoices/';
    if ($type === 'invoice_xml') {
      $pref = 'invoice';
    } else {
      $pref = 'storno';
    }
    $fileName = (app()->has('label_xml_filename') ? app('label_xml_filename') : 'F_41903669') . '_' . $pref .
      "_{$this->start_date}_to_{$this->end_date}.xml";

    if (!File::exists($invoicePath)) {
      File::makeDirectory($invoicePath, 0755, true);
    }

    Storage::disk('public_upload')->put($invoicePath . $fileName, $xml->asXML());

    session()->flash('notification', [
      'message' => "Invoices XML successfully generated at: $fileName",
      'type' => 'success',
      'title' => 'XML Generated'
    ]);
    $path = public_path($invoicePath . $fileName);
    return $path;
  }
  public function generate_xml_storno()
  {
    $this->validate();

    $ordersQuery = \App\Models\Order::query();

    if ($this->start_date && $this->end_date) {
      if ($this->start_date === $this->end_date) {
        $ordersQuery->whereDate('storno_date', $this->start_date);
      } else {
        $ordersQuery->whereBetween('storno_date', [$this->start_date, $this->end_date]);
      }
    }
    $orders = $ordersQuery->get();
    if ($orders) {


      $type = 'storno_xml';
      $path = $this->generate_invoice_xml($orders, $type);

      if ($path) {
        session()->flash('message', 'XML Invoice generated successfully.');
        $this->resetModal();
        return response()->download($path);
      } else {
        $this->resetModal();
        session()->flash('warning', 'No order found');
        return;
      }
    } else {
      $this->resetModal();

      session()->flash('message', 'No order founds beteen those dates.');
    }
  }
  public function cancel_xml()
  {
    $this->resetModal();
  }
  private function resetModal()
  {
    $this->xmlinvoicesmodal = false;
    $this->xmlstornomodal = false;
    $this->reset(['start_date', 'end_date']);
  }
  public function xmlinvoices()
  {
    $this->xmlinvoicesmodal = true;
  }
  public function filter()
  {
    $this->filteractive = true;
  }
  public function xmlstorno()
  {
    $this->xmlstornomodal = true;
  }
  public function expandRow($index)
  {
    if ($this->row === null) {
      $this->row = $index;
    } elseif ($this->row != $index) {
      $this->row = $index;
    } else {
      $this->row = null;
    }
  }
  public function cancel_filter()
  {
    $this->filteractive = false;
  }
  public function filter_order()
  {
    $this->validate([
      'start_date_filter' => 'nullable|date',
      'end_date_filter' => 'nullable|date|after_or_equal:start_date_filter',
    ]);

    $this->orders->when($this->start_date_filter && $this->end_date_filter, function ($query) {
      $query->whereBetween('invoice_date', [$this->start_date_filter, $this->end_date_filter]);
    });
    $this->filteractive = false;
  }
  public function updatedSelectPage($value)
  {
    if ($value) {
      $this->checked = $this->orders->pluck('id')->map(fn($item) => (string) $item)->toArray();
    } else {
      $this->checked = [];
    }
  }
  public function updatedChecked()
  {
    $this->selectPage = false;
  }
  public function swapSortDirection()
  {
    return $this->orderAsc === '1' ? '0' : '1';
  }
  public function isChecked($id)
  {
    return in_array($id, $this->checked);
  }
  public function selectAll()
  {
    $this->selectAll = true;
    $this->checked = $this->ordersQuery->pluck('id')->map(fn($item) => (string) $item)->toArray();
  }
  public function loadMore()
  {
    $this->loadAmount += 10;
  }
  public function deleteSingleRecord()
  {
    $id = $this->idbeingremoved;
    $order = Order::findOrFail($id);
    foreach ($order->orders as $orderitem) {
      $orderitem->product->quantity += $orderitem->quantity;
      $orderitem->product->save();
      $orderitem->delete();
    }

    $order->delete();
    $this->checked = array_diff($this->checked, [$id]);
    $this->single = false;
    session()->flash('notification', [
      'message' => 'Record deleted successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }
  public function confirmItemRemoval($id)
  {
    $this->idbeingremoved = $id;
    $this->single = true;
  }
  public function confirmItemsRemoval()
  {
    $this->multiple = true;
  }
  public function cancel_delete()
  {
    $this->multiple = false;
    $this->single = false;
  }
  public function deleteRecords()
  {
    $orders = Order::whereKey($this->checked)->get();
    foreach ($orders as $order) {
      foreach ($order->orders as $orderitem) {
        $orderitem->product->quantity += $orderitem->quantity;
        $orderitem->product->save();
        $orderitem->delete();
      }

      $order->delete();
    }
    $this->checked = [];
    $this->selectPage = false;
    $this->multiple = false;
    session()->flash('notification', [
      'message' => 'Records deleted successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }
  // export-import data
  public function exportData()
  {
    $selectedColumns = $this->listview['columns'] ?? [];

    if (empty($selectedColumns)) {
      session()->flash('notification', ['message' => 'No columns selected for export.', 'type' => 'error']);
      return;
    }

    $filename = $this->tableName . '.csv';
    $checked = $this->checked;

    return Response::streamDownload(function () use ($selectedColumns, $checked) {
      $handle = fopen('php://output', 'w');

      fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

      fputcsv($handle, $selectedColumns);

      if (empty($checked)) {
        fclose($handle);
        return;
      }

      $realColumns = array_filter($selectedColumns, function ($col) {
        static $dbColumns = null;
        $dbColumns = $dbColumns ?? Schema::getColumnListing($this->tableName);
        return in_array($col, $dbColumns);
      });

      $query = $this->getOrdersQueryProperty();

      $query->select($realColumns)->whereIn('id', $checked);

      $query->chunk(1000, function ($items) use ($handle, $selectedColumns) {
        foreach ($items as $item) {
          $row = [];

          foreach ($selectedColumns as $column) {
            $value = data_get($item, $column, '');

            if ($value instanceof Carbon) {
              $value = $value->setTimezone('Europe/Chisinau')->format('Y-m-d H:i:s');
            } elseif (is_string($value) && preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}/', $value)) {
              try {
                $value = Carbon::parse($value)->setTimezone('Europe/Chisinau')->format('Y-m-d H:i:s');
              } catch (\Exception $e) {
              }
            }

            $row[] = is_scalar($value) ? $value : json_encode($value);
          }

          fputcsv($handle, $row);
        }
      });

      fclose($handle);
    }, $filename, [
      'Content-Type' => 'text/csv; charset=UTF-8',
      'Content-Disposition' => "attachment; filename=\"$filename\"",
    ]);
  }
  public function updatingcsvimportdata($value)
  {
    ini_set('max_execution_time', 0);
    ini_set('memory_limit', '1024M');

    if (!$value->isValid() || $value->getClientOriginalExtension() !== 'csv') {
      session()->flash('notification', [
        'message' => 'Invalid CSV file.',
        'type' => 'error',
        'title' => 'Upload Failed'
      ]);
      return;
    }

    $filenameBase = 'import_' . $this->tableName . '_' . uniqid();
    $chunkSize = app()->bound('global_import_chunkSize')
      ? app('global_import_chunkSize')
      : 500;

    $chunkDir = storage_path('app/import_chunks/' . $filenameBase);

    if (!file_exists($chunkDir)) {
      mkdir($chunkDir, 0755, true);
    }

    $csv = fopen($value->getRealPath(), 'r');
    $header = fgetcsv($csv);
    $skip = ['created_at', 'updated_at'];
    $keepIndexes = array_filter(array_keys($header), fn($i) => !in_array($header[$i], $skip));
    $filteredHeader = array_intersect_key($header, array_flip($keepIndexes));

    $chunk = [];
    $chunkIndex = 0;
    $rowCount = 0;

    while ($row = fgetcsv($csv)) {
      $filteredRow = array_intersect_key($row, array_flip($keepIndexes));
      $chunk[] = $filteredRow;
      $rowCount++;

      if ($rowCount % $chunkSize === 0) {
        $chunkFile = "$chunkDir/chunk_$chunkIndex.csv";
        $this->writeChunk($chunkFile, $filteredHeader, $chunk);
        $chunkIndex++;
        $chunk = [];
      }
    }

    if (!empty($chunk)) {
      $chunkFile = "$chunkDir/chunk_$chunkIndex.csv";
      $this->writeChunk($chunkFile, $filteredHeader, $chunk);
    }

    fclose($csv);

    $job = CsvImportJob::create([
      'queue' => 'default',
      'name' => 'CSV Import for ' . $this->tableName,
      'type' => 'csv_import',
      'status' => 'pending',
      'meta' => [
        'table_name' => $this->tableName,
        'chunk_count' => $chunkIndex + 1,
        'base_path' => 'import_chunks/' . $filenameBase,
      ]
    ]);

    $allJob = AllJob::create([
      'name' => 'DynamicCsvImportJob',
      'type' => 'csv_import',
      'status' => 'pending',
      'payload' => [
        'table_name' => $this->tableName,
        'csv_import_job_id' => $job->id,
      ],
      'related_table' => $this->tableName,
    ]);

    DB::afterCommit(function () use ($job, $chunkIndex, $filenameBase, $allJob) {
      for ($i = 0; $i <= $chunkIndex; $i++) {
        $chunkPath = "import_chunks/{$filenameBase}/chunk_{$i}.csv";
        DynamicCsvImportJob::dispatch($this->tableName, $chunkPath, $job->id, $allJob->id);
      }
    });

    $this->importdata = false;

    session()->flash('notification', [
      'message' => 'Large CSV import started in background with multiple jobs.',
      'type' => 'success',
      'title' => 'Import Queued'
    ]);
  }

  protected function writeChunk(string $path, array $header, array $rows): void
  {
    $handle = fopen($path, 'w');
    fputcsv($handle, $header);
    foreach ($rows as $row) {
      fputcsv($handle, $row);
    }
    fclose($handle);
  }
  public function getavgvalues()
  {

    DB::transaction(function () {
      $allJob = AllJob::create([
        'name' => CalculateOrdersCost::class,
        'type' => 'calculate_orders_cost',
        'status' => 'pending',
        'payload' => [],
        'related_table' => 'orders',
      ]);

      DB::afterCommit(function () use ($allJob) {
        CalculateOrdersCost::dispatch($allJob->id);
      });
    });
    session()->flash('notification', [
      'message' => 'Orders cost calculation successfully started by job!',
      'type' => 'success',
      'title' => 'Import Queued'
    ]);
  }
}
