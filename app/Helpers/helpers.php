<?php
/*get some cols by pagination table */
function getColsWhereP($model = null, $with = [], $cols = [], $where = [], $orderBy = 'id', $orderType = 'asc', $paginate = PAGEINATION_COUNTER)
{
    return $model::with($with)->select($cols)->where($where)->orderBy($orderBy, $orderType)->paginate($paginate);
    //         $query = $model::select($cols)->with($with)->where($where)->orderBy($orderBy, $orderType);
// $query->ddRawSql();
}
/*get some cols  table */
function getColsWhere($model = null, $with = [], $cols = [], $where = [], $orderBy = 'id', $orderType = 'asc')
{
    return $model::with($with)->select($cols)->where($where)->orderBy($orderBy, $orderType)->first();
}
function getColsWhereget($model = null, $with = [], $cols = [], $where = [], $orderBy = 'id', $orderType = 'asc')
{
    return $model::with($with)->select($cols)->where($where)->orderBy($orderBy, $orderType)->get();
}

function uploadImage($folder, $image)
{
    $imageName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
    $imageName = Str::slug($imageName);
    $extension = $image->getClientOriginalExtension();
    $filename = $imageName . '_' . time() . '_' . uniqid() . '.' . $extension;
    $path = $image->storeAs($folder, $filename, 'public');
    if (!$path) {
        throw new \Exception('فشل في رفع الملف');
    }
    return $path;

}
/*get some cols by pagination table where 2 */
function get_cols_where2_p($model = null, $columns_names = array(), $where = array(), $where2field = null, $where2operator = null, $where2value = null, $order_field = "id", $order_type = "DESC", $pagination_counter = 13)
{
    return $model::select($columns_names)->where($where)->where($where2field, $where2operator, $where2value)->orderby($order_field, $order_type)->paginate($pagination_counter);
}
/*get some cols  table */
function get_cols_where($model = null, $columns_names = array(), $where = array(), $order_field = "id", $order_type = "DESC")
{
    return $model::select($columns_names)->where($where)->orderby($order_field, $order_type)->get();
}
/*get some cols  table */
function get_cols_where_limit($model = null, $columns_names = array(), $where = array(), $order_field = "id", $order_type = "DESC", $limit = 1)
{
    return $model::select($columns_names)->where($where)->orderby($order_field, $order_type)->limit($limit)->get();
}
/*get some cols  table 2 */
function get_cols_where_order2($model = null, $columns_names = array(), $where = array(), $order_field = "id", $order_type = "DESC", $order_field2 = "id", $order_type2 = "DESC", $pagination_counter = 13)
{
    return $model::select($columns_names)->where($where)->orderby($order_field, $order_type)->orderby($order_field2, $order_type2)->paginate($pagination_counter);
}
function get_cols_where_order2_with($model = null, $with = [], $columns_names = array(), $where = array(), $order_field = "id", $order_type = "DESC", $order_field2 = "id", $order_type2 = "DESC", $pagination_counter = 13)
{
    return $model::with($with)->select($columns_names)->where($where)->orderby($order_field, $order_type)->orderby($order_field2, $order_type2)->paginate($pagination_counter);
}
/*get some cols  table */
function get_cols($model = null, $columns_names = array(), $order_field = "id", $order_type = "DESC")
{
    return $model::select($columns_names)->orderby($order_field, $order_type)->get();
}
/*get some cols for one row on table */
function getColsWhereRow($model = null, $columns_names = [], $where = [])
{
    return $model::select($columns_names)->where($where)->first();

}
/*get some cols row table */
function get_cols_where2_row($model = null, $columns_names = array(), $where = array(), $where2 = "")
{
    return $model::select($columns_names)->where($where)->where($where2)->first();
}
/*get some cols row table order by */
function get_cols_where_row_orderby($model, $columns_names = array(), $where = array(), $order_field = "id", $order_type = "DESC")
{
    return $model::select($columns_names)->where($where)->orderby($order_field, $order_type)->first();
}
/*get some cols table */
function insert($model = null, $arrayToInsert = [], $returnData = false)
{
    $flag = $model::create($arrayToInsert);
    if ($returnData) {
        return getColsWhereRow($model, ['*'], $arrayToInsert);
    } else {
        return $flag;
    }
}
function get_field_value($model = null, $field_name = null, $where = array())
{
    return $model::where($where)->value($field_name);
}
function update($object = null, $data_to_update = [])
{
    return $object->update($data_to_update);
}
function updateWhere($object = null, $data_to_update = [],$where=[])
{
    return $object::where($where)->update($data_to_update);
}

function destroy($object = null)
{
    return $object->delete();
}
function get_sum_where($model = null, $field_name = null, $where = array())
{
    return $model::where($where)->sum($field_name);
}

function get_count_where($model = null, $where = array())
{
    return $model::where($where)->count();
}

function check_permission($subMenuName, $actionName)
{
    $admin = auth('admin')->user();
    if (!$admin) {
        return false;
    }
    if ($admin->is_master_admin) {
        return true;
    }
    if (!$admin->permission_role_id) {
        return false;
    }

    return \Illuminate\Support\Facades\DB::table('permission_roles_sub_menues_actions')
        ->join('permission_sub_menues_actions', 'permission_roles_sub_menues_actions.permission_sub_menu_action_id', '=', 'permission_sub_menues_actions.id')
        ->join('permission_sub_menues', 'permission_sub_menues_actions.permission_sub_menu_id', '=', 'permission_sub_menues.id')
        ->where('permission_roles_sub_menues_actions.permission_role_id', $admin->permission_role_id)
        ->where('permission_sub_menues.name', $subMenuName)
        ->where('permission_sub_menues_actions.name', $actionName)
        ->where('permission_sub_menues.is_active', 1)
        ->where('permission_sub_menues_actions.is_active', 1)
        ->exists();
}

function check_main_menu_permission($mainMenuName)
{
    $admin = auth('admin')->user();
    if (!$admin) {
        return false;
    }
    if ($admin->is_master_admin) {
        return true;
    }
    if (!$admin->permission_role_id) {
        return false;
    }

    return \Illuminate\Support\Facades\DB::table('permission_roles_main_menues')
        ->join('permission_main_menues', 'permission_roles_main_menues.permission_main_menu_id', '=', 'permission_main_menues.id')
        ->where('permission_roles_main_menues.permission_role_id', $admin->permission_role_id)
        ->where('permission_main_menues.name', $mainMenuName)
        ->where('permission_main_menues.is_active', 1)
        ->exists();
}

function check_sub_menu_permission($subMenuName)
{
    $admin = auth('admin')->user();
    if (!$admin) {
        return false;
    }
    if ($admin->is_master_admin) {
        return true;
    }
    if (!$admin->permission_role_id) {
        return false;
    }

    return \Illuminate\Support\Facades\DB::table('permission_roles_sub_menues')
        ->join('permission_sub_menues', 'permission_roles_sub_menues.permission_sub_menu_id', '=', 'permission_sub_menues.id')
        ->where('permission_roles_sub_menues.permission_role_id', $admin->permission_role_id)
        ->where('permission_sub_menues.name', $subMenuName)
        ->where('permission_sub_menues.is_active', 1)
        ->exists();
}

