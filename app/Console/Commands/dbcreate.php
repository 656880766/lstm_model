<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
USE Illuminate\Support\Facades\DB;

class dbcreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:create{name?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creer une commande de connection à la base de donnees';

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
        $schemaName = $this->argument('name')  ? : config('database.connections.mysql.database');
        $charset =  config('database.connections.mysql.charset','utf8mb4');
        $collation = config('database.connections.mysql.collation','utf8mb4_general_ci');

        config(['database.connections.mysql.database'=>null]);
        $query = "DROP DATABASE $schemaName;";
         DB::statement($query);
        $query ="CREATE DATABASE IF NOT EXISTS $schemaName CHARACTER SET $charset COLLATE $collation ;";
        DB::statement($query);
        echo"Database $schemaName create sucessfully";
        config(['database.connections.mysql.database'=>  $schemaName ]);
    }
}
