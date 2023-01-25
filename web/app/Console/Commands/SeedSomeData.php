<?php

namespace App\Console\Commands;

use \Laudis\Neo4j\ClientBuilder;
use \Laudis\Neo4j\Authentication\Authenticate;
use Illuminate\Console\Command;

class SeedSomeData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seedsomedata';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $client = ClientBuilder::create()
            ->withDriver(
                'aura',
                'neo4j+s://aeb87557.production-orch-0064.neo4j.io:7687',
                Authenticate::basic('neo4j', '9D8oiNAv3XjrYa7O31nv0SAgq6iuzAXcV6AwKh7QIe8')
            )
            ->build();

        $results = $client->run(<<<'CYPHER'
                        MATCH (m:Movie) 
                        RETURN m.title AS title 
                        ORDER BY m.title 
                        ASC LIMIT $limit
                        CYPHER, ['limit' => 10]);

        foreach ($results as $result) {
            echo $result->get('title') . PHP_EOL;
        }
    }
}
