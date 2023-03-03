<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class SendSalaryChangeEmail extends Command
{
    protected $signature = 'salary:change {employee_id} {new_salary}';

    protected $description = 'Send an email to an employee when their salary is changed.';

    public function handle()
    {
        $employee = User::findOrFail($this->argument('id'));

        $data = [
            'name' => $employee->name,
            'new_salary' => $this->argument('new_salary'),
        ];

        Mail::send('emails.salary_change', $data, function($message) use ($employee) {
            $message->to($employee->email, $employee->name)
                ->subject('Your salary has been changed');
        });

        $this->info('Salary change email sent successfully.');
    }
}
