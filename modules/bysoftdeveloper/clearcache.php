<?php

$bysoftDevINI = eZINI::instance('bysoftdeveloper.ini');

$phpBinaryPath = $bysoftDevINI->variable('BysoftDeveloper', 'PHPBinaryPath');

exec($phpBinaryPath . ' -v', $return);

if (strpos($return[0], 'PHP') === false) {
	echo "$phpBinaryPath isn't PHP CLI!";
} else {
	$cleanCacheCommand = $phpBinaryPath . ' bin/php/ezcache.php --clear-all --no-colors';
	exec($cleanCacheCommand, $output, $status);
	if ($status == 0) {
		echo 'Clear Cache Done!';
	}
}

eZExecution::cleanExit();

?>
