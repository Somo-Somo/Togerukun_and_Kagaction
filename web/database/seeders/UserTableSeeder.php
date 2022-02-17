<?php

namespace Database\Seeders;

use packages\Domain\User\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Neo4jDB;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $client = Neo4jDB::call();     

        // $client->run(<<<'CYPHER'
        //             CREATE (u:User {name:$name, email:$email, password:$password})
        //             CYPHER,[
        //                 //adminユーザー
        //                 'name' => 'test2',
        //                 'email' => 'test2@test',
        //                 'password' => bcrypt('test'),
        //             ] );

        //userのダミーデータを2つ作成
        DB::table('users')->insert([
            [
                //adminではないユーザー
                'name' => 'test1',
                'email' => 'test1@test',
                'password' => bcrypt('test'),
            ],
            [
                //adminユーザー
                'name' => 'test2',
                'email' => 'test2@test',
                'password' => bcrypt('test'),
            ],
        ]);


    }
}
