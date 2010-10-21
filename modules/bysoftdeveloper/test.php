<?php

$tpl = eZTemplate::factory();


$tpl->setVariable('name', 'user');

echo $tpl->fetch('design:developer/test.tpl');

eZExecution::cleanExit();

?>