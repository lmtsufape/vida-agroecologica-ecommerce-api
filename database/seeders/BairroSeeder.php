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
            $bairro = Bairro::firstOrNew(['nome' => $line[0]]);
            $bairro->taxa = $line[1];
            $bairro->save();
        }
        fclose($handle);
    }
}
