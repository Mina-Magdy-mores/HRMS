<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\EmployeeRequest;
use App\Models\EmployeeRequestComment;
use App\Models\EmployeeRequestType;
use App\Models\Admin;
use Illuminate\Database\Seeder;

class EmployeeRequestSeeder extends Seeder
{
    public function run(): void
    {
        $employees = Employee::where('company_id', 1)->get();
        $requestTypes = EmployeeRequestType::where('company_id', 1)->get();

        if ($employees->isEmpty() || $requestTypes->isEmpty()) {
            return;
        }

        $requestsData = [
            [
                'title' => 'طلب إجازة اعتيادية لظروف عائلية',
                'content' => 'أرجو التكرم بالموافقة على منحى إجازة اعتيادية لمدة 3 أيام تبدأ من الأسبوع القادم لظروف عائلية طارئة.',
                'status' => 1, // Approved
                'comment' => 'تمت الموافقة، يرجى تسليم مهامك لزميلك قبل المغادرة.',
            ],
            [
                'title' => 'طلب سلفة مالية عاجلة',
                'content' => 'أرجو التكرم بالموافقة على صرف سلفة قدرها 3000 جنيه لظروف صحية طارئة على أن يتم خصمها من راتبي القادم.',
                'status' => 0, // Pending
                'comment' => null,
            ],
            [
                'title' => 'طلب تعديل بصمة حضور يوم 5 يوليو',
                'content' => 'يرجى التكرم بتعديل بصمة الحضور ليوم 5 يوليو حيث كنت متواجداً بمأمورية عمل خارجية ولم أتمكن من البصم.',
                'status' => 2, // Rejected
                'comment' => 'يرجى إرفاق نموذج إثبات المأمورية المعتمد من رئيس القسم أولاً.',
            ]
        ];

        foreach ($employees as $employee) {
            $empAdmin = Admin::where('employee_id', $employee->id)->first();
            $empAdminId = $empAdmin ? $empAdmin->id : 1;

            foreach ($requestsData as $index => $data) {
                // Map types
                $typeId = $requestTypes->pluck('id')->random();

                $request = EmployeeRequest::create([
                    'employee_id' => $employee->id,
                    'employee_request_type_id' => $typeId,
                    'title' => $data['title'],
                    'content' => $data['content'],
                    'status' => $data['status'],
                    'is_archived' => 0,
                    'company_id' => 1,
                    'added_by' => $empAdminId, // Added by the employee admin account
                    'updated_by' => 1,
                ]);

                if ($data['comment']) {
                    // Manager comment
                    EmployeeRequestComment::create([
                        'employee_request_id' => $request->id,
                        'admin_id' => 1, // Manager
                        'comment' => $data['comment'],
                    ]);
                }
            }
        }

        $this->command->info('✅ Employee Requests and Comments seeded successfully!');
    }
}
