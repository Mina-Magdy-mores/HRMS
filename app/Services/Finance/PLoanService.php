<?php

namespace App\Services\Finance;

use App\Services\BaseService;
use App\Models\MainSalaryEmployeePLoan;
use App\Models\MainSalaryEmployeePLoanInstallment;
use App\Models\MainSalaryEmployee;
use Illuminate\Support\Facades\DB;
use App\Services\Finance\SalaryService;

class PLoanService extends BaseService
{
    public function __construct()
    {
        $this->setModel(MainSalaryEmployeePLoan::class);
    }

    public function createPLoan($data)
    {
        $data['company_id'] = $this->getCompanyId();
        $data['added_by'] = $this->getUserId();
        $data['updated_by'] = $this->getUserId();
        $data['paid_amount'] = 0;
        $data['remaining_amount'] = $data['amount'];
        $data['is_closed'] = 0;

        DB::transaction(function() use (&$pLoan, $data) {
            $pLoan = insert(MainSalaryEmployeePLoan::class, $data, true);
            $this->generateInstallments($pLoan, $data['next_installment_date']);
        });

        return $pLoan;
    }

    public function updatePLoan($id, $data)
    {
        $loan = $this->getById($id);
        if (!$loan || $loan->is_archived == 1 || $loan->is_disbursed == 1) {
            throw new \Exception('لا يمكن تعديل السلفة');
        }

        DB::transaction(function() use ($loan, $data, $id) {
            $loan->mainSalaryEmployeePLoanInstallments()->delete();
            $data['updated_by'] = $this->getUserId();
            $data['remaining_amount'] = $data['amount'];
            $data['paid_amount'] = 0;
            
            update($loan, $data);
            $this->generateInstallments($loan, $data['next_installment_date']);
        });

        return $loan;
    }

    public function deletePLoan($id)
    {
        $loan = $this->getById($id);
        if (!$loan || $loan->is_archived == 1 || $loan->is_disbursed == 1) {
            throw new \Exception('عفوا لا يمكن حذف السلفة');
        }

        DB::transaction(function() use ($loan) {
            $loan->mainSalaryEmployeePLoanInstallments()->delete();
            destroy($loan);
        });

        return true;
    }

    public function disbursePLoan($id, $userId)
    {
        $loan = $this->getById($id);
        if (!$loan || $loan->is_archived == 1 || $loan->is_disbursed == 1) {
            throw new \Exception('عفوا لا يمكن صرف السلفة');
        }

        DB::transaction(function() use ($loan, $userId) {
            $loan->update([
                'is_disbursed' => 1,
                'disbursed_by' => $userId,
                'disbursed_at' => now(),
                'updated_by' => $userId,
            ]);

            $this->recalculateSalary($loan->employee_id);
        });

        return $loan;
    }

    public function payInstallmentCash($installmentId, $userId)
    {
        $installment = MainSalaryEmployeePLoanInstallment::where('company_id', $this->getCompanyId())
            ->where('id', $installmentId)
            ->where('is_archived', 0)
            ->where('installment_status', '0')
            ->first();

        if (empty($installment)) {
            throw new \Exception('عفواً، القسط غير متاح للدفع كاش');
        }

        $loan = $installment->mainSalaryEmployeePLoan;
        if (!$loan || $loan->is_archived == 1 || $loan->is_disbursed == 0) {
            throw new \Exception('عفواً، السلفة الأساسية غير صالحة أو مغلقة');
        }

        $firstEligible = $loan->mainSalaryEmployeePLoanInstallments()
            ->where('is_archived', 0)
            ->where('installment_status', '0')
            ->orderBy('id', 'asc')
            ->first();

        if (!$firstEligible || $firstEligible->id !== $installment->id) {
            throw new \Exception('عفواً، يجب سداد الأقساط بالترتيب المستحق');
        }

        DB::transaction(function () use ($installment, $loan, $userId) {
            $installment->update([
                'installment_status' => '2',
                'is_archived' => 1,
                'archived_by' => $userId,
                'archived_at' => now(),
                'updated_by' => $userId,
                'notes' => $installment->notes ? $installment->notes . ' (تم سداده نقداً بشكل مباشر)' : 'تم سداد القسط نقداً بشكل مباشر',
            ]);

            $this->updateParentLoanStats($loan);
            $this->recalculateSalary($loan->employee_id);
        });

        return $loan;
    }

    public function reschedule($loanId, $cashPayment, $numberOfMonths, $startDate, $userId)
    {
        $loan = $this->getById($loanId);
        if (empty($loan) || $loan->is_archived == 1 || $loan->is_disbursed == 0) {
            throw new \Exception('عفواً، السلفة غير صالحة أو مؤرشفة بالفعل');
        }

        $firstAvailableInstallment = $loan->mainSalaryEmployeePLoanInstallments()
            ->where('is_archived', 0)
            ->where('installment_status', '0')
            ->orderBy('id', 'asc')
            ->first();

        DB::transaction(function () use ($loan, $cashPayment, $numberOfMonths, $startDate, $userId, $firstAvailableInstallment) {
            // Delete unarchived installments
            $loan->mainSalaryEmployeePLoanInstallments()->where('is_archived', 0)->delete();

            // Cash payment if specified
            if ($cashPayment > 0) {
                $cash_payment_month = $firstAvailableInstallment ? $firstAvailableInstallment->next_installment_year_and_month : date('Y-m');
                MainSalaryEmployeePLoanInstallment::create([
                    'employee_id' => $loan->employee_id,
                    'main_salary_employee_p_loan_id' => $loan->id,
                    'amount' => $loan->amount,
                    'installment_amount_monthly' => $cashPayment,
                    'next_installment_year_and_month' => $cash_payment_month,
                    'installment_status' => '2',
                    'is_archived' => 1,
                    'archived_by' => $userId,
                    'archived_at' => now(),
                    'company_id' => $this->getCompanyId(),
                    'added_by' => $userId,
                    'notes' => 'دفعة نقدية فورية عند إعادة الجدولة',
                ]);
            }

            // Calculate new remaining balance
            $remainingBalance = floatval($loan->remaining_amount) - $cashPayment;

            if ($remainingBalance > 0) {
                $newInstallmentAmount = round($remainingBalance / $numberOfMonths, 2);

                $next_installment_year_and_month = date('Y-m', strtotime($startDate));
                for ($i = 1; $i <= $numberOfMonths; $i++) {
                    MainSalaryEmployeePLoanInstallment::create([
                        'employee_id' => $loan->employee_id,
                        'main_salary_employee_p_loan_id' => $loan->id,
                        'amount' => $loan->amount,
                        'installment_amount_monthly' => $newInstallmentAmount,
                        'next_installment_year_and_month' => $next_installment_year_and_month,
                        'installment_status' => '0',
                        'company_id' => $this->getCompanyId(),
                        'added_by' => $userId,
                        'notes' => 'قسط مجدول جديد بعد إعادة الجدولة',
                    ]);
                    $next_installment_year_and_month = date('Y-m', strtotime($next_installment_year_and_month . ' + 1 month'));
                }
            }

            $this->updateParentLoanStats($loan);
            $this->recalculateSalary($loan->employee_id);
        });

        return $loan;
    }

    public function updateParentLoanStats(MainSalaryEmployeePLoan $loan)
    {
        $totalPaid = MainSalaryEmployeePLoanInstallment::where('main_salary_employee_p_loan_id', $loan->id)
            ->whereIn('installment_status', ['1', '2'])
            ->sum('installment_amount_monthly');

        $loan->update([
            'paid_amount' => $totalPaid,
            'remaining_amount' => max(0, floatval($loan->amount) - $totalPaid),
            'updated_by' => $this->getUserId(),
        ]);

        $totalInstallments = MainSalaryEmployeePLoanInstallment::where('main_salary_employee_p_loan_id', $loan->id)->count();
        $paidAndArchived = MainSalaryEmployeePLoanInstallment::where('main_salary_employee_p_loan_id', $loan->id)
            ->whereIn('installment_status', ['1', '2'])
            ->where('is_archived', 1)
            ->count();

        if ($totalInstallments > 0 && $totalInstallments === $paidAndArchived) {
            $loan->update([
                'is_archived' => 1,
                'archived_by' => $this->getUserId(),
                'archived_at' => now(),
            ]);
        }
    }

    private function generateInstallments(MainSalaryEmployeePLoan $pLoan, $startDate)
    {
        $months = $pLoan->number_of_installment_months;
        $monthlyAmount = $pLoan->installment_amount_monthly;
        
        $currentDate = new \DateTime($startDate);

        for ($i = 0; $i < $months; $i++) {
            MainSalaryEmployeePLoanInstallment::create([
                'main_salary_employee_p_loan_id' => $pLoan->id,
                'employee_id' => $pLoan->employee_id,
                'amount' => $pLoan->amount,
                'installment_amount_monthly' => $monthlyAmount,
                'next_installment_year_and_month' => $currentDate->format('Y-m'),
                'installment_status' => 0,
                'company_id' => $pLoan->company_id,
                'added_by' => $this->getUserId(),
                'updated_by' => $this->getUserId(),
                'notes' => 'قسط مجدول تلقائياً عند إنشاء السلفة',
            ]);

            $currentDate->modify('+1 month');
        }
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