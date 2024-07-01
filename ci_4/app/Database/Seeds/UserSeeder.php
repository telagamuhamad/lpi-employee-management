<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'username' => 'admin',
            'password' => password_hash('password', PASSWORD_DEFAULT),
            'email'    => 'testing@testing.com',
            'level'     => 'admin',
        ];

        $this->db->table('users')->insert($data);
    }
}
