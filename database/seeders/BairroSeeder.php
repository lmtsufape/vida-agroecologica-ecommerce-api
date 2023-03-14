<?php

namespace Database\Seeders;

use App\Models\Bairro;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BairroSeeder extends Seeder
{
    public function run()
    {
        $dir = __DIR__;
        $handle = fopen("{$dir}\..\..\public\storage\bairros.csv", "r");
        while ($line = fgetcsv($handle, 1000, ",")) {
            Bairro::firstOrCreate([
                'nome' => $line[0]
            ]);
        }
        fclose($handle);
    }
}
