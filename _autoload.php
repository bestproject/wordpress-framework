<?php
spl_autoload_register(function($classname) {

	$path = explode('\\', $classname);
	array_walk($path, function(&$value) {
		$value = strtolower($value);
	});

	if ($path[0] == 'bestproject' AND $path[1] === 'wordpress') {
		$path = array_slice($path, 2);
	}
	
	$path = __DIR__.'/'.implode('/', $path).'.php';
	if (file_exists($path)) {
		require_once $path;
	}
});
