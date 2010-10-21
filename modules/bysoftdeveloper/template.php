<?php

//
// @author cavin.deng
//
include_once( "kernel/common/template.php" );

// disable all debug
$ini = eZINI::instance();
//$ini->setVariable('TemplateSettings', 'Bug', 'disabled');
//$ini->setVariable('TemplateSettings', 'TemplateCache', 'enabled');
//$ini->setVariable('TemplateSettings', 'TemplateCompile', 'enabled');
//$ini->setVariable('TemplateSettings', 'ShowUsedTemplates', 'disabled');


// turn off debug for this page
$settings = array(
	'debug-enabled' =>  FALSE,
    'debug-by-ip' => FALSE,
);

eZDebug::updateSettings($settings);

$tpl = templateInit();

function adjustPath($path) {
    // cavin.deng add these two variables
    $isWin = (substr(PHP_OS, 0, 3) == 'WIN') ? 1 : 0;
    $siteDir = eZSys::siteDir();
    
    $path = str_replace('\\', '/', $siteDir . $path );
    $path = str_replace('//', '/', $path);
    if ($isWin) {
        $path = str_replace('/', '\\\\', $path);
    }
    return $path;
}

function cmpStrlen($a, $b){
    $lenA = strlen($a);
    $lenB = strlen($b);
    if ($lenA == $lenB) {
        return strcasecmp($a, $b);
    }
    return ($lenA - $lenB);
}

$templateFuncAttrs = $tpl->FunctionAttributes;

///// operator
$templateOperators = array();

$operatorClassToNameMaps = array(
    'eZTemplateArrayOperator' => 'Arrays',
    'eZTemplateExecuteOperator' => 'Data and information extraction',
    'eZTemplateLocaleOperator' => 'Formatting and internationalization',
    'eZTemplateAttributeOperator' => 'Miscellaneous',
    'eZTemplateNl2BrOperator' => 'Strings',
    'eZTemplateTextOperator' => 'Strings',
    'eZTemplateUnitOperator' => 'Formatting and internationalization',
    'eZTemplateLogicOperator' => 'Logical operations',
    'eZTemplateTypeOperator' => 'Variable and type handling',
    'eZTemplateControlOperator' => 'Logical operations',
    'eZTemplateArithmeticOperator' => 'Mathematics',
    'eZTemplateImageOperator' => 'Images',
    'eZTemplateStringOperator' => 'Strings',
    'eZTemplateDigestOperator' => 'Strings',
    'eZURLOperator' => 'URLs',
    'eZI18nOperator' => 'Formatting and internationalization',
    'eZAlphabetOperator' => 'Strings',
    'eZDateOperatorCollection' => 'Miscellaneous',
    'eZAutoLinkOperator' => 'Strings',
    'eZSimpleTagsOperator' => 'Strings',
    'eZTreeMenuOperator' => 'Miscellaneous',
    'eZContentStructureTreeOperator' => 'Miscellaneous',
    'eZWordToImageOperator' => 'Miscellaneous',
    'eZKernelOperator' => 'Data and information extraction',
    'eZPHPOperatorInit' => 'Miscellaneous',
    'eZModuleParamsOperator' => 'Data and information extraction',
    'eZTopMenuOperator' => 'Miscellaneous',
    'eZPackageOperator' => 'Miscellaneous',
    'eZTOCOperator' => 'Miscellaneous',
    'eZModuleOperator' => 'Miscellaneous',
    'eZPDF' => 'Miscellaneous',
    'ezpLanguageSwitcherOperator' => 'Miscellaneous',
);

foreach ($tpl->Operators as $operator => $info) {
    $info['path'] = adjustPath($info['script']);
    $info['name'] = $operator;
    if (substr($operator , 0, 2) != '__') {
        $info['path'] = adjustPath($info['script']);
        // cateogrized
        if (array_key_exists($info['class'],$operatorClassToNameMaps)) {
            $templateOperators[$operatorClassToNameMaps[$info['class']]][$operator] = $info;
        } else {
            if ('extension' == substr($info['script'], 0, 9)) {
                // extension operators
                $templateOperators['XExtension Operator'][$operator] = $info;
            } else {
                $templateOperators['Miscellaneous'][$operator] = $info;
            }
        }
    }
}

// used by developer only
$tpl->Operators['array_intel'] = array(
    'script' => 'extension/bysoftdeveloper/kernel/eztemplatesstatisticsreporter.php',
    'class' => 'eZTemplatesStatisticsReporter',
    'operator_names' => array('array_intel','variables')
);

///// functions
$templateFunctions = array();
$functionClassToNameMaps = array(
	'eZTemplateSectionFunction' => 'Miscellaneous',
    'eZTemplateDelimitFunction' => 'Miscellaneous',
    'eZTemplateIncludeFunction' => 'Miscellaneous',
    'eZTemplateSwitchFunction' => 'Miscellaneous',
    'eZTemplateSequenceFunction' => 'Variables',
    'eZTemplateSetFunction' => 'Variables',
    'eZTemplateBlockFunction' => 'Variables',
    'eZTemplateDebugFunction' => 'Debugging',
    'eZTemplateCacheFunction' => 'Miscellaneous',
    'eZTemplateToolbarFunction' => 'Visualization',
    'eZTemplateMenuFunction' => 'Miscellaneous',
    'eZTemplateIfFunction' => 'Miscellaneous',
    'eZTemplateWhileFunction' => 'Miscellaneous',
    'eZTemplateForFunction' => 'Miscellaneous',
    'eZTemplateForeachFunction' => 'Miscellaneous',
    'eZTemplateDoFunction' => 'Miscellaneous',
    'eZTemplateDefFunction' => 'Miscellaneous',
    'eZObjectForwardInit' => 'Miscellaneous',
);
$functionFuncNameToNameMaps = array(
    'eZObjectForwardInit' => 'Visualization',
    'eZSurveyForwardInit' => 'Visualization'
);
foreach ($tpl->Functions as $fun => $info) {
    if (!isset($info['script'])) {
        if ($info['function'] == 'eZObjectForwardInit') {
            $info['script'] = 'kernel/common/eztemplateautoload.php';
        }
        if ($info['function'] == 'eZSurveyForwardInit') {
            $info['script'] = 'extension/ezsurvey/autoloads/eztemplateautoload.php';
        }
    }
    $info['path'] = adjustPath($info['script']);
    $info['name'] = $fun;
    if (array_key_exists($info['class'], $functionClassToNameMaps)) {
        $templateFunctions[$functionClassToNameMaps[$info['class']]][$fun] = $info;
    } else {
        if (array_key_exists($info['function'], $functionFuncNameToNameMaps)) {
            $templateFunctions[$functionFuncNameToNameMaps[$info['function']]][$fun] = $info;
        } else {
            if ('extension' == substr($info['script'], 0, 9)) {
                $templateFunctions['XExtension Functions'][$fun] = $info;
            } else {
                $templateFunctions['Miscellaneous'][$fun] = $info;
            }
        }
    }
}

///// function attributes
$templateFuncAttrs = array();
foreach ($tpl->FunctionAttributes as $attr => $info){
    $info['path'] = adjustPath($info['script']);
    $info['name'] = $attr;
    $templateFuncAttrs[$attr] = $info;
}


// sort operators.
foreach ($templateOperators as $category => &$ops) {
    uksort($ops, 'cmpStrlen');
}

// sort functions
foreach ($templateFunctions as $category => &$func) {
    uksort($func, 'cmpStrlen');
}


$tpl->setVariable('template_operators', $templateOperators);
$tpl->setVariable('template_functions', $templateFunctions);
$tpl->setVariable('template_funcattrs', $templateFuncAttrs);

$Result = array();
$Result['content'] = $tpl->fetch( 'design:bysoftdeveloper/template.tpl' );

$Result['path'] = array(    array('url' => false,
			                      'text' => ezi18n('design/standard/csv', 'Import') ),
			                array('url' => false,
			                      'text' => ezi18n('design/standard/csv', 'CSV') )
			                       );

$Result['pagelayout'] = '';			                       
?>