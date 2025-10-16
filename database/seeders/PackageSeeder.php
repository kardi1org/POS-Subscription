<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Package;

class PackageSeeder extends Seeder
{
    public function run(): void
    {
        Package::insert([
            ['name' => 'Basic', 'price' => 10.00],
            ['name' => 'Pro', 'price' => 25.00],
            ['name' => 'Premium', 'price' => 50.00],
        ]);
    }
}
