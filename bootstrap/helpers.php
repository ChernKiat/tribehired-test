<?php

use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\CliDumper;
use Symfony\Component\VarDumper\Dumper\HtmlDumper;

function dddd() // the laravel 7 version
{
	$args = func_get_args();
	$defaultStringLength = -1;
	$defaultItemNumber = -1;
	$defaultDepth = -1;

	foreach ($args as $variable) {
		$dumper = 'cli' === PHP_SAPI ? new CliDumper() : new HtmlDumper();

		$cloner = new VarCloner();
		$cloner->setMaxString($defaultStringLength);
        $cloner->setMaxItems($defaultItemNumber);

		$dumper->dump($cloner->cloneVar($variable)->withMaxDepth($defaultDepth));
	}

    die(1);
}
