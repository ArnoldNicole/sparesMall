<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'year_of_manufacture' => $this->faker->year,
            'price' => $this->faker->numberBetween(1000,9999),
            'image_url' => '1612538664.jpg', 
            'country'=>$this->faker->country,
            'manufacturer'=>$this->faker->company,
            'color'=>$this->faker->colorName,
            'size'=>$this->faker->word,
            'description'=>$this->faker->realText(800),
            'category_id'=>1,
        ];
    }
}
