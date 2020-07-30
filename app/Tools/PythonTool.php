<?php

namespace App\Tools;

class PythonTool extends CommandTool
{
	public static function pythonVersion()
	{
	    return self::run("python -V");
	}

    public static function packageDetails($packageName)
    {
        return self::run("pip show {$packageName}");
    }
}
