<?php

namespace App\Services\Finance;

use App\Services\BaseService;
use App\Models\MainSalaryEmployeeSettlement;
use App\Models\MainSalaryEmployee;
use Illuminate\Support\Facades\DB;

class SettlementService extends BaseService
{
    public function __construct()
    {
        $this->setModel(MainSalaryEmployeeSettlement::class);
    }

    public function createSettlement($data)
    {
        $company_id = $this->getCompanyId();
        $data['company_id'] = $company_id;
        $data['added_by'] = $this->getUserId();

        return DB::transaction(function () use ($data, $company_id) {
            $settlement = insert(MainSalaryEmployeeSettlement::class, $data, true);

            $this->updateArchivedSalary(
                $data['employee_id'],
                $data['finance_monthly_calendar_id'],
                $company_id,
                $data['total_amount_for_addition'],
                $data['total_amount_for_deduction'],
                $data['final_total_amount']
            );

            return $settlement;
        });
    }

    public function updateSettlement($id, $data)
    {
        $settlement = $this->getById($id);
        if (empty($settlement)) {
            throw new \Exception('عفواً، السجل غير موجود.');
        }

        $company_id = $this->getCompanyId();

        return DB::transaction(function () use ($settlement, $data, $company_id) {
            $deltaAddition = $data['total_amount_for_addition'] - $settlement->total_amount_for_addition;
            $deltaDeduction = $data['total_amount_for_deduction'] - $settlement->total_amount_for_deduction;
            $deltaFinal = $data['final_total_amount'] - $settlement->final_total_amount;

            $data['updated_by'] = $this->getUserId();
            update($settlement, $data);

            $this->updateArchivedSalary(
                $settlement->employee_id,
                $settlement->finance_monthly_calendar_id,
                $company_id,
                $deltaAddition,
                $deltaDeduction,
                $deltaFinal
            );

            return $settlement;
        });
    }

    public function deleteSettlement($id)
    {
        $settlement = $this->getById($id);
        if (empty($settlement)) {
            throw new \Exception('عفواً، السجل غير موجود.');
        }

        $company_id = $this->getCompanyId();

        return DB::transaction(function () use ($settlement, $company_id) {
            $deltaAddition = -$settlement->total_amount_for_addition;
            $deltaDeduction = -$settlement->total_amount_for_deduction;
            $deltaFinal = -$settlement->final_total_amount;

            destroy($settlement);

            $this->updateArchivedSalary(
                $settlement->employee_id,
                $settlement->finance_monthly_calendar_id,
                $company_id,
                $deltaAddition,
                $deltaDeduction,
                $deltaFinal
            );

            return true;
        });
    }

    private function updateArchivedSalary($employee_id, $finance_monthly_calendar_id, $company_id, $deltaAddition, $deltaDeduction, $deltaFinal)
    {
        $mainSalary = MainSalaryEmployee::where('employee_id', $employee_id)
            ->where('finance_monthly_calendar_id', $finance_monthly_calendar_id)
            ->where('company_id', $company_id)
            ->where('is_archived', 1)
            ->first();

        if ($mainSalary) {
            $mainSalary->total_benefits += $deltaAddition;
            $mainSalary->total_deductions += $deltaDeduction;
            $mainSalary->employee_net_salary += $deltaFinal;
            
            // Adjust the payout amount for the archived salary
            $mainSalary->archive_settlement_amount += $deltaFinal;

            // Update status type based on new net salary
            $net = (float)$mainSalary->employee_net_salary;
            if ($net > 0) {
                $mainSalary->archive_status_type = 1; // دائن
            } elseif ($net < 0) {
                $mainSalary->archive_status_type = 2; // مدين
            } else {
                $mainSalary->archive_status_type = 3; // صافي
            }

            // Adjust is_disbursed
            if ($net >= 0 && $mainSalary->archive_settlement_amount > 0) {
                $mainSalary->is_disbursed = 1;
            } else {
                $mainSalary->is_disbursed = 0;
            }

            $mainSalary->save();
        }
    }
}