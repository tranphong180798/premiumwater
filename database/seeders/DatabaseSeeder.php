<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory(20)->create();

        for ($i = 1; $i <= 20; $i++) {
            $user = User::find($i);
            $user->email = "premium{$i}@gmail.com";
            $user->save();
        }
    }
}
