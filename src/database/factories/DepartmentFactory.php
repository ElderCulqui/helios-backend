<?php

namespace Database\Factories;

use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Department>
 */
class DepartmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'parent_id' => null,
            'ambassador_name' => $this->faker->boolean(50) ? $this->faker->name() : null,
        ];
    }

    /**
     * Create a department with a specific name
     */
    public function withName(string $name): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => $name,
        ]);
    }

    /**
     * Create a department with an ambassador
     */
    public function withAmbassador(string $ambassadorName): static
    {
        return $this->state(fn (array $attributes) => [
            'ambassador_name' => $ambassadorName ?? $this->faker->name(),
        ]);
    }

    /**
     * Create a department without an ambassador
     */
    public function withoutAmbassador(): static
    {
        return $this->state(fn (array $attributes) => [
            'ambassador_name' => null,
        ]);
    }

    /**
     * Create a department with a specific parent
     */
    public function withParent(Department $parent): static
    {
        return $this->state(fn (array $attributes) => [
            'parent_id' => $parent->id,
        ]);
    }

    /**
     * Create a department with a parent ID
     */
    public function withParentId(int $parentId): static
    {
        return $this->state(fn (array $attributes) => [
            'parent_id' => $parentId,
        ]);
    }
}
