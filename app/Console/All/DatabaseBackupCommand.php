<?php

namespace App\Console\All;

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
        $backupdir = env('DB_BACKUPDIR');
        $database = env('DB_DATABASE');
        $mysqldump = env('DB_MYSQLDUMP', 'mysqldump');
        $backupdayretain = env('DB_BACKUPDAYRETAIN', 14);

        if (!$backupdir || !file_exists($backupdir)) {
            $this->error('failed to backup database, please check your setting.');
            $this->error("DB_BACKUPDIR={$backupdir}");
            return false;
        }

        $now = new \Datetime();
        for ($i = $backupdayretain, $j = $backupdayretain + 7; $i < $j; $i++) {
            $prev = clone $now;
            $prev->sub(new \DateInterval('P'.$i.'D'));
            $files = glob($backupdir.DIRECTORY_SEPARATOR.$database.'-'.$prev->format('Ymd').'*.sql*');
            if ($files) {
                array_map(function ($f) {
                    unlink($f);
                    // $this->info($f);
                }, $files);
            }
        }

        $filenames = [$database];
        $filenames[] = $now->format('Ymd-His');
        $fullFileName = $backupdir."/".implode('-', $filenames).".sql";

        $command = sprintf("{$mysqldump} -u%s -p%s %s > %s", env('DB_USERNAME'), env('DB_PASSWORD'), env('DB_DATABASE'), $fullFileName);

        exec($command);

        $this->info('BackupDatabase completed ('.$fullFileName.')');
    }
}
