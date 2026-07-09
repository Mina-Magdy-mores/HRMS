<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class BaseService
{
    protected $modelClass;
    protected static $columnsCache = [];

    /**
     * Set the model class dynamically.
     *
     * @param string $modelClass
     * @return $this
     */
    public function setModel($modelClass)
    {
        $this->modelClass = $modelClass;
        return $this;
    }

    /**
     * Get table columns listing, caching the result.
     *
     * @return array
     */
    protected function getTableColumns()
    {
        $class = $this->modelClass;
        if (!isset(self::$columnsCache[$class])) {
            $instance = new $class;
            self::$columnsCache[$class] = Schema::getColumnListing($instance->getTable());
        }
        return self::$columnsCache[$class];
    }

    /**
     * Check if table has a specific column.
     *
     * @param string $column
     * @return bool
     */
    protected function hasColumn($column)
    {
        return in_array($column, $this->getTableColumns());
    }

    /**
     * Get active company ID for the authenticated user.
     *
     * @return int|null
     */
    protected function getCompanyId()
    {
        $user = Auth::guard('admin')->user() ?? Auth::user();
        return $user ? $user->company_id : null;
    }

    /**
     * Get active authenticated user ID.
     *
     * @return int|null
     */
    protected function getUserId()
    {
        $user = Auth::guard('admin')->user() ?? Auth::user();
        return $user ? $user->id : null;
    }

    /**
     * Get all records matching company constraints.
     */
    public function getAll($columns = ['*'], $where = [], $orderBy = 'id', $orderType = 'asc')
    {
        $companyId = $this->getCompanyId();
        if ($companyId !== null && $this->hasColumn('company_id')) {
            $where['company_id'] = $companyId;
        }
        return get_cols_where($this->modelClass, $columns, $where, $orderBy, $orderType);
    }

    /**
     * Get paginated records matching company constraints.
     */
    public function getPaginated($with = [], $columns = ['*'], $where = [], $orderBy = 'id', $orderType = 'asc', $paginate = 13)
    {
        $companyId = $this->getCompanyId();
        if ($companyId !== null && $this->hasColumn('company_id')) {
            $where['company_id'] = $companyId;
        }
        return getColsWhereP($this->modelClass, $with, $columns, $where, $orderBy, $orderType, $paginate);
    }

    /**
     * Get single record by ID with company check.
     */
    public function getById($id, $columns = ['*'])
    {
        $companyId = $this->getCompanyId();
        $where = ['id' => $id];
        if ($companyId !== null && $this->hasColumn('company_id')) {
            $where['company_id'] = $companyId;
        }
        return getColsWhereRow($this->modelClass, $columns, $where);
    }

    /**
     * Insert a new record.
     */
    public function create($data)
    {
        $companyId = $this->getCompanyId();
        if ($companyId !== null && $this->hasColumn('company_id') && !isset($data['company_id'])) {
            $data['company_id'] = $companyId;
        }
        $userId = $this->getUserId();
        if ($userId !== null) {
            if ($this->hasColumn('added_by') && !isset($data['added_by'])) {
                $data['added_by'] = $userId;
            }
            if ($this->hasColumn('updated_by') && !isset($data['updated_by'])) {
                $data['updated_by'] = $userId;
            }
            if ($this->hasColumn('created_by') && !isset($data['created_by'])) {
                $data['created_by'] = $userId;
            }
        }
        return insert($this->modelClass, $data, true);
    }

    /**
     * Update an existing record.
     */
    public function update($id, $data)
    {
        $record = $this->getById($id);
        if (!$record) {
            return null;
        }
        $userId = $this->getUserId();
        if ($userId !== null) {
            if ($this->hasColumn('updated_by') && !isset($data['updated_by'])) {
                $data['updated_by'] = $userId;
            }
        }
        update($record, $data);
        return $record;
    }

    /**
     * Delete a record.
     */
    public function delete($id)
    {
        $record = $this->getById($id);
        if (!$record) {
            return false;
        }
        return destroy($record);
    }

    /**
     * Check if a record exists matching the given where constraints (and company_id).
     */
    public function checkExists($where, $excludeId = null)
    {
        $companyId = $this->getCompanyId();
        if ($companyId !== null && $this->hasColumn('company_id')) {
            $where['company_id'] = $companyId;
        }
        $query = $this->modelClass::where($where);
        if ($excludeId !== null) {
            $query->where('id', '!=', $excludeId);
        }
        return $query->exists() ? $query->first() : null;
    }
}
