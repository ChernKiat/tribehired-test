<?php

namespace App\Tools\BoardGame;

class BoardGameTool
{
    public static function identityCategories($allPossibleCombinations)
    {
        $characterIndex = array(0, 1, 2);

        $output = array('set' => 0, 'same' => 0, 'different' => 0, 'others' => 0);
        // $output = array('set' => array(), 'same' => array(), 'different' => array(), 'others' => 0);
        foreach ($allPossibleCombinations as $combination) {
            // $booleanIndex = array(false, false, false);
            // foreach ($characterIndex as $key => $value) {
            //     if ( ($combination[0][$value] == $combination[1][$value] && $combination[1][$value] == $combination[2][$value]) ||
            //         ($combination[0][$value] != $combination[1][$value] && $combination[0][$value] != $combination[2][$value] && $combination[1][$value] != $combination[2][$value]) ) {
            //         $booleanIndex[$key] = true;
            //     }
            // }
            // if ($booleanIndex[0] && $booleanIndex[1] && $booleanIndex[2]) {
            //     $output['set']++;
            //     continue;
            // }
            if ( ($combination[0][0] == $combination[1][0] && $combination[1][0] == $combination[2][0]) ||
                ($combination[0][1] == $combination[1][1] && $combination[1][1] == $combination[2][1]) ||
                ($combination[0][2] == $combination[1][2] && $combination[1][2] == $combination[2][2]) ) {
                $output['same']++;
                continue;
            }
            if ( ($combination[0][0] != $combination[1][0] && $combination[0][0] != $combination[2][0] && $combination[1][0] != $combination[2][0]) ||
                ($combination[0][1] != $combination[1][1] && $combination[0][1] != $combination[2][1] && $combination[1][1] != $combination[2][1]) ||
                ($combination[0][2] != $combination[1][2] && $combination[0][2] != $combination[2][2] && $combination[1][2] != $combination[2][2]) ) {
                $output['different']++;
                continue;
            }
        }
        return $output;
    }

    public static function generateAllPossibleCards($numberOfFeaturesVSNumberOfVariations)
    {
        $variationsSetup = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', '1', '2', '3', '4', '5', '6', '7', '8', '9', '0');

        // $numberOfFeaturesVSNumberOfCopies = an array of characters
        $output = array();
        foreach ($numberOfFeaturesVSNumberOfVariations as $feature => $numberOfVariation) {
            $copies = 1;
            foreach ($numberOfFeaturesVSNumberOfVariations as $key => $value) {
                if ($key <= $feature) { continue; }
                $copies *= $value;
            }

            if ($feature == 0) {
                for ($i = 0; $i < $numberOfVariation; $i++) {
                    for ($j = 0; $j < $copies; $j++) {
                        $output[] = $variationsSetup[$i];
                    }
                }
            } else {
                $current = 0;
                $remaining = $copies;
                foreach ($output as $key => $value) {
                    if ($remaining == 0) {
                        $current++;
                        $remaining = $copies;
                    }
                    if ($current == $numberOfVariation) { $current = 0; }
                    $output[$key] = $value . $variationsSetup[$current];
                    $remaining--;
                }
            }

        }
        return $output;
    }
}
