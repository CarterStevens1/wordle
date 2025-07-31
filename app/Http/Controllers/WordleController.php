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
        $todaysWord = Word::whereDate('use_date', Carbon::today())->first();
        if (!$todaysWord) {
            // Handle case where no word is scheduled for today
            return response()->json(['error' => 'No word scheduled for today. Please check the word schedule.'], 500);
        }

        $wordOfTheDay = $todaysWord->word;

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
            }
        };

        // Loop through and check if character is present in the word of the day
        for ($i = 0; $i < 5; $i++) {
            // Check to make sure letter is not correct or null
            if ($tempGuess[$i] !== null && $tempGuess[$i] !== $tempWord[$i]) {
                $foundIndex = array_search($tempGuess[$i], $tempWord);
                if ($foundIndex !== false) {
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
