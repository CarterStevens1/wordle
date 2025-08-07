<?php

namespace Database\Seeders;

use App\Models\Word;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class WordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fetch data from external API
        $response = Http::get('https://random-word-api.herokuapp.com/word?length=5&number=365');

        if ($response->successful()) {
            $data = $response->json();

            foreach ($data as $index => $word) {
                Word::create([
                    'word' => $word,
                    'use_date' => Carbon::today()->addDays($index)
                ]);
            }
        } else {
            $this->command->error('Failed to fetch data from API');
        }
    }
}
