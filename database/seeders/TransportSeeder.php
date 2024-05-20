<?php

namespace Database\Seeders;

use App\Models\Transport;
use Illuminate\Database\Seeder;

class TransportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            ['type' => Transport::TYPE_BIKE, 'rate' => 0.50],
            ['type' => Transport::TYPE_CAR, 'rate' => 0.10],
            ['type' => Transport::TYPE_BUS, 'rate' => 0.25],
            ['type' => Transport::TYPE_TRAIN, 'rate' => 0.25],
        ];

        foreach ($types as $type) {
            Transport::create($type);
        }
    }
}
