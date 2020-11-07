<?php

namespace App\Http\Controllers\BoardGame;

use App\Http\Controllers\Controller;
use App\Models\VideoChannel\Playlist;
use App\Tools\BoardGame\BoardGameTool;
use drupol\phpermutations\Generators\Combinations;
use drupol\phpermutations\Generators\Permutations;
use Illuminate\Http\Request;

class BoardGameController extends Controller
{
    public function test()
    {
        $allPossibleCards = BoardGameTool::generateAllPossibleCards(array(3, 3, 3));
        $allPossibleCombinations = (new Combinations($allPossibleCards, 3))->toArray();
        $cardCategories = BoardGameTool::identityCategories($allPossibleCombinations);

        dddd($cardCategories);
    }
}
