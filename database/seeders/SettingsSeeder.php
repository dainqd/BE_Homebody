<?php

namespace Database\Seeders;

use App\Models\Settings;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'og_title' => 'GrowEase Site',
            'og_des' => 'Welcome to my GrowEase site!',
            'og_url' => 'https://homebodyglam.com',
            'og_site' => 'GrowEase Site',
            'brand_name' => 'GrowEase',
            'home_name' => 'GrowEase',
            'qna_email' => 'support@growease.com',
            'browser_title' => 'My GrowEase Site - Best Place to Be',
            'meta_tag' => 'growease, best, site',
            'meta_keyword' => 'growease, site, best',
            'domain_url' => 'https://homebodyglam.com',
            'email' => 'info@growease.com',
            'phone' => '+1234567890',
            'address' => '123 Awesome St.',
            'address_detail' => 'Suite 101',
            'zip' => '12345',
            'owner_name' => 'John Doe',
            'owner_phone' => '+1234567890',
            'owner_email' => 'admin@growease.com',
            'fax' => '+1234567890',
            'business_number' => '1234567890',
            'logo' => asset('storage/setting/logo.png'),
            'og_img' => asset('storage/setting/og_image.png'),
            'favicon' => asset('storage/setting/favicon.ico'),
            'thumbnail' => implode(',', [
                asset('storage/setting/thumbnail1.png'),
                asset('storage/setting/thumbnail2.png'),
            ])
        ];

        Settings::updateOrCreate([], $data);
    }
}
