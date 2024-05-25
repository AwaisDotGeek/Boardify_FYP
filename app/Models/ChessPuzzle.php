<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChessPuzzle extends Model
{
    protected $table = 'chess_puzzles'; // Replace 'your_table_name' with the actual name of your table
    protected $primaryKey = 'id'; // If your primary key column name is different from 'id', replace it here
    public $timestamps = false; // If your table does not have 'created_at' and 'updated_at' columns, set this to false

    // If you want to specify the fillable/protected attributes, you can define them here:
    // protected $fillable = ['PuzzleId', 'FEN', 'Moves', 'Rating', 'RatingDeviation', 'Popularity', 'NbPlays', 'Themes', 'GameUrl', 'OpeningTags'];

    // If you have custom attributes or relationships, you can define them here
}
