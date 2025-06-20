<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call([
            RoleSeeder::class,
        ]);
        User::create(
            [
                'nombre' => 'Rodrigo',
                'apellido' => 'Centellas',
                'ci' => '7772243',
                'email' => 'rodrigo@gmail.com',
                'estado' => 'disponible',
                'role_id' => '1',
                'password' => Hash::make('123')
            ]
        );

        User::create([
            'nombre' => 'Jorge',
            'apellido' => 'Rossel',
            'ci' => '7695673',
            'email' => 'jorge@gmail.com',
            'profile_photo_path' => '/storage/jorge.jpg',
            'estado' => 'disponible',
            'role_id' => 3,
            'password' => Hash::make('123')
        ]);

        User::create([
            'nombre' => 'Luis',
            'apellido' => 'Rossel',
            'ci' => '7123456',
            'email' => 'luis@gmail.com',
            'profile_photo_path' => '/storage/luis.jpg',
            'estado' => 'disponible',
            'role_id' => 3,
            'password' => Hash::make('123')
        ]);
    }
}
