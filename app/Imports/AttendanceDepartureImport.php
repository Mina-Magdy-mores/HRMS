<?php

namespace App\Imports;

use App\Models\AttendanceDepartureActionsExcel;
use App\Models\MainSalaryEmployee;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class AttendanceDepartureImport implements ToCollection
{
    private int $finance_monthly_calendar_id;

    public function __construct(int $finance_monthly_calendar_id)
    {
        $this->finance_monthly_calendar_id = $finance_monthly_calendar_id;
    }
    public function collection(Collection $rows)
    {
        $company_id = Auth::user()->company_id;
        foreach ($rows as $row) {
            $dateTimeAction = null;
            if (!empty($row[3])) {
                if (is_numeric($row[3])) {
                    try {
                        $dateTimeAction = Date::excelToDateTimeObject($row[3])->format('Y-m-d H:i:s');
                    } catch (\Exception $e) {
                        $dateTimeAction = null;
                    }
                } else {
                    $normalizedDate = str_replace('/', '-', $row[3]);
                    $timestamp = strtotime($normalizedDate);
                    if ($timestamp !== false) {
                        $dateTimeAction = date('Y-m-d H:i:s', $timestamp);
                    }
                }
            }
            $type = null;
            if (!empty($row[4])) {
                if ($row[4] == "C/In") {
                    $type = 1;
                } elseif ($row[4] == "C/Out") {
                    $type = 2;
                }
            }

            $checkExisitsBefore = getColsWhereRow(new AttendanceDepartureActionsExcel(), ['id'], [
                'company_id' => $company_id,
                'finance_monthly_calendar_id' => $this->finance_monthly_calendar_id,
                'dateTimeAction' => $dateTimeAction,
                'employee_id' => $row[2],
                'type' => $type
            ]);
            if (empty($checkExisitsBefore)) {
                $checkExisitsMainSalaryEmployee  = getColsWhereRow(new MainSalaryEmployee(), ['id'], [
                    'company_id' => $company_id,
                    'employee_id' => $row[2],
                    'finance_monthly_calendar_id' => $this->finance_monthly_calendar_id
                ]);
                if (!empty($checkExisitsMainSalaryEmployee)) {
                    $dataToInsert['main_salary_employee_id'] = $checkExisitsMainSalaryEmployee['id'];
                }
                $dataToInsert['company_id'] = $company_id;
                $dataToInsert['finance_monthly_calendar_id'] = $this->finance_monthly_calendar_id;
                $dataToInsert['dateTimeAction'] = $dateTimeAction;
                $dataToInsert['employee_id'] = $row[2] ?? null;
                $dataToInsert['type'] = $type ?? null;
                $dataToInsert['added_by'] = Auth::id();
                $dataToInsert['notes'] = 'من خلال ملف Excel';
                     AttendanceDepartureActionsExcel::create($dataToInsert);
            }
        }
    }
}
