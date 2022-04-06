<?php
namespace App\Models\HijackShowcase;

use Illuminate\Database\Eloquent\Model;

class Envato extends Model
{
    const TEAM_CODETHISLAB  = 1;
    // const TEAM_REDFOC       = 2;

    const TEAM_DESCRIPTION_LIST = array(
        self::TEAM_CODETHISLAB  => 'codethislab',
        // self::TEAM_REDFOC       => 'redfoc',
    );

    const TEAM_DOMAIN_LIST = array(
        self::TEAM_CODETHISLAB  => 'showcase.codethislab.com/games',
        // self::TEAM_REDFOC       => 'cdn.redfoc.com/demo',
    );

    const TEAM_DOMAIN_LIST = array(
        self::TEAM_CODETHISLAB  => array(
            'rugby_rush',
            'hurdles',
            'slalom_ski',
            'roulette_royale',
            'roulette',
            'wheel_of_fortune',
            'bingo',
            'jacks_or_better',
            'deuces_wild',
            'keno',
            'blackjack',
            'baccarat',
            'bouncing_eggs',
            'kittygram',
            'high_or_low',
            'master_checkers',
            'classic_nonogram',
            'slot_christmas',
            'slot_arabian',
            'slot_ramses',
            'slot_ultimate_soccer',
            'slot_mr_chicken',
            'slot_space_adventure',
            'slot_the_fruits',
            'word_search',
            'word_finder',
            'dreamlike_room',
            'bubble_shooter',
            'gold_miner',
            'snake',
            'klondike',
            'christmas_chain', // ZuMA
            'gummy_blocks',
            // 'waffle',
            'galactic_war',
            'city_blocks',
            'funny_faces', // click all same colors disappear
            'katana_fruit',
            'freecell',
            'coloring_animals',
            'jumper_frog',
            'flowers', // connect dots puzzle
            'miner_block', // take a block out puzzle
            'jumper_frog',
            'jumper_frog',
            'jumper_frog',
        ),
        // self::TEAM_CODETHISLAB  => array(
        //     'snake_ladder',
        // ),
    );
}
