<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;
use App\Models\User;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();
        DB::table('role_user')->truncate();
        $adminRole = Role::where('nombre', 'ADMIN')->first();
        $clientRole = Role::where('nombre', 'CLIENTE')->first();

        $adminUser = User::create([
            'name'=>"Los durice",
            'email'=>"admin@admin.com",
            'password'=>Hash::make("admin")
        ]);

        $clienteUser = User::create([
            'name'=>"Comprador impulsivo",
            'email'=>"comproycompro@impulsive.com",
            'password'=>Hash::make("cliente")
        ]);

        $adminUser->roles()->attach($adminRole);
        $clienteUser->roles()->attach($clientRole);
    }
}
