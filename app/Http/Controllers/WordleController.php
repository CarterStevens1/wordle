<?php


namespace App\Http\Controllers;

use App\Models\Word;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class WordleController extends Controller
{
    public function guess(Request $request)
    {
        // Validate
        $request->validate([
            'guess' => 'required|min:5|max:5|string|alpha',
        ]);



        // Convert to lowercase to make it easier to compare
        $guessedWord = strtolower($request->input('guess'));

        // Determine the word of the day 
        $todaysWord = Word::whereRaw("DATE(use_date) = DATE('now')")->first();
        if (!$todaysWord) {
            return response()->json(['error' => 'No word scheduled for today. Please check the word schedule.'], 500);
        }

        $wordOfTheDay = $todaysWord->word;

        $result = []; // Array to store the validation result for each letter
        $tempWord = str_split($wordOfTheDay);
        $tempGuess = str_split($guessedWord);

        // Create a for loop to initialise each letter with absent initially
        for ($i = 0; $i < 5; $i++) {
            $result[$i] = ['letter' => $tempGuess[$i], 'state' => 'absent'];
            if ($tempGuess[$i] !== null && $tempGuess[$i] === $tempWord[$i]) {
                $result[$i]['state'] = 'correct';
            } else if ($tempGuess[$i] !== null && $tempGuess[$i] !== $tempWord[$i]) {
                $foundIndex = array_search($tempGuess[$i], $tempWord);
                if ($foundIndex) {
                    $result[$i]['state'] = 'present';
                }
            }
        }


        // Determine if the guessed word is entirely correct
        $isCorrect = ($guessedWord === $wordOfTheDay);

        // Return the validation results, whether the guess was correct, and the word of the day (for debugging/game over state)
        return response()->json([
            'result' => $result,
            'isCorrect' => $isCorrect,
        ]);
    }
}
