<?php

namespace Database\Seeders;

use App\Models\Collector;
use Illuminate\Database\Seeder;

class CollectorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $collectors = [
            [
                'name' => 'Eder Perez',
                'type' => 'individual',
                'email' => 'edpe12@agua.com' // FusEPLErUm
            ],
            [
                'name' => 'Azucena Tequihuactle',
                'type' => 'individual',
                'email' => 'azte53@agua.com' // rIATcHarbo
            ],
            [
                'name' => 'Edmundo Rodriguez',
                'type' => 'individual',
                'email' => 'edro74@agua.com' // DINtITigHt
            ],
            [
                'name' => 'Griselda Pacheco',
                'type' => 'individual',
                'email' => 'grpa21@agua.com' // IrDbUCRiON
            ],
            [
                'name' => 'Patricia Garcia',
                'type' => 'individual',
                'email' => 'paga88@agua.com' // LauGHoYFUL
            ],
            [
                'name' => 'Hilario Mendoza',
                'type' => 'individual',
                'email' => 'hime93@agua.com' // ArcuASiCIA
            ],
            [
                'name' => 'Jimena Soto',
                'type' => 'individual',
                'email' => 'jiso45@agua.com' // LaiMeNDURL
            ],
            [
                'name' => 'Roberto Blanco',
                'type' => 'individual',
                'email' => 'robl28@agua.com' // bOwNSHerEw
            ],
            [
                'name' => 'Maria del Socorro',
                'type' => 'individual',
                'email' => 'maso83@agua.com' // HerivErYMe
            ]
        ];

        foreach ($collectors as $collector) Collector::create($collector);
    }
}
