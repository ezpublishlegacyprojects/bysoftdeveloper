<?php

include_once( 'kernel/common/template.php' );

// total disabled translation for this project.
$ini = eZINI::instance();
$ini->setVariable('RegionalSettings', 'TextTranslation', 'disabled');

// Authorize the user to admin when doing fetch
$current_user = eZUser::currentUser ();
$admin = eZUser::fetch($ini->variable('BysoftDeveloper', 'AdminID'));
SwitchUser::switchTo($admin);

$tpl = templateInit();
$http = eZHTTPTool::instance();

if ($http->postVariable('action') == 'form') {
    
    $groups = eZContentClassGroup::fetchList(false, true);
    
    $template = 'design:bysoftdeveloper/classes/form.tpl';
    $tpl->setVariable('groups', $groups);
    echo $tpl->fetch($template);
}

if ($http->postVariable('action') == 'content') {
    
    $class_id = $http->postVariable('selectedClass');
    
    $class = eZContentClass::fetch($class_id);
    
    $attributes = $class->fetchAttributes();
    
    eZDataType::loadAndRegisterAllTypes();
    
    $datatypes = eZDataType::registeredDataTypes();
    $count = eZContentObject::fetchListCount(array('contentclass_id' => $class_id));
    
    $tpl->setVariable('count', $count);
    $tpl->setVariable('class', $class);
    $tpl->setVariable('attributes', $attributes);
    $tpl->setVariable('datatypes', $datatypes );
    
    $template = 'design:bysoftdeveloper/classes/content.tpl';
    echo $tpl->fetch($template);
    
}


if ($http->postVariable('action') == 'object') {
    
    $class_id = $http->postVariable('selectedClass');
    $class = eZContentClass::fetch($class_id);
    
    $contentobject_list = eZContentObject::fetchList(true,  array('contentclass_id' => $class_id));

  //  $tpl->setVariable('class_identifier', $classIdentifier);
	$tpl->setVariable('contentobject_list', $contentobject_list);
    
    $template = 'design:bysoftdeveloper/classes/object.tpl';
    echo $tpl->fetch($template);
    
}

if ($http->postVariable('action') == 'objectcontent') {
    
    $object_id = $http->postVariable('object_id');
	$object = eZContentObject::fetch($object_id);
    
    $tpl->setVariable('object', $object);

    
    $template = 'design:bysoftdeveloper/classes/objectcontent.tpl';
    echo $tpl->fetch($template);
    
}
SwitchUser::switchTo($current_user);
eZExecution::cleanExit();;

?>