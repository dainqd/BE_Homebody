<?php

namespace Database\Factories;

use App\Enums\AnswerStatus;
use App\Enums\QuestionStatus;
use App\Enums\UserStatus;
use App\Models\Questions;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class AnswersFactory extends Factory
{
    protected $model = \App\Models\Answers::class;

    public function definition()
    {
        $questions = Questions::where('status', '!=', QuestionStatus::DELETED)->get();
        $dataQuestion = [];
        foreach ($questions as $question) {
            $dataQuestion[] = $question->id;
        }

        $users = User::where('status', '!=', UserStatus::DELETED)->get();
        $dataUser = [];
        foreach ($users as $user) {
            $dataUser[] = $user->id;
        }

        return [
            'title' => $this->faker->sentence(),
            'content' => $this->faker->paragraph(),
            'user_id' => $this->faker->randomElement($dataUser),
            'question_id' => $this->faker->randomElement($dataQuestion),
            'status' => $this->faker->randomElement([AnswerStatus::ACTIVE, AnswerStatus::INACTIVE]),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
