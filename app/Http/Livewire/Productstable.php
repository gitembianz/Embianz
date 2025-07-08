<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use App\Models\Job;
use App\Models\Media;
use App\Models\JobLog;
use App\Models\Product;
use Livewire\Component;
use App\Models\Listview;
use App\Models\Wishlist;
use App\Models\Cart_Item;
use App\Models\ProductCost;
use App\Models\Product_Spec;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use App\Models\PricelistEntries;
use App\Models\Related_Products;

use App\Jobs\DynamicCsvImportJob;
use Illuminate\Support\Facades\DB;
use App\Models\CsvImportJob;
use Livewire\WithFileUploads;


use App\Models\Products_categories;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use Intervention\Image\Facades\Image;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use App\Models\ProductReviews as ModelsProductReviews;

class Productstable extends Component
{
  use WithPagination;
  use WithFileUploads;

  public $loadAmount;
  public $search = '';
  public $orderBy;
  public $orderAsc;
  public $checked = [];
  public $selectPage = false;
  public $selectAll = false;
  public $idbeingremoved = null;
  public $columns;
  public $selectedColumns = [];
  public $row = null;
  public $single = false;
  public $multiple = false;
  public $uploadcsv = false;
  public $addlistview = false;
  public $csvFile;
  public $relation = false;
  public $editlistview = false;
  public $tableName;
  public $activelistview;
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
    'csvFile' => 'required|mimes:csv,txt',
    'csvimportdata' => 'required|mimes:csv,txt',
  ];

  // importdata
  public $importdata = false;
  public $csvimportdata;



  public function render()
  {
    $activeId = $this->activelistview?->id;

    return view('livewire.productstable', [
      'products' => $this->products,
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

    $quantityIndex = array_search('quantity', $this->columns);
    if ($quantityIndex !== false) {
      array_splice($this->columns, $quantityIndex + 1, 0, ['interim_quantity', 'quantity_ordered']);
    }

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
  public function getProductsProperty()
  {
    return $this->productsQuery->paginate($this->loadAmount);
  }
  public function getProductsQueryProperty()
  {
    $query = Product::search($this->search);

    $query = $this->applyFilters($query);

    return $query
      ->withCount([
        'orders_item as interim_quantity' => function ($query) {
          $query->whereHas('order', function ($q) {
            $q->where('status_id', 31);
          })
            ->select(DB::raw('
                    CASE
                        WHEN COUNT(*) = 0 THEN products.quantity
                        ELSE SUM(quantity) + products.quantity
                    END
                '));
        },
        'order_suppliers as quantity_ordered' => function ($query) {
          $query->whereHas('order', function ($q) {
            $q->where('status', '!=', 'closed');
          })->select(DB::raw('SUM(quantity)'));
        },
      ])
      ->orderBy($this->listview['sort']['column'] ?? 'created_at', $this->listview['sort']['direction'] ?? 'desc');
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
  public function updatingcsvFile($value)
  {
    ini_set('max_execution_time', 300);
    ini_set('memory_limit', '512M');

    $file = fopen($value->getRealPath(), 'r');
    // Skip the header row
    $header = fgetcsv($file);
    while ($row = fgetcsv($file)) {
      $this->processRow($row);
    }

    fclose($file);
    $this->uploadcsv = false;
    session()->flash('notification', [
      'message' => 'Media added successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }
  public function processRow($row)
  {
    $id = $row[0]; // id
    $mediaLink = $row[1]; // media link

    $product = Product::find($id);

    if ($product) {
      $productType = class_basename(get_class($product));
      //check for directory
      $filespath = 'media/' . $productType . '/';
      if (!File::exists($filespath)) {
        File::makeDirectory($filespath, 0755, true);
      }
      if (!File::exists($filespath . $product->id)) {
        File::makeDirectory($filespath . $product->id, 0755, true);
      }
      $path = $filespath . $product->id . "/";
      $urlComponents = parse_url($mediaLink);

      $urlWithoutParams = $urlComponents['scheme'] . '://' . $urlComponents['host'] . $urlComponents['path'];
      $mediaLink = $urlWithoutParams;
      $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'svg'];
      $fileExtension = strtolower(pathinfo($mediaLink, PATHINFO_EXTENSION));

      if (!in_array($fileExtension, $allowedExtensions)) {
        return;
      }
      $fileContent = file_get_contents($mediaLink);
      if ($fileContent == false) {
        return;
      }
      $imageInfo = getimagesizefromstring($fileContent);
      if (app()->has('global_auto_webp') &&  app('global_auto_webp') === 'true') {
        $image = Image::make($fileContent);
        $webpContent = $image->encode('webp')->__toString();
        $fileExtension = 'webp';
        $name = strtolower(preg_replace('/\s+/', '-', $product->name));
        if (file_exists($path . $name)) {
          $j = 1;
          while (file_exists($path . $product->name . '(' . $j . ').' . $fileExtension)) {
            $j++;
          }
          $name = $product->name . '(' . $j . ').' . $fileExtension;
        }
        Storage::disk('public_upload')->put($path . $name, $webpContent);
      } else {
        $fileExtension = image_type_to_extension($imageInfo[2], false);
        $name = $product->name . '.' . $fileExtension;
        if (file_exists($path . $name)) {
          $j = 1;
          while (file_exists($path . $product->name . '(' . $j . ').' . $fileExtension)) {
            $j++;
          }
          $name = $product->name . '(' . $j . ').' . $fileExtension;
        }
        Storage::disk('public_upload')->put($path . $name, $fileContent);
      }

      $isoriginal = $product->media()->where('type', 'original')->where('sequence', '1')->first();
      if ($isoriginal) {
        $isoriginal->delete();
      }
      $media = new Media();
      $media->name = $name;
      $media->extension = $fileExtension;
      $media->width = $imageInfo[0];
      $media->height =  $imageInfo[1];
      $media->size = strlen($fileContent);
      $media->type = 'original';
      $media->sequence = 1;
      $media->path = $path;
      $media->createdby = Auth::user()->name;
      $media->lastmodifiedby = Auth::user()->name;
      $media->save();
      $product->media()->attach($media->id);

      $filePath = $path . $name;
      $file = Storage::disk('public_upload')->get($filePath);

      $ismin = $product->media()->where('type', 'min')->first();

      if (!$ismin) {
        $this->resizeImage(
          $file,
          $path,
          70,
          'min',
          $name,
          $fileExtension,
          true,
          1,
          $product
        );
      } else {
        $oldPath = $ismin->path . $ismin->name;
        if (File::exists($oldPath)) {
          File::delete($oldPath);
        }

        $resizedImage = Image::make($file)
          ->resize(70, 70, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
          });

        $newPath = $ismin->path . "resized70_" . $name;
        $resizedImage->encode('webp')->save($newPath);
        $ismin->path = $ismin->path;
        $ismin->name = "resized70_" . $name;
        $ismin->sequence = 1;
        $ismin->extension = $fileExtension;
        $ismin->width = $resizedImage->width();
        $ismin->height = $resizedImage->height();
        $ismin->size = File::size($newPath);
        $ismin->lastmodifiedby = Auth::user()->name;
        $ismin->save();
      }

      $ismaim = $product->media()->where('type', 'main')->first();
      if (!$ismaim) {
        $this->resizeImage($file, $path, 300, 'main', $name, $fileExtension, true, 1, $product);
      } else {
        $oldPath = $ismaim->path . $ismaim->name;
        if (File::exists($oldPath)) {
          File::delete($oldPath);
        }

        $resizedImage = Image::make($file)
          ->resize(300, 300, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
          });

        $newPath = $ismaim->path . "resized300_" . $name;
        $resizedImage->encode('webp')->save($newPath);
        $ismaim->path = $ismaim->path;
        $ismaim->name = "resized300_" . $name;
        $ismaim->sequence = 1;
        $ismaim->extension = $fileExtension;
        $ismaim->width = $resizedImage->width();
        $ismaim->height = $resizedImage->height();
        $ismaim->size = File::size($newPath);
        $ismaim->lastmodifiedby = Auth::user()->name;
        $ismaim->save();
      }

      $isfull = $product->media()->where('type', 'full')->where('sequence', '1')->first();
      if ($isfull) {
        $isfull->delete();
      }
      $this->resizeImage($file, $path, 640, 'full', $name, $fileExtension, true, 1, $product);
      return;
    } else {
      return;
    }
  }
  private function resizeImage($file, $path, $size, $type, $name, $extension, $external, $sequence, $product)
  {
    if ($external) {
      $resizedImage = Image::make($file)
        ->resize($size, $size, function ($constraint) {
          $constraint->aspectRatio();
          $constraint->upsize();
        });
    } else {

      $resizedImage = Image::make($file->getRealPath())
        ->resize($size, $size, function ($constraint) {
          $constraint->aspectRatio();
          $constraint->upsize();
        });
    }

    $resizedImage->encode('webp')->save($path . "resized{$size}_" . $name);

    $resizedMedia = new Media();
    $resizedMedia->path = $path;
    $resizedMedia->name = "resized{$size}_" . $name;
    $resizedMedia->sequence = $sequence;
    $resizedMedia->extension = $extension;
    $resizedMedia->type = $type;
    $resizedMedia->width = $resizedImage->width();
    $resizedMedia->height = $resizedImage->height();
    $resizedMedia->size = File::size($path . "resized{$size}_" . $name);
    $resizedMedia->createdby = Auth::user()->name;
    $resizedMedia->lastmodifiedby = Auth::user()->name;
    $resizedMedia->save();

    $product->media()->attach($resizedMedia->id);
  }
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
  public function updatedSelectPage($value)
  {
    if ($value) {
      $this->checked = $this->products->pluck('id')->map(fn($item) => (string) $item)->toArray();
    } else {
      $this->checked = [];
    }
  }
  public function updatedChecked()
  {
    $this->selectPage = false;
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
  public function swapSortDirection()
  {
    return $this->orderAsc === '1' ? '0' : '1';
  }
  public function selectAll()
  {
    $this->selectAll = true;
    $this->checked = $this->productsQuery->pluck('id')->map(fn($item) => (string) $item)->toArray();
  }
  public function loadMore()
  {
    $this->loadAmount += 10;
  }
  public function deleteRecords()
  {
    $products = Product::whereKey($this->checked)->get();
    foreach ($products as $product) {
      $id = $product->id;
      $producttodel = Product::find($id);
      if (
        $producttodel->carts_item()->exists() ||
        $producttodel->orders_item()->exists() ||
        $producttodel->order_suppliers()->exists()
      ) {
        session()->flash('notification', [
          'message' => 'This product is in use and cannot be deleted!',
          'type' => 'danger',
          'title' => 'Error'
        ]);
        $this->relation = true;
        $this->single = false;
        $this->idbeingremoved = $producttodel->id;
        return;
      }
      $productcats = Products_categories::where('product_id', $id)->get();
      if ($productcats != NULL) {
        foreach ($productcats as $productcat) {
          $productcat->delete();
        }
      }
      $productspecs = Product_Spec::where('product_id', $id)->get();
      if ($productspecs != NULL) {
        foreach ($productspecs as $productspec) {
          $productspec->delete();
        }
      }
      $costs = ProductCost::where('product_id', $id)->get();
      if ($costs != NULL) {
        foreach ($costs as $cost) {
          $cost->delete();
        }
      }
      $relproducts = Related_Products::where('product_id', $id)->orwhere('parent_id', $id)->get();
      if ($relproducts != NULL) {
        foreach ($relproducts as $item) {
          $item->delete();
        }
      }
      //de comentat pe viitor
      $productcarts = Cart_Item::where('product_id', $id)->get();
      if ($productcarts != NULL) {
        foreach ($productcarts as $cartitem) {
          $cart = $cartitem->cart;
          $cart->sum_amount -= $cartitem->price;
          $cart->quantity_amount -= $cartitem->quantity;
          $cart->save();
          $cartitem->delete();
          $this->emit('cartUpdated');
        }
      }
      $productswishlist = Wishlist::where('product_id', $id)->get();
      if ($productswishlist != NULL) {
        foreach ($productswishlist as $productwis) {
          $productwis->delete();
          $this->emit('wishlistUpdated');
        }
      }
      ModelsProductReviews::where('product_id', $id)->delete();

      $productpricelists = PricelistEntries::where('product_id', $id)->get();
      if ($productpricelists != NULL) {
        foreach ($productpricelists as $productpricelist) {
          $productpricelist->delete();
        }
      }
      $medias = $producttodel->media()->get();
      foreach ($medias as $media) {
        $media->delete();
      }
      $productType = class_basename(get_class($producttodel));
      $filespath = 'media/' . $productType . '/' . $producttodel->id;
      if (File::exists($filespath)) {
        File::deleteDirectory($filespath);
      }
      $producttodel->delete();
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
    $id = $this->idbeingremoved;
    $product = Product::findOrFail($id);
    if (
      $product->carts_item()->exists() ||
      $product->orders_item()->exists() ||
      $product->order_suppliers()->exists()
    ) {
      session()->flash('notification', [
        'message' => 'This product is in use and cannot be deleted!',
        'type' => 'danger',
        'title' => 'Error'
      ]);
      $this->relation = true;
      $this->single = false;
      return;
    }
    $productcats = Products_categories::where('product_id', $id)->get();
    if ($productcats != NULL) {
      foreach ($productcats as $productcat) {
        $productcat->delete();
      }
    }
    $productcarts = Cart_Item::where('product_id', $id)->get();
    if ($productcarts != NULL) {
      foreach ($productcarts as $cartitem) {
        $cart = $cartitem->cart;
        $cart->sum_amount -= $cartitem->price;
        $cart->quantity_amount -= $cartitem->quantity;
        $cart->save();
        $cartitem->delete();
        $this->emit('cartUpdated');
      }
    }
    $productswishlist = Wishlist::where('product_id', $id)->get();
    if ($productswishlist != NULL) {
      foreach ($productswishlist as $productwis) {
        $productwis->delete();
        $this->emit('wishlistUpdated');
      }
    }
    $productspecs = Product_Spec::where('product_id', $id)->get();
    if ($productspecs != NULL) {
      foreach ($productspecs as $productspec) {
        $productspec->delete();
      }
    }
    ModelsProductReviews::where('product_id', $id)->delete();

    $productpricelists = PricelistEntries::where('product_id', $id)->get();
    if ($productpricelists != NULL) {
      foreach ($productpricelists as $productpricelist) {
        $productpricelist->delete();
      }
    }
    $costs = ProductCost::where('product_id', $id)->get();
    if ($costs != NULL) {
      foreach ($costs as $cost) {
        $cost->delete();
      }
    }
    $medias = $product->media()->get();
    foreach ($medias as $media) {
      $media->delete();
    }
    $productType = class_basename(get_class($product));
    $filespath = 'media/' . $productType . '/' . $product->id;
    if (File::exists($filespath)) {
      File::deleteDirectory($filespath);
    }
    $product->delete();
    $this->checked = array_diff($this->checked, [$id]);
    $this->single = false;
    session()->flash('notification', [
      'message' => 'Records deleted successfully!',
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
  public function ProductshuffledIds()
  {
    $products = Product::all();

    $shuffledIds = range(1, $products->count());
    shuffle($shuffledIds);

    foreach ($products as $index => $product) {
      $product->innerid = $shuffledIds[$index];
      $product->save();
    }
    session()->flash('notification', [
      'message' => 'Product ids shuffled successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }
  public function Relatedshuffleseq()
  {
    $products = Product::all();

    $shuffledIds = range(1, $products->count());
    shuffle($shuffledIds);

    foreach ($products  as $product) {
      if ($product->related_product->count() != 0) {
        $shuffledIds = range(1, $product->related_product->count());
        shuffle($shuffledIds);
        foreach ($product->related_product as $index => $related) {
          $related->sequence = $shuffledIds[$index];
          $related->save();
        }
      } else {
        continue;
      }
    }
    Cache::forget('cached_products');

    session()->flash('notification', [
      'message' => 'Related products sequence shuffled successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }
  public function forcedeleteRecord()
  {
    $product = Product::find($this->idbeingremoved);
    $productcarts = $product->carts_item()->get();
    if ($productcarts != NULL) {
      foreach ($productcarts as $cartitem) {
        $cart = $cartitem->cart;
        $cart->sum_amount -= $cartitem->price * $cartitem->quantity;
        $cart->quantity_amount -= $cartitem->quantity;
        $cart->final_amount -= $cartitem->price * $cartitem->quantity;
        $cart->save();
        if ($cart->final_amount <= 0 || $cart->sum_amount <= 0) {
          $cart->sum_amount = 0;
          $cart->quantity_amount = 0;
          $cart->final_amount = 0;
          $cart->save();
        }
        $cartitem->delete();
        $this->emit('cartUpdated');
      }
    }
    $productorders = $product->orders_item()->get();
    if ($productorders != NULL) {
      foreach ($productorders as $orderitem) {
        $order = $orderitem->order;
        $order->sum_amount -= $orderitem->price * $orderitem->quantity;
        $order->quantity_amount -= $orderitem->quantity;
        $order->final_amount -= $orderitem->price * $orderitem->quantity;
        $order->save();
        if ($order->final_amount <= 0 || $order->sum_amount <= 0) {
          $order->sum_amount = 0;
          $order->quantity_amount = 0;
          $order->final_amount = 0;
          $order->save();
        }
        $orderitem->delete();
        $this->emit('orderUpdated');
      }
    }
    $productordersuppliers = $product->order_suppliers()->get();
    if ($productordersuppliers != NULL) {
      foreach ($productordersuppliers as $orderitem) {
        $order = $orderitem->order;
        $order->sum_amount -= $orderitem->price * $orderitem->quantity;
        $order->final_amount -= $orderitem->price * $orderitem->quantity;
        $order->save();
        if ($order->final_amount <= 0 || $order->sum_amount <= 0) {
          $order->sum_amount = 0;
          $order->final_amount = 0;
          $order->save();
        }
        $orderitem->delete();
        $this->emit('orderUpdated');
      }
    }
    $this->deleteRecord();
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

      Product::select($realColumns)
        ->withCount([
          'orders_item as interim_quantity' => function ($query) {
            $query->whereHas('order', function ($q) {
              $q->where('status_id', 31);
            })->select(DB::raw('
                        CASE
                            WHEN COUNT(*) = 0 THEN products.quantity
                            ELSE SUM(quantity) + products.quantity
                        END
                    '));
          },
          'order_suppliers as quantity_ordered' => function ($query) {
            $query->whereHas('order', function ($q) {
              $q->where('status', '!=', 'closed');
            })->select(DB::raw('SUM(quantity)'));
          },
        ])
        ->whereIn('id', $checked)
        ->orderBy($this->listview['sort']['column'] ?? 'created_at', $this->listview['sort']['direction'] ?? 'desc')
        ->chunk(1000, function ($items) use ($handle, $selectedColumns) {
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

    DB::afterCommit(function () use ($job, $chunkIndex, $filenameBase) {
      for ($i = 0; $i <= $chunkIndex; $i++) {
        $chunkPath = "import_chunks/{$filenameBase}/chunk_{$i}.csv";
        DynamicCsvImportJob::dispatch($this->tableName, $chunkPath, $job->id);
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
