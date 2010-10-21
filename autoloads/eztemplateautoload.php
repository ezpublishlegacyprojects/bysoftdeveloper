<?php

// Operator autoloading

$eZTemplateOperatorArray = array();

$eZTemplateOperatorArray[] = array( 'script' => 'extension/bysoftdeveloper/kernel/eztemplatesstatisticsreporter.php',
                                    'class' => 'eZTemplatesStatisticsReporter',
                                    'operator_names' => array( 'array_intel', 'variables') );

?>
