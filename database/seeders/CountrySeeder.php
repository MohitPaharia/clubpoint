<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountrySeeder extends Seeder
{
    public function run()
    {
        $path = database_path('seeders/data/countries.csv');

        $file = fopen($path, 'r');
        $first = true;

        while (($row = fgetcsv($file)) !== false) {
            if ($first) { $first = false; continue; }

            DB::table('countries')->insert([
                'id'        => $row[0],
                'name'      => $row[1],
                'iso2'      => $row[3],
                'iso3'      => $row[2],
            ]);
        }

        fclose($file);
    }
}

