<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'titulo'          => fake()->sentence(3),
            'descricao'       => fake()->paragraph(),
            'data_publicacao' => fake()->date(),
            'author_id'       => \App\Models\Author::factory(),
            'capa'            => null, // Por padrão, sem capa
        ];
    }

    /**
     * Indica que o livro deve ter uma capa.
     */
    public function withCover(): static
    {
        return $this->state(fn (array $attributes) => [
            'capa' => 'capas/fake_cover_' . fake()->uuid() . '.jpg',
        ]);
    }
}
