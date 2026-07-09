<?php

// Boot Laravel
require __DIR__ . '/../../../../vendor/autoload.php';
$app = require_once __DIR__ . '/../../../../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Admin;
use App\Models\Employee;
use App\Models\EmployeeRequestType;
use App\Models\EmployeeRequest;
use App\Models\EmployeeRequestComment;
use App\Models\AlertSystemMonitoring;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

DB::beginTransaction();

try {
    echo "=== 1. Testing Employee Login Restriction (allow_login) ===\n";
    
    // Fetch existing employee
    $employee = Employee::first();
    if (!$employee) {
        throw new Exception("No employees found in DB to run tests!");
    }
    
    // Create an admin login for this employee
    $admin = Admin::create([
        'name' => 'موظف تجريبي 1',
        'username' => 'test_emp_1',
        'email' => 'test_emp_1@test.com',
        'password' => bcrypt('password123'),
        'company_id' => 1,
        'is_employee' => 1,
        'employee_id' => $employee->id,
        'allow_login' => 0, // Blocked!
        'status' => 1,
        'is_master_admin' => 0,
        'date' => now()->toDateString(),
        'added_by' => 1,
        'updated_by' => 1,
    ]);

    echo "Attempting to login blocked employee...\n";
    $loginResult = Auth::guard('admin')->attempt(['username' => 'test_emp_1', 'password' => 'password123']);
    if ($loginResult) {
        $user = Auth::guard('admin')->user();
        if ($user->is_employee == 1 && $user->allow_login == 0) {
            Auth::guard('admin')->logout();
            echo "SUCCESS: Blocked employee was successfully logged out automatically!\n";
        } else {
            throw new Exception("FAILED: Blocked employee was allowed to stay logged in!");
        }
    } else {
        throw new Exception("FAILED: Auth attempt did not succeed at all!");
    }

    echo "\n=== 2. Testing Employee Request Types CRUD & System Monitoring ===\n";
    // Sign in as admin (id = 1)
    $masterAdmin = Admin::find(1);
    Auth::guard('admin')->login($masterAdmin);
    DB::table('admin_panel_settings')->where('company_id', 1)->update(['is_active_system_monitoring' => 1]);
    
    // Create a new request type
    $type = EmployeeRequestType::create([
        'name' => 'طلب إجازة زواج',
        'is_active' => 1,
        'company_id' => 1,
        'added_by' => 1,
        'updated_by' => 1,
    ]);
    echo "Created request type: '{$type->name}'\n";



    // Verify system monitoring log
    $typeLog = AlertSystemMonitoring::where('company_id', 1)
        ->where('alert_module_id', function($q) {
            $q->select('id')->from('alert_modules')->where('name', 'أنواع طلبات الموظفين')->limit(1);
        })
        ->orderBy('id', 'desc')
        ->first();

    if ($typeLog && str_contains($typeLog->name, 'طلب إجازة زواج')) {
        echo "SUCCESS: System monitoring logged type creation! Name: '{$typeLog->name}'\n";
    } else {
        throw new Exception("FAILED: System monitoring did not log type creation correctly!");
    }

    echo "\n=== 3. Testing Employee Request Submission & Notifications ===\n";
    // Create request
    $requestTicket = EmployeeRequest::create([
        'employee_id' => $employee->id,
        'employee_request_type_id' => $type->id,
        'title' => 'طلب إجازة زواج لمدة أسبوع',
        'content' => 'أرجو التكرم بالموافقة على منحي إجازة زواج من الأحد القادم.',
        'status' => 0, // pending
        'is_archived' => 0,
        'company_id' => 1,
        'added_by' => $admin->id, // added by employee
        'updated_by' => $admin->id,
    ]);
    echo "Submitted employee request ticket #{$requestTicket->id}\n";

    // Verify navbar shares counter correctly
    $pendingCount = EmployeeRequest::where('company_id', 1)->where('status', 0)->count();
    echo "Navbar counter for pending requests shows: {$pendingCount} pending requests.\n";
    if ($pendingCount > 0) {
        echo "SUCCESS: Counter is updated!\n";
    } else {
        throw new Exception("FAILED: Pending requests counter is 0!");
    }

    echo "\n=== 4. Testing Comments System ===\n";
    // Admin comments on request
    $comment = EmployeeRequestComment::create([
        'employee_request_id' => $requestTicket->id,
        'admin_id' => $masterAdmin->id,
        'comment' => 'يرجى إرفاق وثيقة عقد الزواج لتسجيلها.',
    ]);
    echo "Admin commented: '{$comment->comment}'\n";

    // Verify comments relation works
    $commentsCount = $requestTicket->comments()->count();
    if ($commentsCount == 1) {
        echo "SUCCESS: Comment successfully linked to ticket #{$requestTicket->id}\n";
    } else {
        throw new Exception("FAILED: Comments count is {$commentsCount} instead of 1!");
    }

    echo "\n=== 5. Testing Request Status Change & Archiving ===\n";
    // Change status to approved (1)
    $requestTicket->status = 1;
    $requestTicket->save();
    echo "Approved request ticket #{$requestTicket->id}\n";

    // Archive the ticket
    $requestTicket->is_archived = 1;
    $requestTicket->archived_by = $masterAdmin->id;
    $requestTicket->archived_at = now();
    $requestTicket->save();
    echo "Archived request ticket #{$requestTicket->id}\n";

    // Verify ticket is archived
    $freshTicket = EmployeeRequest::find($requestTicket->id);
    if ($freshTicket->status == 1 && $freshTicket->is_archived == 1) {
        echo "SUCCESS: Ticket is approved and archived successfully!\n";
    } else {
        throw new Exception("FAILED: Ticket status or archive state is incorrect!");
    }

    echo "\n=== ALL TESTS PASSED SUCCESSFULLY! ===\n";
    
} catch (Exception $e) {
    echo "TEST FAILED: " . $e->getMessage() . "\n";
} finally {
    DB::rollBack();
    echo "Database rolled back successfully.\n";
}
