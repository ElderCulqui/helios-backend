<?php

namespace App\Http\Controllers;

use App\Http\Requests\DepartmentRequest;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $limit = $request->query('limit', 10);
        $orderBy = $request->query('orderby', 'name');
        $orderDirection = $request->query('order', 'asc');
        $name = $request->query('name', null);
        $nameArray = explode(',', $name);

        $ambassadorName = $request->query('ambassador_name', null);
        $level = $request->query('level', null);
        $parentName = $request->query('parent_name', null);

        $departments = Department::query()
            ->when($name, function ($query) use ($nameArray) {
                $query->whereIn('name', $nameArray);
            })
            ->when($ambassadorName, function ($query) use ($ambassadorName) {
                $query->where('ambassador_name', 'like', '%' . $ambassadorName . '%');
            })
            ->when($level, function ($query) use ($level) {
                $query->where('level', $level);
            })
            ->when($parentName, function ($query) use ($parentName) {
                $query->whereHas('parent', function ($q) use ($parentName) {
                    $q->where('name', 'like', '%' . $parentName . '%');
                });
            })
            ->orderBy($orderBy, $orderDirection)
            ->paginate($limit);

        $modifiedDepartments = $departments->getCollection()->map(function ($item) use ($request) {
            $item->append('parent_name', 'children_count');
            return $item;
        });
        $departments->setCollection($modifiedDepartments);

        return response()->json($departments);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DepartmentRequest $request)
    {
        $data = $request->validated();
        $department = Department::create($data);

        return response()->json($department, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Department $department)
    {
        return response()->json($department);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DepartmentRequest $request, Department $department)
    {
        $data = $request->validated();
        $department->update($data);

        return response()->json($department);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department)
    {
        $department->delete();
        return response()->json('Department delete successful');
    }

    public function getSubdepartments(Department $department)
    {
        $subDepartments = $department->children;

        return response() ->json($subDepartments);
    }
}
