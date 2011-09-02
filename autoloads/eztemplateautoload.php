<?php

// Operator autoloading

$eZTemplateOperatorArray = array();

$eZTemplateOperatorArray[] = array( 'script' => 'extension/bysoftdeveloper/kernel/eztemplatesstatisticsreporter.php',
                                    'class' => 'eZTemplatesStatisticsReporter',
                                    'operator_names' => array( 'array_intel', 'variables') );

$eZTemplateOperatorArray[] = array( 'script' => dirname(__FILE__) . '/getusergroups.php',
                                    'class' => 'GetUserGroups',
                                    'operator_names' => array( 'getusergroups') );

?>
