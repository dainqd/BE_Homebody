<?php

namespace Database\Factories;

use App\Enums\CouponStatus;
use App\Enums\UserStatus;
use App\Models\Coupons;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<Coupons>
 */
class CouponsFactory extends Factory
{
    protected $model = Coupons::class;

    public function definition()
    {
        $users = User::where('status', '!=', UserStatus::DELETED)->get();
        $dataUser = [];
        foreach ($users as $user) {
            $dataUser[] = $user->id;
        }

        return [
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->sentence(),
            'code' => strtoupper(Str::random(10)),
            'type' => $this->faker->randomElement(['percentage', 'fixed']),
            'discount_percent' => $this->faker->numberBetween(5, 50), // 5% to 50%
            'max_discount' => $this->faker->numberBetween(10000, 100000), // In your currency unit
            'max_set' => $this->faker->numberBetween(1, 5),
            'min_total' => $this->faker->numberBetween(50000, 200000),
            'status' => $this->faker->randomElement([CouponStatus::ACTIVE, CouponStatus::INACTIVE]),
            'thumbnail' => $this->faker->imageUrl(),
            'quantity' => $this->faker->numberBetween(1, 100),
            'number_used' => $this->faker->numberBetween(0, 50),
            'start_time' => $this->faker->dateTimeBetween('-1 week', 'now'),
            'end_time' => $this->faker->dateTimeBetween('now', '+1 month'),
            'created_by' => $this->faker->randomElement($dataUser),
            'created_at' => now(),
            'updated_at' => now(),
            'name_en' => $this->faker->words(3, true),
            'name_cn' => $this->faker->words(3, true),
            'name_vi' => $this->faker->words(3, true),
            'description_en' => $this->faker->sentence(),
            'description_cn' => $this->faker->sentence(),
            'description_vi' => $this->faker->sentence(),
            'max_discount_en' => $this->faker->numberBetween(10000, 100000),
            'max_discount_cn' => $this->faker->numberBetween(10000, 100000),
            'max_discount_vi' => $this->faker->numberBetween(10000, 100000),
            'min_total_en' => $this->faker->numberBetween(50000, 200000),
            'min_total_cn' => $this->faker->numberBetween(50000, 200000),
            'min_total_vi' => $this->faker->numberBetween(50000, 200000),
        ];
    }
}
