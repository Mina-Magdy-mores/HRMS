<?php

namespace App\Services\HR;

use App\Services\BaseService;
use App\Models\MainEmployeesVacationsBalances;
use App\Traits\GeneralTrait;

class VacationBalanceService extends BaseService
{
    use GeneralTrait;

    public function __construct()
    {
        $this->setModel(MainEmployeesVacationsBalances::class);
    }

    public function updateBalance($id, $data)
    {
        $balance = $this->getById($id);
        if (empty($balance)) {
            throw new \Exception('السجل غير موجود');
        }

        $data['updated_by'] = $this->getUserId();
        update($balance, $data);

        // Propagate updates to subsequent months
        $this->reupdate_vacation($balance->employee_id);

        return $balance;
    }

    public function calculateBalance($employeeId)
    {
        $this->calculate_employees_vacations_balance($employeeId);
    }
}