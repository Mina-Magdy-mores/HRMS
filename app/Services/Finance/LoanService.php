<?php

namespace App\Services\Finance;

use App\Services\BaseService;
use App\Models\MainSalaryEmployeeLoan;
use App\Models\MainSalaryEmployee;
use Illuminate\Support\Facades\DB;
use App\Services\Finance\SalaryService;

class LoanService extends BaseService
{
    public function __construct()
    {
        $this->setModel(MainSalaryEmployeeLoan::class);
    }

    public function createLoan($data)
    {
        $data['company_id'] = $this->getCompanyId();
        $data['added_by'] = $this->getUserId();
        $data['updated_by'] = $this->getUserId();

        DB::transaction(function() use (&$loan, $data) {
            $loan = insert(MainSalaryEmployeeLoan::class, $data, true);
            $this->recalculateSalary($data['employee_id']);
        });

        return $loan;
    }

    public function updateLoan($id, $data)
    {
        $loan = $this->getById($id);
        if (empty($loan) || $loan->is_archived == 1) {
            throw new \Exception('لا يمكن تعديل السلفة');
        }

        DB::transaction(function() use ($loan, $data) {
            $data['updated_by'] = $this->getUserId();
            update($loan, $data);
            $this->recalculateSalary($loan->employee_id);
        });

        return $loan;
    }

    public function deleteLoan($id)
    {
        $loan = $this->getById($id);
        if (empty($loan) || $loan->is_archived == 1) {
            throw new \Exception('لا يمكن حذف السلفة');
        }

        DB::transaction(function() use ($loan) {
            destroy($loan);
            $this->recalculateSalary($loan->employee_id);
        });

        return true;
    }

    private function recalculateSalary($employeeId)
    {
        $main_salary = MainSalaryEmployee::where('employee_id', $employeeId)
            ->where('company_id', $this->getCompanyId())
            ->where('is_archived', 0)
            ->first();
        if ($main_salary) {
            app(SalaryService::class)->recalculateMainSalary($main_salary->id);
        }
    }
}