<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    private static array $departmentHierarchy = [
        'CEO' => [
            'Strategy' => [
                'Business Development',
                'Growth',
                'Research & Development'
            ],
            'Finance' => [
                'Compliance',
                'Legal'
            ],
            'Human Resources' => []
        ],
        'Operations' => [
            'Engineering' => [
                'Quality Assurance',
                'IT Support',
                'Security'
            ],
            'Product' => [
                'Design',
                'Data Analytics'
            ],
            'Customer Success' => []
        ],
        'Sales & Marketing' => [
            'Marketing' => [],
            'Sales' => []
        ]
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (self::$departmentHierarchy as $level1Name => $level2Departments) {
            $level1Department = Department::factory()->withName($level1Name)->create();

            foreach ($level2Departments as $level2Name => $level3Departments) {

                $level2Department = Department::factory()
                ->withName($level2Name)
                ->withParent($level1Department)
                ->create();

                foreach ($level3Departments as $level3Name) {
                    Department::factory()->withName($level3Name)
                      ->withParent($level2Department)
                      ->create();
                }
            }
        }
    }
}
