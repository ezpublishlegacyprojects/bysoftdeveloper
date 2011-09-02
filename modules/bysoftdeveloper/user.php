<?php 
include_once( 'kernel/common/template.php' );

// total disabled translation for this project.
$ini = eZINI::instance();
$ini->setVariable('RegionalSettings', 'TextTranslation', 'disabled');

// Authorize the user to admin when doing fetch


$tpl = templateInit();
$http = eZHTTPTool::instance();


if ($http->postVariable('action') == 'info') {
	
	$current_user = eZUser::currentUser ();
	$admin = eZUser::fetch($ini->variable('BysoftDeveloper', 'AdminID'));
	SwitchUser::switchTo($admin);

	$userIDList = array();
	$userContentList = eZUser::fetchContentList( );
	foreach($userContentList as $userContent)
	{
		$userIDList[] = $userContent['id'];
	}
	$userObjectList = eZPersistentObject::fetchObjectList( eZUser::definition(),
                                                null,
                                                array( 'contentobject_id' => array($userIDList) ),
                                                $asObject );


    $template = 'design:bysoftdeveloper/user/info.tpl';
    $tpl->setVariable('current_user', $current_user);
	$tpl->setVariable('userObjectList', $userObjectList);
    echo $tpl->fetch($template);
    SwitchUser::switchTo($current_user);
}

if ($http->postVariable('action') == 'switchuser') {
	$uid = $http->postVariable('uid');
	$user = eZUser::fetch($uid);
	if($user instanceof eZUser)
	{
		SwitchUser::switchTo($user);
		echo 'success';
	}
	
    echo $tpl->fetch($template);
}


eZExecution::cleanExit();
