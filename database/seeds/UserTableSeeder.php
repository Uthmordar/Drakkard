<?php
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder{
    public function run() {
        DB::table('users')->delete();
        DB::unprepared('ALTER TABLE drakkard_users AUTO_INCREMENT=1');
        DB::table('users')->insert(
            [
                ['name'=>'Tanguy',
                 'email'=>'tanguyrygodin@gmail.com',
                 'password'=>Hash::make('drakkard'),
                 'snippet_code'=>'snippetdrakkard'
                ],
                ['name'=>'Test',
                 'email'=>'test@mail.com',
                 'password'=>Hash::make('drakkard'),
                 'snippet_code'=>'snippetdrakkard2'
                ]
            ]
        );
    }
}