<?php
$Module = array( 'name' => 'Developer' );

$ViewList = array();

$ViewList["template"] = array(
    "functions" => array( 'read' ),
    "default_navigation_part" => 'ezbysoftdevelopernavigationpart',
    "script" => "template.php",
    "params" => array( ) 
);

$ViewList['class'] = array(
	'functions' => array('read'),
	'default_navigation_part' => 'ezbysoftdevelopernavigationpart',
	'script' => 'class.php',
	'params' => array('classID', 'classVersion'),
);
$ViewList['classes'] = array(
    'functions' => array('read'),
	'default_navigation_part' => 'ezbysoftdevelopernavigationpart',
	'script' => 'classes.php'
);
$ViewList['test'] = array(
	'functions' => array('read'),
	'default_navigation_part' => 'ezbysoftdevelopernavigationpart',
	'script' => 'test.php',
);

$ViewList['ini'] = array(
    'functions' => array('read'),
    'default_navigation_part' => 'ezbysoftdevelopernavigationpart',
	'script' => 'ini.php',
);

$ViewList["clearcache"] = array(
    "functions" => array( 'read' ),
    "default_navigation_part" => 'ezbysoftdevelopernavigationpart',
    "script" => "clearcache.php",
    "params" => array( ) 
);

$ViewList["search"] = array(
    "functions" => array( 'read' ),
    "default_navigation_part" => 'ezbysoftdevelopernavigationpart',
    "script" => "search.php",
    "params" => array( ) 
);

$ViewList["user"] = array(
    "functions" => array( 'read' ),
    "default_navigation_part" => 'ezbysoftdevelopernavigationpart',
    "script" => "user.php",
    "params" => array( ) 
);

$FunctionList = array( );
$FunctionList['read'] = array( );


?>