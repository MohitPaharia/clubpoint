<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StateSeeder extends Seeder
{
    public function run(): void
    {
        DB::disableQueryLog();
        if (DB::getDriverName() === 'sqlite') {
            DB::statement("PRAGMA foreign_keys = OFF;");
        }
        // DB::statement('SET FOREIGN_KEY_CHECKS=0;'); // MySQL

        $path = database_path('seeders/data/states.csv');
        $file = fopen($path, 'r');

        $first = true;
        $batch = [];
        $batchSize = 1000; // tweakable

        DB::beginTransaction();

        while (($row = fgetcsv($file)) !== false) {

            if ($first) {
                $first = false;
                continue;
            }

            $batch[] = [
                'id'         => $row[0],
                'name'       => $row[1],
                'country_id' => $row[2],
            ];

            // Insert a chunk of rows at a time
            if (count($batch) >= $batchSize) {
                DB::table('states')->insert($batch);
                $batch = [];
            }
        }

        // Insert remaining rows
        if (!empty($batch)) {
            DB::table('states')->insert($batch);
        }

        DB::commit();
        fclose($file);

        if (DB::getDriverName() === 'sqlite') {
            DB::statement("PRAGMA foreign_keys = OFF;");
        }
    }
}
