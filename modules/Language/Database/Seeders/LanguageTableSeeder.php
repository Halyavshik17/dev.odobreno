<?php

namespace Modules\Language\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Language\Entities\Language;

class LanguageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Language::truncate();

        $data = [
            [
                'title' => 'Русский',
                'code' => 'ru',
                'status' => 1,
            ], [
                'title' => 'English',
                'code' => 'en',
                'status' => 1,
            ],
        ];
        foreach ($data as $lan) {
            Language::create($lan);
        }
    }
}
