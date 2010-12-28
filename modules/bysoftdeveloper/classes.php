<?php

include_once( 'kernel/common/template.php' );

// total disabled translation for this project.
$ini = eZINI::instance();
$ini->setVariable('RegionalSettings', 'TextTranslation', 'disabled');

$tpl = templateInit();
$http = eZHTTPTool::instance();

if ($http->postVariable('action') == 'form') {
    
    $groups = eZContentClassGroup::fetchList(false, true);
    
    $template = 'design:bysoftdeveloper/classes/form.tpl';
    $tpl->setVariable('groups', $groups);
    echo $tpl->fetch($template);
}

if ($http->postVariable('action') == 'content') {
    
    $classIdentifier = $http->postVariable('selectedClass');
    
    $class = eZContentClass::fetchByIdentifier($classIdentifier);
    
    $attributes = $class->fetchAttributes();
    
    eZDataType::loadAndRegisterAllTypes();
    
    $datatypes = eZDataType::registeredDataTypes();
    
    $tpl->setVariable('class', $class);
    $tpl->setVariable('class_identifier', $classIdentifier);
    $tpl->setVariable('attributes', $attributes);
    $tpl->setVariable('datatypes', $datatypes );
    
    $template = 'design:bysoftdeveloper/classes/content.tpl';
    echo $tpl->fetch($template);
    
}

eZExecution::cleanExit();;

?>