<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use App\Models\AllJob;
use App\Models\Country;
use Livewire\Component;
use App\Models\Listview;
use App\Models\CsvImportJob;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;

use App\Jobs\DynamicCsvImportJob;
use Illuminate\Support\Facades\DB;
use Database\Seeders\CountrySeeder;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;


class Countriestable extends Component
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
  public $itemidbeingremoved = null;
  public $columns;
  public $selectedColumns = [];
  public $rowindex = null;
  public $element = [];
  public $row = null;
  public $checked = [];
  public $selectPage = false;
  public $selectAll = false;

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


  public function render()
  {
    $activeId = $this->activelistview?->id;

    return view('livewire.countriestable', [
      'countries' => $this->countries,
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

  public function getCountriesProperty()
  {
    $query = Country::search($this->search);
    $query = $this->applyFilters($query);
    return $query->orderBy($this->listview['sort']['column'] ?? 'created_at', $this->listview['sort']['direction'] ?? 'desc')->paginate($this->loadAmount);
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
  // default functions

  public function expandRow($index)
  {
    if ($this->row  === null) {
      $this->row = $index;
    } elseif ($this->row != $index) {
      $this->row = $index;
    } else {
      $this->row = null;
    }
  }
  public function loadMore()
  {
    $this->loadAmount += 10;
  }
  public function swapSortDirection()
  {
    return $this->orderAsc === '1' ? '0' : '1';
  }
  public function edititem($index, $id)
  {
    $record = Country::find($id);
    $this->rowindex = $index;
    $this->element[$index]  = [
      'name' => $record->name,
      'iso_code' => $record->iso_code,
      'iso_code3' => $record->iso_code3,
      'phone_code' => $record->phone_code,
      'currency' => $record->currency,
      'status' => $record->status == 1 ? true : false,
    ];
  }
  public function saveitem($index, $id)
  {
    $record = $this->element[$index] ?? null;
    if (!$record) {
      session()->flash('notification', [
        'message' => 'Nothing was edited!',
        'type' => 'warning',
        'title' => 'Warning'
      ]);
      return;
    }
    $element = Country::find($id);
    $fillableFields = [
      'name',
      'iso_code',
      'iso_code3',
      'phone_code',
      'currency',
      'status'
    ];
    foreach ($fillableFields as $field) {
      if (array_key_exists($field, $record)) {
        $element->{$field} = $record[$field];
      }
    }
    $element->save();
    Cache::forget('active_countries');

    session()->flash('notification', [
      'message' => 'Record edited successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);

    $this->rowindex = null;
    $this->element = [];
  }
  public function cancelitem()
  {
    $this->rowindex = null;
    $this->element = [];
  }
  public function addCountriesIfNotExist()
  {
    $element = CountrySeeder::labels();
    foreach ($element as $elem) {
      $exists = DB::table('countries')
        ->where('name', $elem['name'])
        ->exists();

      if (!$exists) {
        DB::table('countries')->insert([
          'name' => $elem['name'],
          'iso_code' => $elem['iso_code'],
          'iso_code3' => $elem['iso_code3'],
          'phone_code' => $elem['phone_code'],
          'currency' => $elem['currency'],
          'status' => true,
          'created_at' => now(),
          'updated_at' => now()
        ]);
      }
    }
    Cache::forget('active_countries');

    session()->flash('notification', [
      'message' => 'Countries update successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }
  public function selectAll()
  {
    $this->selectAll = true;
    $this->checked = $this->countries->pluck('id')->map(fn($item) => (string) $item)->toArray();
  }
  public function isChecked($id)
  {
    return in_array($id, $this->checked);
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

      $query = $this->getQueryBuilder();

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
  public function getQueryBuilder()
  {
    $query = Country::search($this->search);
    $query = $this->applyFilters($query);
    return $query->orderBy($this->listview['sort']['column'] ?? 'created_at', $this->listview['sort']['direction'] ?? 'desc');
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
}
