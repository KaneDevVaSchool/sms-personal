<?php

namespace Database\Seeders;

use App\Models\ResourceType;
use Illuminate\Database\Seeder;

class ResourceTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['name' => 'Server', 'icon' => 'server'],
            ['name' => 'Domain', 'icon' => 'globe'],
            ['name' => 'Hosting', 'icon' => 'cloud'],
            ['name' => 'License', 'icon' => 'key'],
            ['name' => 'Service Account', 'icon' => 'user-circle'],
        ];

        foreach ($types as $type) {
            ResourceType::query()->firstOrCreate(['name' => $type['name']], $type);
        }
    }
}
