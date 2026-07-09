<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$settings = \App\Models\AdminPanelSetting::first();
$after_days = (float)$settings->after_days_begin_vacation;
$current_date = strtotime(date('Y-m-d'));

$employees = \App\Models\Employee::where('employment_status', 1)->get();
foreach ($employees as $emp) {
    $hire_date = strtotime($emp->hire_date);
    $diff = round(($current_date - $hire_date) / (60 * 60 * 24));
    if ($diff < $after_days) {
        echo "ID: {$emp->id} | Name: {$emp->name} | Hire Date: {$emp->hire_date} | Diff Days: {$diff} (Less than {$after_days})\n";
    }
}
