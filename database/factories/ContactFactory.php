<?php

namespace Database\Factories;

use App\Enums\ContactStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contact>
 */
class ContactFactory extends Factory
{
    protected $model = \App\Models\Contact::class;

    public function definition()
    {
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'subject' => $this->faker->sentence(),
            'email' => $this->faker->email(),
            'message' => $this->faker->paragraph(),
            'status' => $this->faker->randomElement([ContactStatus::PENDING, ContactStatus::APPROVED, ContactStatus::REJECTED]),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
