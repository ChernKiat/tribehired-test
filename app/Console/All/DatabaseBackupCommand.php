<?php

namespace App\Console\All;

use DateInterval;
use Datetime;
use Illuminate\Console\Command;

class DatabaseBackupCommand extends Command
{
    protected $signature = 'DatabaseCommand:backup';

    protected $description = '';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $backup_directory = env('DB_BACKUP_DIRECTORY');
        $database = env('DB_DATABASE');
        $mysqldump = env('DB_MYSQLDUMP', 'mysqldump');
        $backup_keep_day = env('DB_BACKUP_KEEP_DAY', 7);

        if (!$backup_directory || !file_exists($backup_directory)) {
            $this->error("Databases backuped successfully. DB_BACKUP_DIRECTORY = {$backup_directory}");

            return false;
        }

        $now = new Datetime();
        for ($i = 0; $i < $backup_keep_day; $i++) {
            $prev = clone $now;
            $prev->sub(new DateInterval("P{$i}D"));
            $files = glob($backup_directory.DIRECTORY_SEPARATOR.$database.'-'.$prev->format('Ymd').'*.sql*');
            if ($files) {
                array_map(function ($file) {
                    unlink($file);
                }, $files);
            }
        }

        $filenames = array($database);
        $filenames[] = $now->format('Ymd-His');
        $full_filename = "{$backup_directory}/" . implode('-', $filenames) . '.sql';

        exec(sprintf("{$mysqldump} -u%s -p%s %s > %s", env('DB_USERNAME'), env('DB_PASSWORD'), env('DB_DATABASE'), $full_filename));

        $this->info("Databases backuped successfully. ({$full_filename})");
    }
}
