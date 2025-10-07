<?php

namespace App\Http\Livewire;

use Imagick;
use ImagickPixel;
use Carbon\Carbon;
use App\Models\AllJob;

use App\Models\Product;
use Livewire\Component;
use App\Models\Category;
use App\Models\Listview;
use Illuminate\Support\Str;
use App\Models\CsvImportJob;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Store_Settings;
use App\Jobs\RefreshPricesChunkJob;

use Illuminate\Validation\Rule;

use App\Jobs\DynamicCsvImportJob;
use App\Models\Article;
use App\Models\ArticleCategory;
use Database\Seeders\StoreSeeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Response;
use App\Models\ProductReviews;

class Storesettingstable extends Component
{

  use WithPagination;
  use WithFileUploads;
  // importdata
  public $importdata = false;
  public $csvimportdata;

  public $loadAmount;
  public $search = '';
  public $orderBy;
  public $orderAsc;
  public $itemidbeingremoved = null;
  public $columns;
  public $selectedColumns = [];
  public $editindex = null;
  public $settings = [];
  public $row = null;
  public $changelogodark = false;
  public $changelogolight = false;
  public $changefavicon = false;
  public $external = false;
  public $media;
  public $mediaurl =  null;
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

    return view('livewire.storesettingstable', [
      'storesettings' => $this->storesettings,
      'listviews' => $this->listviews->filter(function ($view) use ($activeId) {
        return $view->id !== $activeId;
      }),

    ]);
  }


  public function getStoresettingsProperty()
  {
    return $this->storesettingsQuery->paginate($this->loadAmount);
  }

  public function getStoresettingsQueryProperty()
  {
    $query = Store_Settings::search($this->search);
    $query = $this->applyFilters($query);
    return $query->orderBy($this->listview['sort']['column'] ?? 'created_at', $this->listview['sort']['direction'] ?? 'desc');
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
  // default functions
  public function updatelocal()
  {
    $filespath = 'images/store/svg/';

    $this->validate([
      'media' => 'file|max:2048|mimes:svg,ico,png',
    ]);

    if (!$this->media) {
      return;
    }

    $fileExtension = strtolower($this->media->getClientOriginalExtension());

    $allowedExtensions = ['svg'];
    if (!in_array($fileExtension, $allowedExtensions)) {
      return session()->flash('notification', [
        'message' => 'File type not allowed! Only SVG is allowed!',
        'type' => 'warning',
        'title' => 'Warning'
      ]);
    }

    if ($fileExtension === 'svg') {
      $svgContent = file_get_contents($this->media->getRealPath());
      $svg = simplexml_load_string($svgContent);

      if ($svg === false) {
        return session()->flash('notification', [
          'message' => 'Invalid SVG format!',
          'type' => 'warning',
          'title' => 'Warning'
        ]);
      }

      if ($this->changefavicon) {
        $sizes = [
          'apple-touch-icon.png' => [180, 180],
          'favicon-16x16.png' => [16, 16],
          'favicon-32x32.png' => [32, 32],
          'favicon-48x48.png' => [48, 48],
          'favicon.ico' => [48, 48],
          'favicon.svg' => [48, 48],
          'safari-pinned-tab.svg' => [48, 48],
        ];

        foreach ($sizes as $filename => [$width, $height]) {
          $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

          if ($extension === 'svg') {
            $svgCopy = clone $svg;
            $svgCopy['width'] = $width;
            $svgCopy['height'] = $height;
            Storage::disk('public_upload')->put($filespath . $filename, $svgCopy->asXML());
          } else {
            try {
              $imagick = new Imagick();
              $imagick->setBackgroundColor(new ImagickPixel('transparent'));
              $imagick->readImage($this->media->getRealPath());
              $imagick->setImageFormat($extension === 'ico' ? 'ico' : 'png');
              $imagick->resizeImage($width, $height, Imagick::FILTER_LANCZOS, 1);

              Storage::disk('public_upload')->put($filespath . $filename, $imagick->getImageBlob());

              $imagick->clear();
              $imagick->destroy();
            } catch (\Exception $e) {
              return session()->flash('notification', [
                'message' => 'Imagick failed: ' . $e->getMessage(),
                'type' => 'danger',
                'title' => 'Error'
              ]);
            }
          }
        }
      } else {
        $svg['width'] = '300';
        unset($svg['height']);

        $name = $this->changelogodark
          ? 'logo-dark.svg'
          : ($this->changelogolight ? 'logo-light.svg' : 'logo.svg');

        Storage::disk('public_upload')->put($filespath . $name, $svg->asXML());
      }
    }

    // Reset UI flags
    $this->media = null;
    $this->external = false;
    $this->changelogodark = false;
    $this->changelogolight = false;
    $this->changefavicon = false;

    session()->flash('notification', [
      'message' => 'Image processed successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }
  public function updateexternal()
  {
    $filespath = 'images/store/svg/';

    $urlComponents = parse_url($this->mediaurl);
    $urlWithoutParams = $urlComponents['scheme'] . '://' . $urlComponents['host'] . $urlComponents['path'];
    $this->mediaurl = $urlWithoutParams;

    $fileExtension = strtolower(pathinfo($this->mediaurl, PATHINFO_EXTENSION));
    if ($fileExtension !== 'svg') {
      return session()->flash('notification', [
        'message' => 'File type not allowed! Only SVG is allowed!',
        'type' => 'warning',
        'title' => 'Warning'
      ]);
    }

    $fileContent = @file_get_contents($this->mediaurl);
    if (!$fileContent) {
      return session()->flash('notification', [
        'message' => 'Could not fetch the file from URL!',
        'type' => 'warning',
        'title' => 'Warning'
      ]);
    }

    $svg = simplexml_load_string($fileContent);
    if ($svg === false) {
      return session()->flash('notification', [
        'message' => 'Invalid SVG format!',
        'type' => 'warning',
        'title' => 'Warning'
      ]);
    }

    if ($this->changefavicon) {
      $sizes = [
        'apple-touch-icon.png' => [180, 180],
        'favicon-16x16.png' => [16, 16],
        'favicon-32x32.png' => [32, 32],
        'favicon-48x48.png' => [48, 48],
        'favicon.ico' => [48, 48],
        'favicon.svg' => [48, 48],
        'safari-pinned-tab.svg' => [48, 48],
      ];

      foreach ($sizes as $filename => [$width, $height]) {
        if (Str::endsWith($filename, '.svg')) {
          $svgCopy = clone $svg;
          $svgCopy['width'] = $width;
          $svgCopy['height'] = $height;
          Storage::disk('public_upload')->put($filespath . $filename, $svgCopy->asXML());
        } else {
          try {
            $imagick = new Imagick();
            $imagick->setBackgroundColor(new ImagickPixel('transparent'));

            // Load SVG directly from string content
            $imagick->readImageBlob($fileContent);

            $imagick->setImageFormat(pathinfo($filename, PATHINFO_EXTENSION));
            $imagick->resizeImage($width, $height, Imagick::FILTER_LANCZOS, 1);

            Storage::disk('public_upload')->put($filespath . $filename, $imagick->getImageBlob());

            $imagick->clear();
            $imagick->destroy();
          } catch (\Exception $e) {
            return session()->flash('notification', [
              'message' => 'Imagick failed: ' . $e->getMessage(),
              'type' => 'danger',
              'title' => 'Error'
            ]);
          }
        }
      }

      session()->flash('notification', [
        'message' => 'All favicon assets updated!',
        'type' => 'success',
        'title' => 'Success'
      ]);
    } else {
      $name = 'logo.svg';
      if ($this->changelogodark) {
        $name = 'logo-dark.svg';
      } elseif ($this->changelogolight) {
        $name = 'logo-light.svg';
      }

      $svg['width'] = '300';
      unset($svg['height']);

      Storage::disk('public_upload')->put($filespath . $name, $svg->asXML());

      session()->flash('notification', [
        'message' => 'Logo updated successfully!',
        'type' => 'success',
        'title' => 'Success'
      ]);
    }

    // Reset component state
    $this->mediaurl = null;
    $this->external = false;
    $this->changelogodark = false;
    $this->changelogolight = false;
    $this->changefavicon = false;
  }
  public function closeModalLogo()
  {
    $this->changelogodark = false;
    $this->changelogolight = false;
    $this->changefavicon = false;
    $this->external = false;
  }
  public function expandRow($index)
  {
    if ($this->editindex === $index) {
      return;
    } else {

      if ($this->row  === null) {
        $this->row = $index;
      } elseif ($this->row != $index) {
        $this->row = $index;
      } else {
        $this->row = null;
      }
    }
  }
  public function seedreviews()
  {
    $acronims = [
      'JD',
      'AM',
      'CR',
      'LS',
      'MK',
      'PT',
      'RB',
      'SN',
      'VL',
      'XT',
      'AN',
      'BG',
      'CZ',
      'DK',
      'EV',
      'FP',
      'GH',
      'HK',
      'IL',
      'JM'
    ];

    $comments = [
      'Produs excelent, foarte mulțumit!',
      'Exact ce aveam nevoie, funcționează perfect.',
      'Calitate foarte bună și livrare rapidă.',
      'Raport calitate-preț foarte bun.',
      'A depășit așteptările mele.',
      'Produs bun, îl recomand.',
      'Sunt foarte încântat de această achiziție.',
      'Construcție solidă, se simte premium.',
      'Livrare rapidă și ambalaj de calitate.',
      'Merită cumpărat din nou.',
      'Funcționează impecabil, recomand cu încredere.',
      'Servicii excelente, produsul conform descrierii.',
      'Preț corect pentru ceea ce oferă.',
      'Foarte practic și ușor de folosit.',
      'Un produs de încredere, recomand oricui.'
    ];

    $prods = Product::where('active', true)
      ->where('start_date', '<=', now()->format('Y-m-d'))
      ->where('end_date', '>=', now()->format('Y-m-d'))
      ->get();

    foreach ($prods as $product) {
      if (!$product->reviews->first()) {
        $acronim = $acronims[array_rand($acronims)];
        $comm = $comments[array_rand($comments)];
        $slug = strtolower($acronim) . '-' . rand(1000, 9999);

        ProductReviews::create([
          'product_id' => $product->id,
          'acronim'    => $slug,
          'score'      => rand(4, 5),
          'comment'    => 'Produs excelent, foarte mulțumit!',
          'approved'   => true
        ]);
      }
    }

    session()->flash('notification', [
      'message' => 'Reviews added successfully!',
      'type'    => 'success',
      'title'   => 'Success'
    ]);
  }

  public function actualizeaza()
  {
    Artisan::call('cache:clear');
    Artisan::call('clear-compiled');
    Artisan::call('view:clear');
    Artisan::call('config:cache');
    Artisan::call('config:clear');
    Artisan::call('event:clear');
    Artisan::call('queue:clear');
    Artisan::call('optimize:clear');
    Artisan::call('migrate');

    exec('rm -rf bootstrap/cache/*.php');

    Cache::forget('global_variables');
    Cache::forget('global_statuses');
    Cache::forget('global_payments');
    Cache::forget('global_scripts');
    Cache::forget('static_pages');

    session()->flash('notification', [
      'message' => 'Website is updated!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }
  public function loadMore()
  {
    $this->loadAmount += 10;
  }
  public function swapSortDirection()
  {
    return $this->orderAsc === '1' ? '0' : '1';
  }
  public function confirmItemRemoval($id)
  {
    $this->itemidbeingremoved = $id;
    $this->dispatchBrowserEvent('show-delete-modal');
  }
  public function confirmItemsRemovalmultiple()
  {
    $this->dispatchBrowserEvent('show-delete-modal-multiple');
  }
  // public function isChecked($id)
  // {
  //   return in_array($id, $this->checked);
  // }
  public function edititem($index, $id)
  {
    $record = Store_Settings::find($id);
    $this->editindex = $index;
    $this->row = $index;
    $this->settings = [
      $index . '.value' => $record->value,
    ];
  }
  public function saveitem($index, $id)
  {
    $update = $this->settings[$index] ?? NULL;
    if (!is_null($update)) {
      $item = Store_Settings::find($id);

      if (array_key_exists('value', $update)) {
        if ($item->parameter == 'numberformat_element') {
          if ($update['value'] != '.' && $update['value'] != ',') {
            session()->flash('notification', [
              'message' => 'Value must be . or ,',
              'type' => 'warning',
            ]);
            return;
          }
        }
        $item->value = $update['value'];
      }
      $item->save();
      if ($item->parameter == 'app_debug') {
        if ($item->value == 'true') {
          $envPath = base_path('.env');
          $content = File::get($envPath);

          $content = preg_replace('/^APP_DEBUG=.*/m', "APP_DEBUG=true", $content);

          File::put($envPath, $content);
        } else {
          $envPath = base_path('.env');
          $content = File::get($envPath);

          $content = preg_replace('/^APP_DEBUG=.*/m', "APP_DEBUG=false", $content);

          File::put($envPath, $content);
        }
      }
      if ($item->parameter == 'mailserver_mail') {
        if ($item->value != '') {
          $envPath = base_path('.env');
          $content = File::get($envPath);

          $content = preg_replace('/^MAIL_USERNAME=.*/m', "MAIL_USERNAME=" . $item->value, $content);

          File::put($envPath, $content);
        }
      }
      if ($item->parameter == 'mailserver_from_mail') {
        if ($item->value != '') {
          $envPath = base_path('.env');
          $content = File::get($envPath);

          $content = preg_replace('/^MAIL_FROM_ADDRESS=.*/m', "MAIL_FROM_ADDRESS=" . $item->value, $content);

          File::put($envPath, $content);
        }
      }
      if ($item->parameter == 'mailserver_password') {
        if ($item->value != '') {
          $envPath = base_path('.env');
          $content = File::get($envPath);

          // Replace the MAIL_PASSWORD line with the new value wrapped in double quotes
          $content = preg_replace(
            '/^MAIL_PASSWORD=.*/m',
            'MAIL_PASSWORD="' . addslashes($item->value) . '"', // Escape quotes or special characters in the password
            $content
          );

          File::put($envPath, $content);
        }
      }
      if ($item->parameter == 'mailserver_from_name') {
        if ($item->value != '') {
          $envPath = base_path('.env');
          $content = File::get($envPath);

          // Replace the MAIL_PASSWORD line with the new value wrapped in double quotes
          $content = preg_replace(
            '/^MAIL_FROM_NAME=.*/m',
            'MAIL_FROM_NAME="' . addslashes($item->value) . '"', // Escape quotes or special characters in the password
            $content
          );

          File::put($envPath, $content);
        }
      }
      if ($item->parameter == 'cache_data') {
        if ($item->value != 'true') {
          Cache::forget('cached_products');
          Cache::forget('cached_categories');
        }
      }
      if ($item->parameter == 'robots_txt') {
        if (array_key_exists('value', $update)) {
          if ($item->value = !'') {
            $filepath = public_path('robots.txt');
            $content = str_replace('<br>', "\r\n", $update['value'], $content);
            File::put($filepath, $content);
            chmod($filepath, 0755);
          }
        }
      }
      if ($item->parameter == 'time_zone') {
        if (preg_match('/^[-+]?([0-9]|1[0-2])$/', $item->value)) {
          $envPath = base_path('.env');
          $content = File::get($envPath);
          if (strpos($item->value, '-') !== false) {
            $adjustedValue = str_replace('-', '+', $item->value);
          } elseif (strpos($item->value, '+') !== false) {
            $adjustedValue = str_replace('+', '-', $item->value);
          }
          $content = preg_replace('/^APP_TIMEZONE=.*/m', "APP_TIMEZONE=Etc/GMT" . $adjustedValue, $content);
          File::put($envPath, $content);
        }
      }
      if ($item->parameter == 'lang') {
        if (array_key_exists('value', $update)) {
          if ($update['value'] = !'') {
            $envPath = base_path('.env');
            $content = File::get($envPath);
            $content = preg_replace('/^APP_LANG=.*/m', "APP_LANG=" . $item->value, $content);
            File::put($envPath, $content);
          }
        }
      }
      if ($item->parameter == 'locale') {
        if (array_key_exists('value', $update)) {
          if ($update['value'] = !'') {
            $envPath = base_path('.env');
            $content = File::get($envPath);
            $content = preg_replace('/^APP_LOCALE=.*/m', "APP_LOCALE=" . $item->value, $content);
            File::put($envPath, $content);
          }
        }
      }
      Cache::forget('global_variables');
      session()->flash('notification', [
        'message' => 'Record edited successfully!',
        'type' => 'success',
        'title' => 'Success'
      ]);
    } else {
      session()->flash('notification', [
        'message' => 'Nothing chnaged!',
        'type' => 'success',
        'title' => 'Success'
      ]);
    }
    $this->settings = [];
    $this->editindex = null;
  }
  public function cancelitem()
  {
    $this->editindex = null;
    $this->settings = [];
  }
  public function refreshprices()
  {

    DB::transaction(function () {
      $allJob = AllJob::create([
        'name' => RefreshPricesChunkJob::class,
        'type' => 'price_refresh',
        'status' => 'pending',
        'payload' => [], // You can include model counts or other context if needed
        'related_table' => 'products',
      ]);

      DB::afterCommit(function () use ($allJob) {
        RefreshPricesChunkJob::dispatch($allJob->id);
      });
    });

    session()->flash('notification', [
      'message' => 'Prices corrected successfully started by job!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }

  public function initializeSitemap()
  {
    $filePath = public_path('sitemap.xml');

    return $this->createNewSitemap($filePath);
  }
  private function createNewSitemap($filePath)
  {
    $xmlString = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL . '</urlset>';
    file_put_contents($filePath, $xmlString);
    return simplexml_load_string($xmlString);
  }
  public function sitemap()
  {
    $filePath = public_path('sitemap.xml');
    $xml = $this->initializeSitemap();

    // Homepage
    $url = $xml->addChild('url');
    $url->addChild('loc', url('/'));
    $url->addChild('lastmod', now()->toAtomString());
    $url->addChild('priority', '1.0');
    // search
    $url = $xml->addChild('url');
    $url->addChild('loc', url('/search'));
    $url->addChild('lastmod', now()->toAtomString());
    $url->addChild('priority', '0.9');
    $staticpages = collect(app('static_pages'))->values();

    // Static pages
    $pages = [];
    foreach ($staticpages as $staticpage) {
      $pages[$staticpage->route] = '0.5';
    }

    foreach ($pages as $page => $priority) {
      $url = $xml->addChild('url');
      $url->addChild('loc', url($page));
      $url->addChild('lastmod', now()->toAtomString());
      $url->addChild('priority', $priority);
    }

    // Active Articles
    $articles = Article::where('active', true)
      ->where('start_date', '<=', Carbon::now())->where('end_date', '>=', Carbon::now())
      ->get();

    foreach ($articles as $article) {
      $url = $xml->addChild('url');
      $articleUrl = route('article', ['article' => $article->seo_id ?? $article->id]);
      $url->addChild('loc', htmlspecialchars($articleUrl));
      $url->addChild('lastmod', now()->toAtomString());
      $url->addChild('priority', '0.8');
    }

    // Active Products
    $products = Product::where('active', true)
      ->where('type', '!=', 'parent')
      ->where('start_date', '<=', Carbon::now())->where('end_date', '>=', Carbon::now())
      ->get();

    foreach ($products as $product) {
      $url = $xml->addChild('url');
      $productUrl = route('product', ['product' => $product->seo_id ?? $product->id]);
      $url->addChild('loc', htmlspecialchars($productUrl));
      $url->addChild('lastmod', now()->toAtomString());
      $url->addChild('priority', '0.8');
    }

    // Blog main page and categories
    $this->generateBlogCategoryPages($xml, null);
    // All other categories
    $blogcategories = ArticleCategory::where('active', true)
      ->where('start_date', '<=', Carbon::now())->where('end_date', '>=', Carbon::now())
      ->get();

    foreach ($blogcategories as $category) {

      $this->generateBlogCategoryPages($xml, $category);
    }


    // Global default category
    if (app()->has('global_default_category') && app('global_default_category') != "") {
      $defaultCategory = Category::find(app('global_default_category'));
      if ($defaultCategory) {
        $this->generateCategoryPages($xml, $defaultCategory, true);
      }
    }

    // All other categories
    $categories = Category::where('active', true)
      ->where('start_date', '<=', Carbon::now())->where('end_date', '>=', Carbon::now())
      ->get();

    foreach ($categories as $category) {
      if (isset($defaultCategory) && $category->id === $defaultCategory->id) {
        continue;
      }
      $this->generateCategoryPages($xml, $category, false);
    }

    $xml->asXML($filePath);
    chmod($filePath, 0755);

    session()->flash('notification', [
      'message' => 'Sitemap generated successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }
  public function isChecked($id)
  {
    return in_array($id, $this->checked);
  }
  public function selectAll()
  {
    $this->selectAll = true;
    $this->checked = $this->storesettingsQuery->pluck('id')->map(fn($item) => (string) $item)->toArray();
  }
  public function updatedSelectPage($value)
  {
    if ($value) {
      $this->checked = $this->storesettings->pluck('id')->map(fn($item) => (string) $item)->toArray();
    } else {
      $this->checked = [];
    }
  }
  public function updatedChecked()
  {
    $this->selectPage = false;
  }

  /**
   *
   * @param SimpleXMLElement $xml
   * @param Category $category
   * @param bool $isDefaultCategory
   * @return void
   */
  private function generateCategoryPages(&$xml, $category, $isDefaultCategory = false)
  {
    $productsCount = $category->product_categories()
      ->whereHas('product', function ($query) {
        $query->where('active', true)
          ->where('start_date', '<=', Carbon::now())->where('end_date', '>=', Carbon::now());
      })
      ->count();

    $limit = app('global_limit_load');
    $totalPages = ceil($productsCount / $limit);

    for ($page = 1; $page <= $totalPages; $page++) {
      $url = $xml->addChild('url');
      if (app()->has('global_default_category') && app('global_default_category') != "" && $isDefaultCategory) {
        $categoryUrl = route('products');
      } else {

        $categoryUrl = route('products', [
          'categorySlug' => $category->seo_id ?? $category->id
        ]);
      }

      if ($page > 1) {

        if ($page === 1) {
          $categoryUrl .= $page;
        } else {
          $categoryUrl .= "?page=" . $page;
        }
      }

      $url->addChild('loc', htmlspecialchars($categoryUrl));
      $url->addChild('lastmod', now()->toAtomString());
      $url->addChild('priority', $isDefaultCategory ? '0.9' : '0.8');
    }
  }
  private function generateBlogCategoryPages(&$xml, $category)
  {
    if ($category) {

      $articlesCount = $category->article_categories()
        ->whereHas('article', function ($query) {
          $query->where('active', true)
            ->where('start_date', '<=', Carbon::now())->where('end_date', '>=', Carbon::now());
        })
        ->count();
    } else {
      $articlesCount = Article::where('active', true)
        ->where('start_date', '<=', Carbon::now())->where('end_date', '>=', Carbon::now())->count();
    }

    $limit = app('global_articles_limit_load');
    $totalPages = ceil($articlesCount / $limit);

    for ($page = 1; $page <= $totalPages; $page++) {
      $url = $xml->addChild('url');
      if ($category) {
        $categoryUrl = route('blog', [
          'categorySlug' => $category->seo_id ?? $category->id
        ]);
      } else {
        $categoryUrl = route('blog');
      }

      if ($page > 1) {

        if ($page === 1) {
          $categoryUrl .= $page;
        } else {
          $categoryUrl .= "?page=" . $page;
        }
      }

      $url->addChild('loc', htmlspecialchars($categoryUrl));
      $url->addChild('lastmod', now()->toAtomString());
      $url->addChild('priority', $category ? '0.9' : '0.8');
    }
  }


  public function refreshfilters()
  {
    Cache::forget('cached_specifications');
    session()->flash('notification', [
      'message' => 'Fileters update successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }
  public function addSettingsIfNotExist()
  {
    $settings = StoreSeeder::settings();
    foreach ($settings as $setting) {
      $exists = DB::table('store__settings')
        ->where('parameter', $setting['parameter'])
        ->exists();

      if (!$exists) {
        DB::table('store__settings')->insert([
          'parameter' => $setting['parameter'],
          'value' => $setting['value'],
          'description' => $setting['description'],
          'createdby' => 'admin',
          'lastmodifiedby' => 'admin',
          'created_at' => $setting['created_at'],
          'updated_at' => $setting['updated_at']
        ]);
      }
    }
    Cache::forget('global_variables');
    session()->flash('notification', [
      'message' => 'Parameters update successfully!',
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

      $query = $this->getStoresettingsQueryProperty();

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
}
