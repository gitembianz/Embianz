<?php

namespace App\Jobs;

use App\Models\AllJob;
use App\Models\CsvImportJob;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Products_categories;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Schema;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\ProductReviews as ModelsProductReviews;


class DynamicCsvImportJob implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  protected string $table;
  protected string $csvPath;
  protected int $jobId;
  protected int $allJobId;

  public function __construct(string $table, string $csvPath, int $jobId, int $allJobId)
  {
    $this->table = $table;
    $this->csvPath = $csvPath;
    $this->jobId = $jobId;
    $this->allJobId = $allJobId;
  }

  public function handle()
  {
    try {
      $jobRecord = CsvImportJob::find($this->jobId);
      $allJobRecord = AllJob::find($this->allJobId);
      if (!$jobRecord) {
        Log::error("Job ID {$this->jobId} not found.");
        return;
      }

      $jobRecord->update([
        'status' => 'processing',
        'started_at' => now(config('app.timezone')),
      ]);
      $allJobRecord?->update(['status' => 'processing', 'started_at' => now(config('app.timezone'))]);

      if (!Schema::hasTable($this->table)) {
        $jobRecord->update([
          'status' => 'failed',
          'finished_at' => now(config('app.timezone')),
          'errors' => "Table {$this->table} does not exist.",
        ]);
        $allJobRecord?->update([
          'status' => 'failed',
          'finished_at' => now(config('app.timezone')),
          'error' => "Table {$this->table} does not exist."
        ]);
        return;
      }

      $filePath = storage_path('app/' . $this->csvPath);
      if (!file_exists($filePath)) {
        $jobRecord->update([
          'status' => 'failed',
          'finished_at' => now(config('app.timezone')),
          'errors' => "CSV file not found at {$filePath}.",
        ]);
        $allJobRecord?->update([
          'status' => 'failed',
          'finished_at' => now(config('app.timezone')),
          'error' => "CSV file not found at {$filePath}."
        ]);
        return;
      }

      $file = fopen($filePath, 'r');
      $header = fgetcsv($file);

      if (!$header) {
        fclose($file);
        $jobRecord->update([
          'status' => 'failed',
          'finished_at' => now(config('app.timezone')),
          'errors' => "CSV header missing.",
        ]);
        $allJobRecord?->update([
          'status' => 'failed',
          'finished_at' => now(config('app.timezone')),
          'error' => "CSV header missing."
        ]);
        return;
      }

      // Remove BOM
      $header[0] = preg_replace('/^\xEF\xBB\xBF/', '', $header[0]);

      $schema = DB::getDatabaseName();
      $columnInfo = collect(DB::select("
                SELECT COLUMN_NAME, DATA_TYPE, IS_NULLABLE, COLUMN_DEFAULT
                FROM information_schema.columns
                WHERE table_name = ? AND table_schema = ?
            ", [$this->table, $schema]))->keyBy('COLUMN_NAME');

      $requiredColumns = $columnInfo->filter(function ($col) {
        return $col->IS_NULLABLE === 'NO'
          && $col->COLUMN_DEFAULT === null
          && !in_array($col->COLUMN_NAME, ['id', 'created_at', 'updated_at']);
      })->keys()->toArray();

      foreach ($requiredColumns as $required) {
        if (!in_array($required, $header)) {
          fclose($file);
          $jobRecord->update([
            'status' => 'failed',
            'finished_at' => now(config('app.timezone')),
            'errors' => "Missing required column: {$required}.",
          ]);
          $allJobRecord?->update([
            'status' => 'failed',
            'finished_at' => now(config('app.timezone')),
            'error' => "Missing required column: {$required}."
          ]);
          return;
        }
      }

      $errors = [];
      $totalRows = 0;

      while (($row = fgetcsv($file)) !== false) {
        $totalRows++;
        $data = array_combine($header, $row);
        if (!$data) continue;

        $invalid = $this->validateRow($data, $columnInfo);

        if (!empty($invalid)) {
          $errors[] = array_merge(['__row' => $totalRows], $data, ['__error' => implode('; ', $invalid)]);
          continue;
        }

        $insert = $this->insertOrUpdate($data);
      }

      fclose($file);

      if (!empty($errors)) {
        $errorFile = 'imports/review_errors_' . now(config('app.timezone'))->timestamp . '.csv';
        $this->exportErrorCsv($errors, $errorFile, $jobRecord);

        $jobRecord->update([
          'status' => 'finished',
          'finished_at' => now(config('app.timezone')),
          'errors' => "Completed with errors. {$totalRows} rows processed, " . count($errors) . " failed.",
        ]);
      } else {
        $jobRecord->update([
          'status' => 'finished',
          'finished_at' => now(config('app.timezone')),
          'errors' => null,
        ]);
      }
      $allJobRecord?->update([
        'status' => 'finished',
        'finished_at' => now(config('app.timezone')),
        'errors' => null,
      ]);
    } catch (\Throwable $e) {
      $jobRecord = CsvImportJob::find($this->jobId);
      if ($jobRecord) {
        $jobRecord->update([
          'status' => 'failed',
          'finished_at' => now(config('app.timezone')),
          'errors' => $e->getMessage(),
        ]);
      }

      Log::error("Job ID {$this->jobId} failed: " . $e->getMessage(), [
        'trace' => $e->getTraceAsString(),
      ]);
    }
  }

  protected function validateRow(array &$data, $columnInfo): array
  {
    $errors = [];

    foreach ($data as $col => &$value) {
      if (in_array($col, ['created_at', 'updated_at'])) {
        unset($data[$col]);
        continue;
      }

      if (!isset($columnInfo[$col])) continue;

      $value = trim((string) $value);
      $type = strtolower($columnInfo[$col]->DATA_TYPE);
      $isRequired = $columnInfo[$col]->IS_NULLABLE === 'NO' && $columnInfo[$col]->COLUMN_DEFAULT === null;

      if ($value === '' || $value === null) {
        if ($isRequired) {
          $errors[] = "Empty value for required field `$col`.";
        }
        continue;
      }

      $valid = match ($type) {
        'int', 'bigint', 'tinyint' => filter_var($value, FILTER_VALIDATE_INT) !== false,
        'decimal', 'float', 'double' => filter_var($value, FILTER_VALIDATE_FLOAT) !== false,
        'varchar', 'text', 'char' => is_string($value),
        'boolean', 'bool' => in_array(strtolower($value), ['1', '0', 'true', 'false', 'yes', 'no'], true),
        'date', 'datetime' => strtotime($value) !== false,
        default => true
      };

      if (!$valid) {
        $errors[] = "Invalid value for `$col` as `$type`: $value";
      } else {
        if (in_array($type, ['boolean', 'bool'])) {
          $value = in_array(strtolower($value), ['1', 'true', 'yes']) ? 1 : 0;
        }

        if (in_array($type, ['date', 'datetime'])) {
          try {
            $value = Carbon::parse($value)->toDateTimeString();
          } catch (\Exception $e) {
            $errors[] = "Invalid date format for `$col`: $value";
          }
        }
      }
    }

    return $errors;
  }



  protected function insertOrUpdate(array $data): void
  {
    unset($data['created_at'], $data['updated_at']);

    try {
      if (!empty($data['id']) && DB::table($this->table)->where('id', $data['id'])->exists()) {
        DB::table($this->table)->where('id', $data['id'])->update($data);
      } else {
        unset($data['id']);
        $insertedId = DB::table($this->table)->insertGetId($data);
        // Special handling for products table
        if ($this->table === 'products') {

          // Assign default category if set
          if (app('global_default_category') != 0) {
            $defaultcategory = new Products_categories();
            $defaultcategory->product_id = $insertedId;
            $defaultcategory->category_id = app('global_default_category');
            $defaultcategory->save();
          }
          // Calculate and insert default review
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

          $acronim = $acronims[array_rand($acronims)];
          $slug = strtolower($acronim) . '-' . rand(1000, 9999);
          $comm = $comments[array_rand($comments)];
          ModelsProductReviews::create([
            'product_id' => $insertedId,
            'acronim'    => $slug,
            'score'      => rand(4, 5),
            'comment'    => $comm,
            'approved'   => true
          ]);
        }
      }
    } catch (\Throwable $e) {
      Log::error("Insert/update failed on table `{$this->table}` for data: " . json_encode($data), [
        'message' => $e->getMessage(),
        'trace' => $e->getTraceAsString(),
      ]);
      throw $e;
    }
  }


  protected function exportErrorCsv(array $rows, string $path, $jobRecord): void
  {
    $fullPath = storage_path('app/' . $path);

    if (!file_exists(dirname($fullPath))) {
      mkdir(dirname($fullPath), 0755, true);
    }

    $handle = fopen($fullPath, 'w');

    fwrite($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

    if (!empty($rows)) {
      fputcsv($handle, array_keys($rows[0]));
      foreach ($rows as $row) {
        fputcsv($handle, $row);
      }
    }

    fclose($handle);

    $jobRecord->update([
      'error_file' => $path,
    ]);
  }
}
