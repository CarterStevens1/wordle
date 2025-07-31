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
            'guess' => 'required|min:5|max:5|string',
        ]);

        // Convert to lowercase to make it easier to compare
        $guessedWord = strtolower($request->input('guess'));

        // Determine the word of the day 
        $totalWords = Word::count();
        if ($totalWords === 0) {
            // Handle case where no words are in the database
            return response()->json(['error' => 'No words found in the database. Please seed the words table.'], 500);
        }

        $dayOfYear = Carbon::now()->dayOfYear;

        $wordOfTheDayIndex = ($dayOfYear - 1) % $totalWords;

        $wordOfTheDay = Word::skip($wordOfTheDayIndex)->first()->word;

        $result = []; // Array to store the validation result for each letter
        $tempWord = str_split($wordOfTheDay); // Convert word of the day to an array of characters
        $tempGuess = str_split($guessedWord); // Convert guessed word to an array of characters

        // Create a for loop to initialise each letter with absent initially
        for ($i = 0; $i < 5; $i++) {
            $result[$i] = ['letter' => $tempGuess[$i], 'state' => 'absent'];
        }

        // Loop through and check if character is correct
        for ($i = 0; $i < 5; $i++) {
            if ($tempGuess[$i] !== null && $tempGuess[$i] === $tempWord[$i]) {
                $result[$i]['state'] = 'correct';
                $tempWord[$i] = null; // Mark as used in the word of the day
                $tempGuess[$i] = null; // Mark as used in the guess
            }
        };

        // Loop through and check if character is present in the word of the day
        for ($i = 0; $i < 5; $i++) {
            if ($tempGuess[$i] !== null) { // If this letter hasn't been marked 'correct'
                // Check if the guessed letter exists anywhere in the remaining tempWord
                $foundIndex = array_search($tempGuess[$i], $tempWord);
                if ($foundIndex !== false) {
                    $result[$i]['state'] = 'present';
                    $tempWord[$foundIndex] = null; // Mark as used in the word of the day
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
