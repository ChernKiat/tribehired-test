<?php

namespace App\Skins;

use Carbon\Carbon;
use Illuminate\Support\Str;

class FileSkin
{
    public $file;
    public $name_set;

    public $path;
    public $full_path;

    public function __construct($file)
    {
        $this->file      = $file;
        $this->name_set  = $this->nameSetup($file);
    }

    // save into storage
    public function save($folder, $useFileNameAsFolderName = false)
    {
        $nameWithoutExtension = "{$this->name_set['name']}-{$this->name_set['random']}";
        $name = "{$nameWithoutExtension}.{$this->name_set['extension']}";

        $this->path = "\\storage\\{$folder}";
        $this->full_path = storage_path($folder);
        if ($useFileNameAsFolderName) {
            $this->path .= "\\{$nameWithoutExtension}";
            $this->full_path .= "\\{$nameWithoutExtension}";
        }

        $this->file->move($this->full_path, $name);
        return $name;
    }

    protected function nameSetup($file)
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $random = Carbon::now()->timestamp . Str::random(10);
        $name = pathinfo(preg_replace('/[^A-Za-z0-9. \-]/', '', $file->getClientOriginalName()), PATHINFO_FILENAME);

        return compact('name', 'random', 'extension');
    }
}
