<?php

namespace App\Imports;

use App\Models\AttendanceDeparture;
use App\Models\AttendanceDepartureActionsExcel;
use App\Models\Employee;
use App\Models\MainSalaryEmployee;
use App\Models\ShiftsType;
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
            $employee = getColsWhereRow(new Employee(), ['id','fixed_shift','shift_type_id','daily_work_hours'], [
                'company_id' => $company_id,
                'fingerprint_code' => $row[2]
            ]);
            if (!empty($employee)) {
                $checkExisitsBefore = getColsWhereRow(new AttendanceDepartureActionsExcel(), ['id'], [
                    'company_id' => $company_id,
                    'finance_monthly_calendar_id' => $this->finance_monthly_calendar_id,
                    'dateTimeAction' => $dateTimeAction,
                    'employee_id' => $employee['id'],
                    'type' => $type
                ]);
                if (empty($checkExisitsBefore)) {
                    $checkExisitsMainSalaryEmployee  = getColsWhereRow(new MainSalaryEmployee(), ['id'], [
                        'company_id' => $company_id,
                        'employee_id' => $employee['id'],
                        'finance_monthly_calendar_id' => $this->finance_monthly_calendar_id
                    ]);
                    if (!empty($checkExisitsMainSalaryEmployee)) {
                        $dataToInsert['main_salary_employee_id'] = $checkExisitsMainSalaryEmployee['id'];
                    }
                    $dataToInsert['company_id'] = $company_id;
                    $dataToInsert['finance_monthly_calendar_id'] = $this->finance_monthly_calendar_id;
                    $dataToInsert['dateTimeAction'] = $dateTimeAction;
                    $dataToInsert['employee_id'] = $employee['id'] ?? null;
                    $dataToInsert['type'] = $type ?? null;
                    $dataToInsert['added_by'] = Auth::id();
                    $dataToInsert['notes'] = 'من خلال ملف Excel';
                    AttendanceDepartureActionsExcel::create($dataToInsert);
                    //check the shift type first
                    if($employee['fixed_shift'] == 1){
                        $shiftData = getColsWhereRow(new ShiftsType(),['id','start_time','end_time','total_hours'], [
                            'company_id' => $company_id,
                            'id' => $employee['shift_type_id']
                        ]);
                        if(empty($shiftData)){
                           continue; 
                        }else{
                            $total_hours = $shiftData['total_hours'] ?? null;
                            
                        }
                    }else{
                        if($employee['daily_work_hours'] == 0 || empty($employee['daily_work_hours']) || $employee['daily_work_hours'] == null){
                            continue;
                        }else{
                            $total_hours = $employee['daily_work_hours'] ?? null;
                            
                        }
                    }
                    //check if there is an empty record
                    $checkfor_empty_record = getColsWhereRow(new AttendanceDeparture(),['id','status_move'], [
                        'company_id' => $company_id,
                        'employee_id' => $employee['id'],
                        'finance_monthly_calendar_id' => $this->finance_monthly_calendar_id,
                        'day_of_finger_print' => date('Y-m-d', strtotime($dateTimeAction)),
                        'checkInDateTime' => null,
                    ]);
                    if(!empty($checkfor_empty_record)){
                        if( $type == 1){
                            $checkfor_empty_record->destroy();

                        }
                    }
                    //get last record
                    $last = AttendanceDeparture::select('*')->where([
                        'company_id' => $company_id,
                        'employee_id' => $employee['id'],
                        'finance_monthly_calendar_id' => $this->finance_monthly_calendar_id,
                    ])
                    ->where('checkInDateTime' ,'!=', null)
                    ->where('checkInDateTime', '<=', date('Y-m-d', strtotime($dateTimeAction)))
                    ->orderBy('id', 'desc')->first();
                    if(!empty($last)){
                        
                    }else{
                        // will consider it as the first finger print in the current opened finance_monthly_calendar_id
                        // will prepare the insert array and will consider it as check in
                        $dataToInsert2['status_move'] = 1;
                        $dataToInsert2['finance_monthly_calendar_id'] = $this->finance_monthly_calendar_id;
                        $dataToInsert2['employee_id'] = $employee['id'];
                        $dataToInsert2['checkInDate'] = date('Y-m-d', strtotime($dateTimeAction));
                        $dataToInsert2['checkInTime'] = date('H:i:s', strtotime($dateTimeAction));
                        $dataToInsert2['checkInDateTime'] = date('Y-m-d H:i:s', strtotime($dateTimeAction));
                        $dataToInsert2['company_id'] = $company_id;


                        $dataToInsert2['added_by'] = Auth::id();
                        $dataToInsert2['notes'] = 'من خلال ملف Excel';
                        AttendanceDeparture::create($dataToInsert2);
                    }
                    
                }
            }
        }
    }
}
