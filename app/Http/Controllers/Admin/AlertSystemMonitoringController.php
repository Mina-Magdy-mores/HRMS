<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AdminPanelSetting;
use App\Models\AlertModule;
use App\Models\AlertMoveType;
use App\Models\AlertSystemMonitoring;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AlertSystemMonitoringController extends Controller
{
    public function index()
    {
        $company_id = Auth::user()->company_id;

        $general_settings = getColsWhere(AdminPanelSetting::class, [], ['*'], ['company_id' => $company_id], 'id', 'desc');
        $modules = AlertModule::orderBy('name', 'asc')->get();
        $moveTypes = AlertMoveType::select('name')->distinct()->orderBy('name', 'asc')->get();
        $admins = Admin::where('company_id', $company_id)->orderBy('name', 'asc')->get();

        $monitorings = AlertSystemMonitoring::with(['alertModule', 'alertMoveType', 'addedBy', 'employee'])
            ->where('company_id', $company_id)
            ->orderBy('id', 'desc')
            ->paginate(PAGEINATION_COUNTER);

        return view('admin.alert_system_monitoring.index', compact(
            'general_settings',
            'modules',
            'moveTypes',
            'admins',
            'monitorings'
        ));
    }

    public function toggleImportant(Request $request, $id)
    {
        try {
            $company_id = Auth::user()->company_id;
            $monitoring = AlertSystemMonitoring::where(['id' => $id, 'company_id' => $company_id])->firstOrFail();

            $monitoring->is_important = $monitoring->is_important == 1 ? 0 : 1;
            $monitoring->save();

            // Record self log
            \App\Models\AlertSystemMonitoringSelfLog::create([
                'company_id' => $company_id,
                'admin_id' => Auth::id() ?? Auth::guard('admin')->id() ?? 1,
                'action' => $monitoring->is_important == 1 ? 'تمييز سجل مراقبة' : 'إلغاء تمييز سجل مراقبة',
                'target_log_id' => $monitoring->id,
                'target_log_name' => $monitoring->name,
                'target_log_content' => $monitoring->content,
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'message' => $monitoring->is_important == 1 ? 'تم تمييز الحركة بنجاح' : 'تم إلغاء تمييز الحركة بنجاح',
                    'is_important' => $monitoring->is_important
                ]);
            }

            return redirect()->back()->with('success', 'تم تحديث أهمية الحركة بنجاح');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'حدث خطأ ما: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'حدث خطأ ما: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $company_id = Auth::user()->company_id;
            $monitoring = AlertSystemMonitoring::where(['id' => $id, 'company_id' => $company_id])->firstOrFail();

            // Record self log before deletion
            \App\Models\AlertSystemMonitoringSelfLog::create([
                'company_id' => $company_id,
                'admin_id' => Auth::id() ?? Auth::guard('admin')->id() ?? 1,
                'action' => 'حذف سجل مراقبة',
                'target_log_id' => $monitoring->id,
                'target_log_name' => $monitoring->name,
                'target_log_content' => $monitoring->content,
            ]);

            $monitoring->delete();

            return redirect()->route('admin.system-monitoring.index')->with('success', 'تم حذف سجل المراقبة بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ ما أثناء حذف السجل: ' . $e->getMessage());
        }
    }

    public function ajaxSearch(Request $request)
    {
        if ($request->ajax()) {
            $company_id = Auth::user()->company_id;

            $query = AlertSystemMonitoring::with(['alertModule', 'alertMoveType', 'addedBy', 'employee'])
                ->where('company_id', $company_id);

            // Filter by module
            if ($request->filled('alert_module_id')) {
                $query->where('alert_module_id', $request->alert_module_id);
            }

            // Filter by action name (move type name)
            if ($request->filled('action_name')) {
                $actionName = $request->action_name;
                $query->whereHas('alertMoveType', function ($q) use ($actionName) {
                    $q->where('name', $actionName);
                });
            }

            // Filter by admin
            if ($request->filled('added_by')) {
                $query->where('added_by', $request->added_by);
            }

            // Filter by importance
            if ($request->filled('is_important')) {
                $query->where('is_important', $request->is_important);
            }

            // Date From
            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            // Date To
            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            // Search term (Free-Text)
            if ($request->filled('search_term')) {
                $term = $request->search_term;
                $query->where(function ($q) use ($term) {
                    $q->where('name', 'like', "%{$term}%")
                      ->orWhere('content', 'like', "%{$term}%")
                      ->orWhere('notes', 'like', "%{$term}%");
                });
            }

            $monitorings = $query->orderBy('id', 'desc')->paginate(PAGEINATION_COUNTER);

            return view('admin.alert_system_monitoring.ajaxSearch', compact('monitorings'));
        }
    }

    public function selfLogs(Request $request)
    {
        $company_id = Auth::user()->company_id;

        $query = \App\Models\AlertSystemMonitoringSelfLog::with('admin')
            ->where('company_id', $company_id);

        if ($request->filled('search_term')) {
            $term = $request->search_term;
            $query->where(function ($q) use ($term) {
                $q->where('action', 'like', "%{$term}%")
                  ->orWhere('target_log_name', 'like', "%{$term}%")
                  ->orWhere('target_log_content', 'like', "%{$term}%");
            });
        }

        $selfLogs = $query->orderBy('id', 'desc')->paginate(PAGEINATION_COUNTER);

        if ($request->ajax()) {
            return view('admin.alert_system_monitoring.self_logs_table', compact('selfLogs'));
        }

        return view('admin.alert_system_monitoring.self_logs', compact('selfLogs'));
    }

    public function destroySelfLog($id)
    {
        try {
            $company_id = Auth::user()->company_id;
            $selfLog = \App\Models\AlertSystemMonitoringSelfLog::where(['id' => $id, 'company_id' => $company_id])->firstOrFail();
            $selfLog->delete();

            return redirect()->route('admin.system-monitoring.self-logs')->with('success', 'تم حذف سجل المراقبة الذاتية بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ ما أثناء حذف السجل: ' . $e->getMessage());
        }
    }
}
