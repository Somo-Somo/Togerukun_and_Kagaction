<?php

namespace Database\Seeders;

use App\Models\User;
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
        $client = Neo4jDB::call();     

        $testUser =  $client->run(
                                <<<'CYPHER'
                                    MATCH (user:User{email:'test1'}) RETURN user
                                    CYPHER
                                );
                                    
        if (empty($testUser->toArray())) {
            $client->run(
                <<<'CYPHER'
                    CREATE (u:User {name:$name, email:$email, password:$password})
                    CYPHER,[
                        'name' => 'test1',
                        'email' => 'test1@test',
                        'password' => bcrypt('test'),
                    ] );
        }
    }
}
