<?php

namespace App\TicTacToe;

use App\Events\GameStarted;
use App\Events\MoveMade;
use App\Events\GameEnded;
use App\Models\User;

class TicTacToeGame
{
    protected $players = [];
    protected $board = [];
    protected string $gameUrl = '';
    protected $currentPlayerIndex = 0; // Index of the current player in $players array

    public function __construct(User $player1, User $player2, $gameUrl)
    {
        $this->players = [$player1, $player2];
        $this->board = $this->initializeBoard();
        $this->gameUrl = $gameUrl;
    }

    public function getBoard() {
        return $this -> board;
    }

    public function start()
    {
        $this->broadcastGameStarted();

//        while (!$this->isGameOver()) {
//            $this->makeMove();
//            $this->switchPlayer();
//        }

//        $winner = $this->getWinner();
//        $this->broadcastGameEnded($winner);
    }

    protected function initializeBoard()
    {
        // Implement logic to create and return the initial game board
        // For simplicity, you can use a 3x3 array to represent the board
        return [
            ['', '', ''],
            ['', '', ''],
            ['', '', ''],
        ];
    }

    protected function broadcastGameStarted()
    {
        event(new GameStarted($this->players[0], $this->players[1], $this->board, $this -> gameUrl));
    }

    protected function broadcastMoveMade($position)
    {
        event(new MoveMade($this->players[$this->currentPlayerIndex], $position, $this->gameUrl));
    }

    protected function broadcastGameEnded($winner)
    {
        event(new GameEnded($winner, $this->board));
    }

    protected function makeMove()
    {
        // Implement logic to get the move from the current player
        // For simplicity, assume the move is a position [row, column]
        $move = $this->players[$this->currentPlayerIndex]->makeMove($this->board);

        // Check if the move is valid
        if ($this->isValidMove($move)) {
            // Update the board with the move
            $this->board[$move[0]][$move[1]] = $this->getCurrentPlayerSymbol();

            // Broadcast the move to the players
            $this->broadcastMoveMade($move);
        } else {
            // Handle invalid move (e.g., ask the player to make a new move)
            // For simplicity, you can skip the turn or ask for a new move
        }
    }

    protected function isValidMove($move)
    {
        // Implement logic to check if the move is valid
        // For example, check if the chosen position is empty on the board
        $row = $move[0];
        $col = $move[1];

        return isset($this->board[$row][$col]) && $this->board[$row][$col] === '';
    }

    protected function switchPlayer()
    {
        // Switch to the other player for the next turn
        $this->currentPlayerIndex = 1 - $this->currentPlayerIndex;
    }

    protected function getCurrentPlayerSymbol()
    {
        // Implement logic to get the symbol (e.g., 'X' or 'O') of the current player
        return $this->players[$this->currentPlayerIndex]->getSymbol();
    }

    protected function isGameOver()
    {
        // Implement logic to check if the game is over
        // For example, check for a winner or a full board
        return $this->getWinner() !== null || $this->isBoardFull();
    }

    protected function getWinner()
    {
        // Implement logic to check for a winner (rows, columns, diagonals)
        // Return the winning player or null if there is no winner
        // For simplicity, you can implement a basic check for three in a row
        // You may want to create a separate method for each type of check
        // (e.g., checkRows, checkColumns, checkDiagonals)

        // Example check for rows
        for ($i = 0; $i < 3; $i++) {
            if ($this->board[$i][0] !== '' &&
                $this->board[$i][0] === $this->board[$i][1] &&
                $this->board[$i][1] === $this->board[$i][2]) {
                return $this->getPlayerBySymbol($this->board[$i][0]);
            }
        }

        // Example check for columns
        for ($j = 0; $j < 3; $j++) {
            if ($this->board[0][$j] !== '' &&
                $this->board[0][$j] === $this->board[1][$j] &&
                $this->board[1][$j] === $this->board[2][$j]) {
                return $this->getPlayerBySymbol($this->board[0][$j]);
            }
        }

        // Example check for diagonals
        if ($this->board[0][0] !== '' &&
            $this->board[0][0] === $this->board[1][1] &&
            $this->board[1][1] === $this->board[2][2]) {
            return $this->getPlayerBySymbol($this->board[0][0]);
        }

        if ($this->board[0][2] !== '' &&
            $this->board[0][2] === $this->board[1][1] &&
            $this->board[1][1] === $this->board[2][0]) {
            return $this->getPlayerBySymbol($this->board[0][2]);
        }

        return null; // No winner
    }

    protected function isBoardFull()
    {
        // Implement logic to check if the board is full
        // Return true if every position on the board is occupied
        foreach ($this->board as $row) {
            foreach ($row as $cell) {
                if ($cell === '') {
                    return false; // There is at least one empty cell
                }
            }
        }
        return true; // The board is full
    }

    protected function getPlayerBySymbol($symbol)
    {
        // Return the player object based on the player symbol (X or O)
        foreach ($this->players as $player) {
            if ($player->getSymbol() === $symbol) {
                return $player;
            }
        }
        return null;
    }
}
