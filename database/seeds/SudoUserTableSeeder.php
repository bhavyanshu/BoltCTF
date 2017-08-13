<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SudoUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('users')->delete();
      DB::table('users')->insert(
        array(
          array (
            'role_id' => '2',
            'username' => 'admin', // CHANGE THIS
            'email' => 'admin@admin.com', // CHANGE THIS
            'password' => bcrypt('p@ssw0rd'), // CHANGE THIS
            'api_token' => Str::random(),
            'confirmed' => 1,
            'blocked' => 0,
            'created_at' => date("Y-m-d H:i:s")
          )
        )
      );

      DB::table('profiles')->delete();
      DB::table('profiles')->insert(
        array(
          array (
            'user_id' => '1',
            'created_at' => date("Y-m-d H:i:s")
          )
        )
      );
    }
}
