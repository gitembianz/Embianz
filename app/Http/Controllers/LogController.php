<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;

class LogController extends Controller
{
    public function getLogs()
    {
        $logFile = storage_path('logs/laravel.log');
        
        if (!File::exists($logFile)) {
            return response()->json(['errors' => []]);
        }

        $content = File::get($logFile);
        $lines = explode("\n", $content);
        
        $errors = [];
        $currentError = null;

        foreach ($lines as $line) {
            if (preg_match('/^\[\d{4}-\d{2}-\d{2}\s\d{2}:\d{2}:\d{2}\].*\.ERROR:/', $line)) {
                if ($currentError) {
                    $errors[] = $currentError;
                }
                $currentError = $line;
            } elseif ($currentError && !empty($line)) {
                $currentError .= "\n" . $line;
            }
        }

        if ($currentError) {
            $errors[] = $currentError;
        }

        $errors = array_reverse($errors);
        
        return response()->json(['errors' => $errors]);
    }

public function deleteLogs()
{
    try {
        $logFile = storage_path('logs/laravel.log');
        
        if (File::exists($logFile)) {
            File::delete($logFile);
            return response()->json(['success' => true, 'message' => 'Log file deleted successfully']);
        }
        
        return response()->json(['success' => false, 'message' => 'Log file not found']);
    } catch (\Throwable $th) {
        return response()->json(['success' => false, 'message' => 'Error deleting log file: ' . $th->getMessage()], 500);
    }
}
}
