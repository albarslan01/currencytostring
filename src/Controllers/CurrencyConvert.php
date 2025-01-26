<?php

namespace Albarslan01\CurrencyToString\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller;

class CurrencyConvert extends Controller
{
    public function currencyFormat()
    {

        $filePath = public_path('db.sql');

        $file = fopen($filePath, 'w');

        if (!$file) {
            return response()->json(['message' => 'Could not open file for writing.'], 500);
        }

        fwrite($file, "SET FOREIGN_KEY_CHECKS=0;\n\n");

        $databaseName = config('database.connections.mysql.database');
        $tableKey = "Tables_in_{$databaseName}";
        $tables = DB::select('SHOW TABLES');

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

        return response()->download($filePath, 'db.sql')->deleteFileAfterSend(true);
    }
}