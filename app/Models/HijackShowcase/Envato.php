<?php
namespace App\Models\HijackShowcase;

use Illuminate\Database\Eloquent\Model;

class Envato extends Model
{
    const TEAM_DEMONISBLACK  = 1;
    const TEAM_CODETHISLAB   = 2;
    // const TEAM_REDFOC       = 3;

    const TEAM_DESCRIPTION_LIST = array(
        self::TEAM_DEMONISBLACK  => 'demonisblack.',
        self::TEAM_CODETHISLAB   => 'codethislab',
        // self::TEAM_REDFOC       => 'redfoc',
    );

    const TEAM_DOMAIN_LIST = array(
        self::TEAM_DEMONISBLACK  => 'demonisblack.com/code',
        self::TEAM_CODETHISLAB   => 'showcase.codethislab.com/games',
        // self::TEAM_REDFOC        => 'cdn.redfoc.com/demo',
    );

    const TEAM_PROJECT_LIST = array(
        self::TEAM_DEMONISBLACK  => array(
            'easterday'                       => '2022/easterday/app',
            'findobjects'                     => '2022/findobjects/game',
            'learndrawing'                    => '2022/learndrawing/game',
            'superplinko'                     => '2021/superplinko/game',
            'holidaygreetingcards_staysafe'   => '2021/holidaygreetingcards/app/cards/staysafe/index.html',
            'holidaygreetingcards_valentine'  => '2021/holidaygreetingcards/app/cards/valentine/index.html',
            'circlepuzzle'                    => '2020/circlepuzzle/game',
            'superplinko'                     => '2021/superplinko/game',
            'defusethebomb'                   => '2021/defusethebomb/game',
            'fightvirus'                      => '2020/fightvirus/game',
            'cubemove'                        => '2020/cubemove/game',
            'calcudoku'                       => '2019/calcudoku/game',
            'drumbeats'                       => '2019/drumbeats/game',
            'survivalgame'                    => '2021/survivalgame/game',
            'playmaze'                        => '2019/playmaze/game',
            'rearrangeletters2'               => '2019/rearrangeletters2/game',
            'junglescratch'                   => '2017/junglescratch/game',
            'slotmachine'                     => '2019/slotmachine/game',
            'linebright'                      => '2017/linebright/game',
            'lotterynumbers'                  => '2017/lotterynumbers/game',
            'drawaline'                       => '2017/drawaline/game',
            'calcudoku'                       => '2017/junglescratch/game',
        ),
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
        ),
        // self::TEAM_CODETHISLAB  => array(
        //     'snake_ladder',
        // ),
    );

    public static function link($team, $link)
    {
        if (array_key_exists($team, self::TEAM_DOMAIN_LIST) && array_key_exists($link, self::TEAM_PROJECT_LIST[$team])) {
            switch ($team) {
                case self::TEAM_DEMONISBLACK:
                    return $project = 'https://' . self::TEAM_DOMAIN_LIST[$team] . '/' . self::TEAM_PROJECT_LIST[$team][$link];
                    break;
                case self::TEAM_CODETHISLAB:
                // case self::TEAM_REDFOC:
                default:
                    return $project = 'https://' . self::TEAM_DOMAIN_LIST[$team] . '/' . $link;
                    break;
            }
        } else {
            abort(404);
        }
    }
}
