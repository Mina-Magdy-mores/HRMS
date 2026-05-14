<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branche;
use Auth;
use Illuminate\Http\Request;

class BrancheController extends Controller
{
    public function index()
    {
                $company_id = Auth::user()->company_id;

        $branches = getColsWhereP(Branche::class, ['createdBy'], ['*'], ['company_id' => $company_id], 'id', 'desc', PAGEINATION_COUNTER);
        return view('admin.branches.index', compact('branches'));
    }
    public function create()
    {
        return view('admin.branches.create');
    }
}
