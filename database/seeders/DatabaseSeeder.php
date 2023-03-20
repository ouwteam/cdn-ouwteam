<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory()->create([
            'name'     => 'Test User',
            'email'    => 'test@example.com',
            'password' => bcrypt('testAkun'),
        ]);

        $faker = fake("id_ID");
        $dir = $faker->city();

        \App\Models\UserDirectory::factory()->create([
            "user_id" => 1,
            "slug" => Str::slug($dir),
            "dirname" => $dir,
        ]);

        /**
         * @var \App\Models\User $user
         */
        $user = \App\Models\User::find(1);
        Storage::disk("local")->makeDirectory($user->getUserDirName() . "/" . $dir);
    }
}
