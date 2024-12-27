<?php

namespace Database\Factories;

use App\Enums\QuestionStatus;
use App\Enums\UserStatus;
use App\Models\Questions;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class QuestionsFactory extends Factory
{
    protected $model = Questions::class;

    public function definition()
    {
        $users = User::where('status', '!=', UserStatus::DELETED)->get();
        $dataUser = [];
        foreach ($users as $user) {
            $dataUser[] = $user->id;
        }

        return [
            'title' => $this->faker->sentence(),
            'content' => $this->faker->paragraph(),
            'user_id' => $this->faker->randomElement($dataUser),
            'status' => $this->faker->randomElement([QuestionStatus::ACTIVE, QuestionStatus::INACTIVE()]),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
