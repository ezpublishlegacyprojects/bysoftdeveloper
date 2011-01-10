<?php

include_once('kernel/common/template.php');
$tpl = templateInit();


$tpl->setVariable('name', 'user');


echo $tpl->fetch('design:developer/test.tpl');

eZExecution::cleanExit();

?>
