<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ExportEmployees extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'export:employees';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $employees = User::all();
        $json = $employees->toJson();
        $filename = 'employees.json';
        $path = storage_path('app/' . $filename);
        file_put_contents($path, $json);

        $this->info('All employees have been exported to ' . $filename);
    }

}
