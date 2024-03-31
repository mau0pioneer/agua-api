<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'name' => 'mauricio',
                'email' => 'mtz0mau2002@gmail.com',
                'password' => bcrypt('MTZmau2002')
            ],
            [
                'name' => 'eder',
                'email' => 'eder@agua.com',
                'password' => bcrypt('utml87fu29')
            ],
            [
                'name' => 'edmundo',
                'email' => 'edmundo@agua.com',
                'password' => bcrypt('zk46u688ts')
            ]
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
