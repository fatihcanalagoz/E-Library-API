<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $category = ['issue','history','sci-fiction','comedy','horror','adventure',];
        return [
            'name' => $this->faker->word,
            'author' => $this->faker->name(),
            'publisher' => $this->faker->name(),
            'category' => $category[rand(0,5)],
            'page' => rand(100,500),
            'isbn' => rand(1000,2000),
            'user_id' => rand(1,2)
        ];
    }
}
