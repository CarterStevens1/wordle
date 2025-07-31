<?php

namespace Database\Seeders;

use App\Models\Word;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class WordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $words = [
            'candy',
            'lance',
            'table',
            'flume',
            'biter',
            'plane',
            'apple',
            'house',
            'dream',
            'light',
            'brave',
            'climb',
            'ocean',
            'river',
            'stone',
            'music',
            'happy',
            'smile',
            'plant',
            'train',
            'chair',
            'spoon',
            'knife',
            'bread',
            'grape',
            'lemon',
            'peach',
            'mango',
            'berry',
            'cloud',
            'storm',
            'windy',
            'sunny',
            'rainy',
            'frost',
            'snowy',
            'chase',
            'dance',
            'laugh',
            'sleep',
            'awake',
            'write',
            'readz',
            'learn',
            'teach',
            'speak',
            'listen',
            'think',
            'solve',
            'watch',
            'movie',
            'actor',
            'stage',
            'drama',
            'story',
            'novel',
            'verse',
            'rhyme',
            'poets'
        ];

        foreach ($words as $index => $word) {
            Word::create([
                'word' => $word,
                'use_date' => Carbon::today()->addDays($index)
            ]);
        }
    }
}
