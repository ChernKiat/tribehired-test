<?php

namespace App\Tools;

use Exception;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class CommandTool
{
    public static function run($command, $isThrowable = true)
    {
        // try {
        //     $output = self::runProcess($command);
        // } catch (Exception $e) {
            $output = self::runShell($command, $isThrowable);
        // }

        return $output;
    }

    public static function runShellWithWarning($command)
    {
        ob_start();
        passthru($command);
        $output = ob_get_clean();

        return $output;
    }

    public static function runShell($command, $isThrowable = true)
    {
        $output = shell_exec(escapeshellcmd($command));

        if ($isThrowable && is_null($output)) {
            throw new Exception('My Shell Command Fail');
        }

        return array_filter(explode("\n", $output), 'strlen');
    }

    public static function runProcess($command)
    {
        $process = new Process($command);
        $process->run();

        // executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return $process->getOutput();
    }
}
