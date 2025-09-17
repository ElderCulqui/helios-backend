<?php

namespace App\Http\Requests;

use App\Models\Department;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DepartmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $departmentId = $this->route('department') ? $this->route('department')->id : null;

        return [
            'name' => [
                'required',
                'string',
                'max:45',
                Rule::unique('departments')->ignore($departmentId)
            ],
            'parent_id' => [
                'nullable',
                'exists:departments,id',
                function ($attribute, $value, $fail) use ($departmentId) {
                    if ($value && $departmentId && $value == $departmentId) {
                        $fail('A department cannot be a parent of itself.');
                        return;
                    }

                    if ($value && $departmentId && $this->wouldCreateCircularReference($departmentId, $value)) {
                        $fail('This parent-child relationship would create a circular reference in the hierarchy.');
                    }
                }
            ],
            'ambassador_name' => 'nullable|string|max:100',
        ];
    }

    private function wouldCreateCircularReference($departmentId, $parentId): bool
    {
        return $this->isDescendant($parentId, $departmentId);
    }

    private function isDescendant($childId, $ancestorId): bool
    {
        $visited = [];
        $current = $childId;

        while ($current && !in_array($current, $visited)) {
            $visited[] = $current;

            if ($current == $ancestorId) {
                return true;
            }

            $department = Department::find($current);
            $current = $department ? $department->parent_id : null;
        }

        return false;
    }
}
