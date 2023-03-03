<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ExportDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'export:database';

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
        // Get the database configuration from the Laravel config files
        $database = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $host = config('database.connections.mysql.host');

        // Get the current date and time to use in the filename
        $timestamp = now()->format('Ymd_His');

        // Set the path to the output file
        $path = storage_path("app/{$database}_{$timestamp}.sql");

        // Build the command to execute mysqldump
        $command = sprintf(
            'mysqldump --user=%s --password=%s --host=%s %s > %s',
            $username,
            $password,
            $host,
            $database,
            $path
        );

        // Execute the command
        exec($command, $output, $exitCode);

        // Check if the command was successful
        if ($exitCode === 0) {
            $this->info('Database exported successfully.');
            $this->info("Output file: {$path}");
        } else {
            $this->error('Error exporting database.');
        }
    }
}
