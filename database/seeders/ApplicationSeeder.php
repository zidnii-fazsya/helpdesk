<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
public function run()
{
    $app = \App\Models\Application::create(['nama_aplikasi' => 'Peduli WNI']);
    $user = \App\Models\User::where('email', 'adminaplikasi@example.com')->first();
    $user2 = \App\Models\User::where('email', 'adminhelpdesk@example.com')->first();

    $app->users()->attach($user->id, ['role' => 'admin_aplikasi']);
    $app->users()->attach($user2->id, ['role' => 'admin_helpdesk']);
}

}
