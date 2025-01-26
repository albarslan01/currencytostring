<?php

namespace Albarslan01\CurrencyToString\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller;

class CurrencyConvert extends Controller
{
    public function changeFormat()
    {
        // Define the file path for the exported SQL file
        //$filePath = storage_path('app/database_export.sql');
        $filePath = public_path('db.sql');

        // Open the file for writing
        $file = fopen($filePath, 'w');

        if (!$file) {
            return response()->json(['message' => 'Could not open file for writing.'], 500);
        }

        // Write the command to disable foreign key checks
        fwrite($file, "SET FOREIGN_KEY_CHECKS=0;\n\n");

        // Fetch all tables
        $databaseName = config('database.connections.mysql.database');
        $tableKey = "Tables_in_{$databaseName}";
        $tables = DB::select('SHOW TABLES');

        // Export tables
        foreach ($tables as $table) {
            $tableName = $table->$tableKey;

            if ($tableName === 'sessions') {
                continue;
            }

            $createTable = DB::select("SHOW CREATE TABLE `$tableName`")[0]->{'Create Table'}.";\n\n";
            fwrite($file, $createTable);

            $rows = DB::table($tableName)->get();
            foreach ($rows as $row) {
                $values = array_map(fn ($value) => DB::connection()->getPdo()->quote($value), (array) $row);
                $insertStatement = "INSERT INTO `$tableName` VALUES (".implode(', ', $values).");\n";
                fwrite($file, $insertStatement);
            }

            fwrite($file, "\n\n");
        }

        fwrite($file, "SET FOREIGN_KEY_CHECKS=1;\n");
        fclose($file);

        return response()->download($filePath, 'database_export.sql')->deleteFileAfterSend(true);
    }
}