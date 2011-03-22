<?php

/**
 * 
 * @author cavin.deng
 * @since 18/01/2010
 * 
 */
$Module = & $Params['Module'];

include_once( 'kernel/common/template.php' );
// total disabled translation for this project.
$ini = eZINI::instance();
$ini->setVariable('RegionalSettings', 'TextTranslation', 'disabled');

$class = null;

$http = eZHTTPTool::instance();

if( $Module->isCurrentAction('Store') ){
	$class = array();
	
	$classDefinition = array(
		'class_id' => $_POST['class_id'],
		'class_name' => $_POST['class_name'],
		'class_identifier' => $_POST['class_identifier'],
		'class_object_name_pattern' => $_POST['class_object_name_pattern'],
		'class_is_container' =>  $_POST['class_is_container'] ? 1 : 0,
		'class_url_alias_pattern' => $_POST['class_url_alias_name'],
		'class_group' => $_POST['class_group'],
		'class_version' => $_POST['class_version'],
		'class_always_available' => $_POST['class_always_available'] ? 1 : 0,
		'class_default_sorting_field' => $_POST['class_default_sorting_field'],
		'class_default_sorting_order' => $_POST['class_default_sorting_order'],
	);
	
	$attrs = array();
	$placement = 1;
	for( $i = 0; $i < count($_POST['attribute_identifier']); $i++ ){
		
		if( $_POST['attribute_id'][$i] || ($_POST['attribute_name'][$i] && $_POST['attribute_identifier'][$i]) ){
			// disable default value for now
			$attrs[] = array(
				'attribute_id' => $_POST['attribute_id'][$i],
				'attribute_name' => $_POST['attribute_name'][$i],
				'attribute_identifier' => $_POST['attribute_identifier'][$i],
				'attribute_datatype' => $_POST['attribute_datatype'][$i],
				'attribute_defaultvalue' => $_POST['attribute_defaultvalue'][$i],
				'attribute_is_required' => $_POST['attribute_is_required'][$i],
				'attribute_is_searchable' => $_POST['attribute_is_searchable'][$i],
				'attribute_is_information_collector' => $_POST['attribute_is_information_collector'][$i],
				'attribute_can_translate' => $_POST['attribute_can_translate'][$i],
				'attribute_placement' => $placement
			);
			$placement++;
		}
	}
	
	$ciCreateClass = new EasypublishClass();
	$ciCreateClass->execute($classDefinition, $attrs);
}

$classID = $Params['classID'];
$classVersion = trim($Params['classVersion']);

if( $classVersion === '' ){
    // default set to 0, 0 is stored version.
	$classVersion = eZContentClass::VERSION_STATUS_DEFINED;
}else{
	$classVersion = (int) $classVersion;
}

if( is_numeric($classID) ){
	$class = eZContentClass::fetch($classID);
}elseif($classID){
	$class = eZContentClass::fetchByIdentifier($classID, true, $classVersion);
	if( ! $class ){
		$class = eZContentClass::fetchByIdentifier($classID, TRUE, eZContentClass::VERSION_STATUS_TEMPORARY);
	}
}

$tpl = templateInit();

$groups = eZContentClassGroup::fetchList(false, true);

eZDataType::loadAndRegisterAllTypes();
$datatypes = eZDataType::registeredDataTypes();


$datatypeInfoArray = array();
foreach ($datatypes as $dt){
	$properties = $dt->attribute('properties');

	$datatypeInfoArray[$dt->DataTypeString] = array(
		'is_indexable' => $dt->attribute('is_indexable'),
		'is_information_collector' => $dt->attribute('is_information_collector'),
		'translatable' => $properties['translation_allowed']
	);
}

if( ! $defaultDataType instanceof eZDataType ){
	$defaultDataType = new eZStringType();
}

$tpl->setVariable('datatypeInfoArray', json_encode($datatypeInfoArray));
$tpl->setVariable( 'datatypes', $datatypes );
$tpl->setVariable( 'defaultDataType',$defaultDataType);
$tpl->setVariable( 'groups', $groups);

if( ! $class instanceof eZContentClass ){
	$Result['path'] = array( array( 'url' => '/bysoftdeveloper/class/',
                                'text' => 'Brevity Class Definition' ) );
}else{
	$attributes = $class->fetchAttributes(false, true, $class->attribute('version'));
	// version 0, 1 problem.
	$tpl->setVariable('class', $class);
	$tpl->setVariable('attributes', $attributes);
	
	$Result['path'] = array( array( 'url' => '/bysoftdeveloper/class/' . $classID,
                                'text' => $class->attribute('name') . ' / Brevity Class Definition' ) );
	
}
$Result['content'] = $tpl->fetch('design:bysoftdeveloper/class.tpl');

?>