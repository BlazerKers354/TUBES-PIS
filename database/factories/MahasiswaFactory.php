<?php

namespace Database\Factories;

use App\Models\Mahasiswa;
use Illuminate\Database\Eloquent\Factories\Factory;

class MahasiswaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Mahasiswa::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array 
    {
        return [
            'NIM'        => $this->faker->unique()->numerify('########'),
            'Nama'       => $this->faker->name(),
            'Alamat'     => $this->faker->address(),
            'Nama_Ayah'  => $this->faker->name('male'),
            'Nama_Ibu'   => $this->faker->name('female'),
        ];
    }
}
