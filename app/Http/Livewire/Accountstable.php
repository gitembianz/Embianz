<?php

namespace App\Http\Livewire;

use App\Models\Order;
use App\Models\Account;
use App\Models\Address;
use Livewire\Component;
use App\Models\Listview;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;


class Accountstable extends Component
{
  use WithPagination;
  public $loadAmount;
  public $search = '';
  public $orderBy;
  public $orderAsc;
  public $checked = [];
  public $selectPage = false;
  public $selectAll = false;
  public $idbeingremoved = null;
  public $selectedColumns = [];
  public $columns;
  public $row = null;
  public $single = false;
  public $multiple = false;

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

    return view('livewire.accountstable', [
      'accounts' => $this->accounts,
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
  public function getAccountsProperty()
  {
    return $this->accountsQuery->paginate($this->loadAmount);
  }

  public function getAccountsQueryProperty()
  {
    $query = Account::search($this->search);
    $query = $this->applyFilters($query);
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
  public function updatedSelectPage($value)
  {
    if ($value) {
      $this->checked = $this->accounts->pluck('id')->map(fn($item) => (string) $item)->toArray();
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
  public function selectAll()
  {
    $this->selectAll = true;
    $this->checked = $this->accountsQuery->pluck('id')->map(fn($item) => (string) $item)->toArray();
  }
  public function loadMore()
  {
    $this->loadAmount += 10;
  }
  public function deleteRecords()
  {
    $accounts = Account::whereKey($this->checked)->get();
    foreach ($accounts as $account) {
      $del = Account::find($account->id);
      $adresses = Address::where('account_id', $del->id)->get();
      if ($adresses != NULL) {
        foreach ($adresses as $adress) {
          $adress->delete();
        }
      }
      $orders = Order::where('account_id', $account->id)->get();
      if ($orders != NULL) {
        foreach ($orders as $order) {
          $order->account_id = null;
          $order->save();
        }
      }

      $del->delete();
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
  public function deleteSingleRecord()
  {
    $account = Account::findOrFail($this->idbeingremoved);
    $adresses = Address::where('account_id', $account->id)->get();
    if ($adresses != NULL) {
      foreach ($adresses as $adress) {
        $adress->delete();
      }
    }
    $orders = Order::where('account_id', $account->id)->get();
    if ($orders != NULL) {
      foreach ($orders as $order) {
        $order->account_id = null;
        $order->save();
      }
    }
    $account->delete();
    $this->checked = array_diff($this->checked, [$this->idbeingremoved]);
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
  public function isChecked($id)
  {
    return in_array($id, $this->checked);
  }
}
