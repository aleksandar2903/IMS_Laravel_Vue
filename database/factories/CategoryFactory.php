<?php

namespace Database\Factories;

use App\Models\Model;
use App\Models\ProductCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProductCategory::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => 'kategorija 1'//$this->faker->name(),
        ];
    }
}
