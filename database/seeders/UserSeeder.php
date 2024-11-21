<script>
<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Utilisateur Communication Interne
        $commUser = User::create([
            'name' => 'Communication Interne',
            'email' => 'comm@example.com',
            'password' => Hash::make('password'),
            'department' => 'Communication',
            'position' => 'Chargé de communication interne'
        ]);
        $commUser->assignRole('internal_comm');

        // Utilisateur RH
        $hrUser = User::create([
            'name' => 'Ressources Humaines',
            'email' => 'rh@example.com',
            'password' => Hash::make('password'),
            'department' => 'Ressources Humaines',
            'position' => 'Gestionnaire RH'
        ]);
        $hrUser->assignRole('hr');

        // Utilisateur Agent
        $employeeUser = User::create([
            'name' => 'Jean Dupont',
            'email' => 'employee@example.com',
            'password' => Hash::make('password'),
            'department' => 'Marketing',
            'position' => 'Agent Marketing'
        ]);
        $employeeUser->assignRole('employee');
    }
}
</script>