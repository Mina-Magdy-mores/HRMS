<?php

namespace App\Services\HR;

use App\Services\BaseService;
use App\Models\MainEmployeeInvestigation;
use Illuminate\Support\Facades\DB;

class InvestigationService extends BaseService
{
    public function __construct()
    {
        $this->setModel(MainEmployeeInvestigation::class);
    }

    public function storeInvestigation($data)
    {
        $data['company_id'] = $this->getCompanyId();
        $data['added_by'] = $this->getUserId();
        $data['updated_by'] = $this->getUserId();
        
        return insert(MainEmployeeInvestigation::class, $data);
    }

    public function updateInvestigation($id, $data)
    {
        $investigation = $this->getById($id);
        if (!$investigation) {
            throw new \Exception('التحقيق غير موجود');
        }

        if ($investigation->is_closed == 1) {
            throw new \Exception('عفواً لا يمكن تعديل تحقيق مغلق');
        }

        $data['updated_by'] = $this->getUserId();
        update($investigation, $data);
        
        return $investigation;
    }

    public function destroyInvestigation($id)
    {
        $investigation = $this->getById($id);
        if (!$investigation) {
            throw new \Exception('التحقيق غير موجود');
        }

        if ($investigation->is_closed == 1) {
            throw new \Exception('عفواً لا يمكن حذف تحقيق مغلق');
        }

        destroy($investigation);
        return true;
    }
}