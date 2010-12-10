<?php

$tpl = eZTemplate::factory();
$http = eZHTTPTool::instance();
$ini = eZINI::instance();
$siteAccessList = $ini->variable( 'SiteAccessSettings', 'AvailableSiteAccessList' );

if ($http->postVariable('action') == 'form'){
    
    $rootDir = 'settings';
    $iniFiles = eZDir::recursiveFindRelative( $rootDir, '', '.ini' );
    
    // find all .ini files in active extensions
    foreach ( eZINI::globalOverrideDirs() as $iniDataSet )
    {
        $iniPath = $iniDataSet[1] ? $iniDataSet[0] : 'settings/' . $iniDataSet[0];
        $iniFiles = array_merge( $iniFiles, eZDir::recursiveFindRelative( $iniPath, '', '.ini' ) );
        $iniFiles = array_merge( $iniFiles, eZDir::recursiveFindRelative( $iniPath, '', '.ini.append.php' ) );
    }
    
    // extract all .ini files without path
    $iniFiles = preg_replace('%.*/%', '', $iniFiles );
    // remove *.ini[.append.php] from file name
    $iniFiles = preg_replace('%\.ini.*%', '.ini', $iniFiles );
    sort( $iniFiles );
    
    $tpl->setVariable( 'ini_files', array_unique( $iniFiles ) );
    $tpl->setVariable( 'siteaccess_list', $siteAccessList );
    
    $template = 'design:bysoftdeveloper/ini/form.tpl';
    echo $tpl->fetch($template);
}

if ($http->postVariable('action') == 'content') {
    
    $settingFile = $http->postVariable('file');
    $currentSiteAccess = $http->postVariable('siteaccess');
    
    unset($ini);
    
    if ( $GLOBALS['eZCurrentAccess']['name'] !== $currentSiteAccess )
    {
        // create a site ini instance using $useLocalOverrides
        $siteIni = eZSiteAccess::getIni( $currentSiteAccess, 'site.ini' );

        // load settings file with $useLocalOverrides = true & $addArrayDefinition = true
        $ini = new eZINI( $settingFile,'settings', null, false, true, false, true );
        $ini->setOverrideDirs( $siteIni->overrideDirs( false ) );
        $ini->load();
    }
    else
    {
        // load settings file more or less normally but with $addArrayDefinition = true
        $ini = new eZINI( $settingFile,'settings', null, false, null, false, true );
    }

    $blocks = $ini->groups();
    $placements = $ini->groupPlacements();
    $settings = array();
    $blockCount = 0;
    $totalSettingCount = 0;

    foreach( $blocks as $block=>$key )
    {
        $settingsCount = 0;
        $blockRemoveable = false;
        $blockEditable = true;
        foreach( $key as $setting=>$settingKey )
        {
            $hasSetPlacement = false;
            $type = $ini->settingType( $settingKey );
            $removeable = false;

            switch ( $type )
            {
                case 'array':
                    if ( count( $settingKey ) == 0 )
                        $settings[$block]['content'][$setting]['content'] = array();

                    foreach( $settingKey as $settingElementKey=>$settingElementValue )
                    {
                        $settingPlacement = $ini->findSettingPlacement( $placements[$block][$setting][$settingElementKey] );
                        if ( $settingElementValue != null )
                        {
                            // Make a space after the ';' to make it possible for
                            // the browser to break long lines
                            $settings[$block]['content'][$setting]['content'][$settingElementKey]['content'] = str_replace( ';', "; ", $settingElementValue );
                        }
                        else
                        {
                            $settings[$block]['content'][$setting]['content'][$settingElementKey]['content'] = "";
                        }
                        $settings[$block]['content'][$setting]['content'][$settingElementKey]['placement'] = $settingPlacement;
                        $hasSetPlacement = true;
                        if ( $settingPlacement != 'default' )
                        {
                            $removeable = true;
                            $blockRemoveable = true;
                        }
                    }
                    break;
                case 'string':
                    if( strpos( $settingKey, ';' ) )
                    {
                        // Make a space after the ';' to make it possible for
                        // the browser to break long lines
                        $settingArray = str_replace( ';', "; ", $settingKey );
                        $settings[$block]['content'][$setting]['content'] = $settingArray;
                    }
                    else
                    {
                        $settings[$block]['content'][$setting]['content'] = $settingKey;
                    }
                    break;
                default:
                    $settings[$block]['content'][$setting]['content'] = $settingKey;
            }
            $settings[$block]['content'][$setting]['type'] = $type;
            $settings[$block]['content'][$setting]['placement'] = "";

            if ( !$hasSetPlacement )
            {
                $placement = $ini->findSettingPlacement( $placements[$block][$setting] );
                $settings[$block]['content'][$setting]['placement'] = $placement;
                if ( $placement != 'default' )
                {
                    $removeable = true;
                    $blockRemoveable = true;
                }
            }
            $editable = $ini->isSettingReadOnly( $settingFile, $block, $setting );
            $removeable = $editable === false ? false : $removeable;
            $settings[$block]['content'][$setting]['editable'] = $editable;
            $settings[$block]['content'][$setting]['removeable'] = $removeable;
            ++$settingsCount;
        }
        $blockEditable = $ini->isSettingReadOnly( $settingFile, $block );
        $settings[$block]['count'] = $settingsCount;
        $settings[$block]['removeable'] = $blockRemoveable;
        $settings[$block]['editable'] = $blockEditable;
        $totalSettingCount += $settingsCount;
        ++$blockCount;
    }
    ksort( $settings );
    $tpl->setVariable( 'settings', $settings );
    $tpl->setVariable( 'block_count', $blockCount );
    $tpl->setVariable( 'setting_count', $totalSettingCount );
    $tpl->setVariable( 'ini_file', $settingFile );
    
    $tpl->setVariable( 'siteaccess_list', $siteAccessList );
    $tpl->setVariable( 'current_siteaccess', $currentSiteAccess );
    
    echo $tpl->fetch('design:bysoftdeveloper/ini/content.tpl');
}

eZExecution::cleanExit();



?>