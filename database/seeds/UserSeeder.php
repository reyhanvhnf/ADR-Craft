<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('users')->insert([
            [
                'id' => '1',
                'name' => 'admin',
                'email' => 'admin@adr.com',
                'password' => bcrypt('admin'),
                'phone_number' => '081122334455',
                'created_at' => now(),
                'updated_at' => now(),
                'remember_token' => Str::random(10),
                'roles' => 'ADMIN',
            ]]);
    }
}
