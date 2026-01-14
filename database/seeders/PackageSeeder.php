<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Package;

class PackageSeeder extends Seeder
{
    public function run(): void
    {
        Package::insert([
            ['name' => 'Basic', 'price' => 300000.00],
            ['name' => 'Pro', 'price' => 600000.00],
            ['name' => 'Premium', 'price' => 900000.00],
        ]);
    }
}
