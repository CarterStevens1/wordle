document.addEventListener('DOMContentLoaded', () => {
    const gameBoard = document.getElementById('game-board');
    const guessInput = document.getElementById('guess-input');
    const guessForm = document.getElementById('guess-form');
    const messageArea = document.getElementById('message-area');

    const WORD_LENGTH = 5;
    const MAX_NUM_GUESSES = 6;
    let currentRow = 0;
    let gameOver = false;

    // Create the game board
    function createGameBoard() {
        gameBoard.innerHTML = ''; // reset the game board to empty
        for (let i = 0; i < MAX_NUM_GUESSES; i++) {
            const row = document.createElement('div');
            row.classList.add('row');
            for (let j = 0; j < WORD_LENGTH; j++) {
                const box = document.createElement('div');
                box.classList.add('letter-box');
                box.dataset.row = i;
                box.dataset.col = j;
                row.appendChild(box);
            }
            gameBoard.appendChild(row);
        }
    }

    createGameBoard();

    guessForm.addEventListener('submit', (e) => {
        e.preventDefault();
        submitGuess();
    });

    async function submitGuess() {

        // Check if game is over
        if (gameOver) {
            showMessage('Game over!');
            return;
        }

        // Get the guess from the input field
        const guess = guessInput.value.toLowerCase();

        if (guess === '' || !guess.match(/^[a-zA-Z ]+$/) || guess.length !== WORD_LENGTH) {
            showMessage('Please enter a valid guess.');
            return;
        }

        try {
            const response = await fetch('/api/guess', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ guess }),
            });

            const data = await response.json();
            // Update board function to handle the respponse
            updateBoard(guess, data.result);

            // Check game win/lose conditions
            if (data.isCorrect) {
                showMessage('Congratulations, you win!');
                gameOver = true;
            } else if (currentRow === MAX_NUM_GUESSES - 1) {
                showMessage(`Game over!`);
                gameOver = true;
            } else {
                currentRow++;
                showMessage('');
            }

            guessInput.value = '';
            guessInput.focus();
        } catch (error) {
            console.error('Error:', error);
            showMessage(`Error: ${error.message}`);
        }
    };
    function updateBoard(guess, results) {
        // Select all letter boxes in the current row
        const currentBoxes = document.querySelectorAll(`.letter-box[data-row="${currentRow}"]`);
        for (let i = 0; i < WORD_LENGTH; i++) {
            const box = currentBoxes[i];
            box.textContent = guess[i].toUpperCase();
            // Apply the state classes to the letter boxes
            box.classList.add(results[i].state);

        }
    }


    function showMessage(message) {
        messageArea.textContent = message;
    }



});