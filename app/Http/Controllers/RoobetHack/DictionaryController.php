<?php
namespace App\Http\Controllers\RoobetHack;

use App\Models\RoobetHack\Dictionary;
use App\Http\Controllers\Controller;
use Storage;

class DictionaryController extends Controller
{
    public function keyout()
    {
        $value = 'fd79bb359d6c9db78bffabe4f43f77ec3d556f6e791f92eb5e060d4d19b94833';

        $string = '';
        $dictionaries = Dictionary::pluck('my', 'hexadecimal_number')->toArray();
        foreach (explode(',', chunk_split($value, 4, ',')) as $part) {
            if (empty($part)) { continue; }
            $string .= $dictionaries[$part];
        }

        dd(\Illuminate\Support\Str::contains(\Storage::get("public\\storage\\g.txt"), $string));
    }

    public function keyin()
    {
        $dictionaries = Dictionary::pluck('my', 'hexadecimal_number')->toArray();

        foreach (explode("\n", Storage::get("public\\storage\\1.txt")) as $value) {
            $string = '';
            foreach (explode(',', chunk_split($value, 4, ',')) as $part) {
                if (empty($part)) { continue; }
                $string .= $dictionaries[$part];
            }
            Storage::append("public\\storage\\g.txt", $string);
        }

        dd('lol');
    }

    public function setup()
    {
        for ($i = 0; $i < 65536; $i++) {
            $number = str_pad(dechex($i), 4, '0', STR_PAD_LEFT);
            Dictionary::updateOrCreate([
                'hexadecimal_number' => $number,
            ], [
                'hexadecimal_number' => $number, 'my' => mb_chr($i, 'utf8'),
            ]);
        }

        dd('lol');
    }

    public function convert()
    {
        dd(mb_chr(12103, 'utf8'), mb_ord('⽇', 'utf8'), 'lol');
    }
}
