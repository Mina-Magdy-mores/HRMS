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
function destroy($object = null)
{
    return $object->delete();
}
function get_sum_where($model = null, $field_name = null, $where = array())
{
    return $model::where($where)->sum($field_name);
}
