<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        DB::disableQueryLog();

        if (DB::getDriverName() === 'sqlite') {
            DB::statement("PRAGMA foreign_keys = OFF;");
        }

        $path = database_path('seeders/data/cities.csv');
        $file = fopen($path, 'r');
        $first = true;

        DB::beginTransaction();

        while (($row = fgetcsv($file)) !== false) {

            if ($first) {
                $first = false;
                continue;
            }

            DB::table('cities')->updateOrInsert(
                [
                    // MUST MATCH YOUR UNIQUE INDEX:
                    'country_id' => $row[5],
                    'state_id'   => $row[2],
                    'name'       => $row[1],
                ],
                [
                    'id'  => $row[0],  // set ID only on insert
                    'lat' => $row[8],
                    'lng' => $row[9],
                ]
            );
        }

        DB::commit();
        fclose($file);

        if (DB::getDriverName() === 'sqlite') {
            DB::statement("PRAGMA foreign_keys = ON;");
        }
    }
}
