<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::create([
            'name' => 'super-admin'
        ]);

        $role->admin()->create([
            'name' => 'Ali Khder',
            'email' => 'alikhder@gmail.com',
            'phone_number' => '0936943559',
            'password' => bcrypt('12345678')
        ]);

        $role->admin()->create([
            'name' => 'Hazem Salami',
            'email' => 'Hazem.salami.2000@gmail.com',
            'phone_number' => '0946436022',
            'password' => bcrypt('12345678')
        ]);
    }
}
