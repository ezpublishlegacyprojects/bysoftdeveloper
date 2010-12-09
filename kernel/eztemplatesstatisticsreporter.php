<?php
//
// Definition of eZTemplatesStatisticsReporter class
//
// Created on: <18-Feb-2005 17:21:17 dl>
//
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.2.0
// BUILD VERSION: 24182
// COPYRIGHT NOTICE: Copyright (C) 1999-2009 eZ Systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//

/*! \file
*/

/*!
  \class eZTemplatesStatisticsReporter eztemplatesstatisticsreporter.php
  \brief Generates statistics of tempate usage.

*/
@include_once('extension/bysoftdeveloper/kernel/ezdebug.php');
class eZTemplatesStatisticsReporter
{
    /*!
     static
    */
    static function generateStatistics( $as_html = true )
    {
        $statStartTime = microtime( true );
        $stats = '';

        if ( !eZTemplate::isTemplatesUsageStatisticsEnabled() )
            return $stats;

        if ( $as_html )
        {
            $stats .= "<h2>Templates used to render the page:</h2>";
            $stats .= ( "<table id='templateusage' summary='List of used templates' style='border: 1px dashed black;' cellspacing='0'>" .
                   "<tr><th>Usages</th>" .
                   "<th>Requested template</th>" .
                   "<th>Template</th>" .
                   "<th>Template loaded</th>" .
                   "<th>Edit</th>" .
                   "<th>Override</th></tr>" );
        }
        else
        {
            $formatString = "%-40s%-40s%-40s\n";
            $stats .= "Templates usage statistics\n";
            $stats .= sprintf( $formatString, 'Templates', 'Requested template', 'Template loaded' );
        }

        if ( $as_html )
        {
            $iconSizeX = 16;
            $iconSizeY = 16;
            $templateViewFunction = 'visual/templateview';
            eZURI::transformURI( $templateViewFunction );
            $templateEditFunction = 'visual/templateedit';
            eZURI::transformURI( $templateEditFunction );
            $templateOverrideFunction = 'visual/templatecreate';
            eZURI::transformURI( $templateOverrideFunction );

            $std_base = eZTemplateDesignResource::designSetting( 'standard' );
            $wwwDir = eZSys::wwwDir();
            $editIconFile = "$wwwDir/design/$std_base/images/edit.gif";
            $overrideIconFile = "$wwwDir/design/$std_base/images/override-template.gif";

            $tdClass = 'used_templates_stats1';
            $j = 0;

            $currentSiteAccess = $GLOBALS['eZCurrentAccess']['name'];
        }

        $templatesUsageStatistics = eZTemplate::templatesUsageStatistics();

        $alreadyListedTemplate = $templateCounts = array();

        //Generate usage count for each unique template first.
        foreach( $templatesUsageStatistics as $templateInfo )
        {
            $actualTemplateName = $templateInfo['actual-template-name'];

            if ( !array_key_exists( $actualTemplateName, $templateCounts ) )
            {
                $templateCounts[$actualTemplateName] = 1;

            }
            else
            {
                ++$templateCounts[$actualTemplateName];
            }
        }
        
        // cavin.deng add these two variables
        $isWin = (substr(PHP_OS, 0, 3) == 'WIN') ? 1 : 0;
        $siteDir = eZSys::siteDir();
        
        //Then create the actual listing
        foreach ($templatesUsageStatistics as $templateInfo)
        {
            $actualTemplateName = $templateInfo['actual-template-name'];
            $requestedTemplateName = $templateInfo['requested-template-name'];
            $templateFileName = $templateInfo['template-filename'];

            if ( !in_array( $actualTemplateName, $alreadyListedTemplate ) )
            {
                $alreadyListedTemplate[] = $actualTemplateName;
                if ( $as_html )
                {
                    $tdClass = ( $j % 2 == 0 ) ? 'used_templates_stats1' : 'used_templates_stats2';

                    $requestedTemplateViewURI = $templateViewFunction . '/' . $requestedTemplateName;
                    $actualTemplateViewURI = $templateViewFunction . '/' . $actualTemplateName;

                    $templateEditURI = $templateEditFunction . '/' . $templateFileName;
                    $templateOverrideURI = $templateOverrideFunction . '/' . $actualTemplateName;

                    $actualTemplateNameOutput = ( $actualTemplateName == $requestedTemplateName ) ? "<span style=\"font-style: italic;\">&lt;No override&gt;</span>" : $actualTemplateName;
                    
                    // cavin.deng modify this place, to offer full path base on OS
                    $fullFileName = str_replace('\\', '/', $siteDir . $templateFileName );

                    $stats .= (
                           "<tr onclick=\"show_design_path('$fullFileName', this, event);\">" .
                           "<td class=\"$tdClass\">$templateCounts[$actualTemplateName]</td>" .
                           "<td class=\"$tdClass\"><a href=\"$requestedTemplateViewURI\">$requestedTemplateName</a></td>" .
                           "<td class=\"$tdClass\">$actualTemplateNameOutput</td>" .
                           "<td class=\"$tdClass\">$templateFileName</td>" .
                           "<td class=\"$tdClass\" align=\"center\"><a href=\"$templateEditURI/(siteAccess)/$currentSiteAccess\"><img src=\"$editIconFile\" width=\"$iconSizeX\" height=\"$iconSizeY\" alt=\"Edit template\" title=\"Edit template\" /></a></td>".
                           "<td class=\"$tdClass\" align=\"center\"><a href=\"$templateOverrideURI/(siteAccess)/$currentSiteAccess\"><img src=\"$overrideIconFile\" width=\"$iconSizeX\" height=\"$iconSizeY\" alt=\"Override template\" title=\"Override template\" /></a></td></tr>" );

                    $j++;
                }
                else
                {
                    $stats .= sprintf( $formatString, $requestedTemplateName, $actualTemplateName, $templateFileName );
                }
            }
        }
        
        // cavin.deng add these two variables
        $show_design_path_script = <<<EOT
        <tr>
        	<td colspan="6" align="center">
        		<input size=100 type="text" value="" id="bysoftdeveloper-template-reporter-input" />
        	</td>
        </tr>
EOT;

        $stats .= $show_design_path_script;
        
        // cavin.deng add float for template usage button
        $templateusageindiv = <<<EOT
            $stats
      	</table>
EOT;


        $totalTemplatesCount = count( $templatesUsageStatistics );
        $totalUniqueTemplatesCopunt = count( array_keys( $alreadyListedTemplate ) );
        $statEndTime = microtime( true );
        $timeUsage = number_format( $statEndTime - $statStartTime, 4 );

        if ( $as_html )
        {
            $stats .= ( "<tr>" . 
            		"<td class=\"$tdClass\">&nbsp;</td>" .
                   "<td class=\"$tdClass\">&nbsp;</td>" .
                   "<td class=\"$tdClass\">&nbsp;</td>" .
                   "<td class=\"$tdClass\">&nbsp;</td>".
                   "<td class=\"$tdClass\">&nbsp;</td>".
                   "<td class=\"$tdClass\">&nbsp;</td>".
            		"</tr>" );
            $stats .= "<tr><td colspan=\"4\" style=\"text-align: left;\"><b>&nbsp;Number of times templates used: $totalTemplatesCount<br />&nbsp;Number of unique templates used: $totalUniqueTemplatesCopunt</b></td></tr>";
            $stats .= "<tr><td colspan=\"4\" style=\"text-align: left;\"><b>&nbsp;Time used to render template usage: $timeUsage secs</b></td></tr>";
            $stats .= "</table>";
        }
        else
        {
            $stats .= "\nTotal templates count: " . $totalTemplatesCount . "\n" . "Total unique templates count: " . $totalUniqueTemplatesCopunt . "\n";
        }
        
		// cavin.deng add these js.
		$template_js = <<<EOT
        <script type="text/javascript">
        	function show_design_path(file, trElement, e){
        		//var e = e || event || window.event;
        		
        		// find input based on trElement;
        		var input = document.getElementById('bysoftdeveloper-template-reporter-input');
        		
        		if( $isWin ){
        			file = file.replace(/\//g, "\\\\");
        		}
        		input.value=file;
        		input.select();
        		
        		//event.cancelBubble = true;
        	}
        </script>
EOT;

		//$stats = $stats . $templateusageindiv . $template_js;
		$stats = $templateusageindiv . $template_js;

        return $stats;
    }


    // used only by developer
    function __construct() {
        $this->Operators = array( 'array_intel', 'variables');
    }
    
    /*!
     Returns the operators in this class.
    */
    function &operatorList()
    {
        return $this->Operators;
    }
    
    function namedParameterPerOperator()
    {
        return true;
    }
    
    function namedParameterList()
    {
        
        return array(
            'array_intel' => array(
                'length' => array(
                    'type' => 'integer',
                    'required' => true,
                ),
                'offset' => array(
                    'type' => 'integer',
                    'required' => false,
                    'default' => 0
                )
            ),
            'variables' => array(
            ),
        );
    }
    
    function modify( $tpl, $operatorName, $operatorParameters, $rootNamespace,
                     $currentNamespace, &$operatorValue, $namedParameters )
    {
        switch ($operatorName) {
            case 'array_intel' :
                $temp = array();
                $length = $namedParameters['length'];
                $offset = $namedParameters['offset'];
                $values = array_values($operatorValue);
                
                $return = array();
                $groupCount = ceil(count($values) / $length);
                foreach ($values as $idx => $val) {
                    $gid = $idx % $groupCount;
                    $return[$gid][] = $val;
                }
                
                $operatorValue = $return;
                break;
            case 'variables':
                $operatorValue = $this->showVariables($tpl, $rootNamespace, $currentNamespace);
                break;
        }
    }
    
    /**
     * Show variables in template...
     *
     * @param ezTemplate $tpl
     * @param string $rootNamespace
     * @param string $currentNamespace
     */
    private function showVariables($tpl, $rootNamespace, $currentNamespace)
    {
        $result = '';
        
        // generate a uniqued id for these
        // current file should be the last template been fetched?
        $tplFile = end($tpl->TemplateFetchList);
        $id = md5(microtime(true) . $tplFile . uniqid(rand(), true) );
        $aId = 'bysoftdeveloper_variables_a_' . $id;
        $divId = 'bysoftdeveloper_variables_div_' . $id;
        
        $isWin = (substr(PHP_OS, 0, 3) == 'WIN') ? 1 : 0;
        $siteDir = eZSys::siteDir();
        
        if ($isWin) {
            $tplFilePath = str_replace('/', '\\', $siteDir . $tplFile);
        } else {
            $tplFilePath = str_replace('\\', '/', $siteDir . $tplFile);
        }
        $tplFilePathLength = strlen($tplFilePath);
        $tplFilePathLength = min($tplFilePathLength, 100);
        
        
        $result = <<<EOT
<a id="{$aId}" title="$tplFile" onclick="javascript:bysoftDeveloperShowWindow('{$divId}', event, '{$aId}');" style="text-decoration: underline;font-style:italic;font-weight:bold;">@^V^@</a>
<div id="{$divId}" style="display:none; position:absolute; z-index:999; width:700px; top: 10px; left: 10px; overflow: hidden;">
	<div style="background-color: green; color: white;" onmousedown="return bysoftDeveloperDragWindow(this, event);">
	    Namespaces & Variables &nbsp;&nbsp;&nbsp;&nbsp;
	    <a style="color:white;text-decoration:underline;" onclick="javascript:bysoftDeveloperCloseWindowById('{$divId}');">Close</a>
	</div>
	<div style="display: block; background-color: white; border: 3px solid green;">
		<table>
			<tr align="center"><td colspan="4"><input type='text' value='{$tplFilePath}' size='{$tplFilePathLength}' /></td></tr>
			<tr style="font-weight:bold;"><td>&nbsp;</td><td>Variable</td><td>Type</td><td>Value</td></tr>
EOT;
        $currentNamespaceRows = '';
        $otherNamespaceRows = '';
		foreach ($tpl->Variables as $namespace => $varArr) {
            if (!count($varArr)) {
                continue;
            }
            $isCurrentNamespace = false;
            if (!$namespace) {
                if ($currentNamespace) {
                    $namespace = 'GLOBAL';
                } else {
                    $namespace = 'ROOT';
                }
            } else if ($namespace == $currentNamespace) {
                $namespace .= ' [Current]';
                $isCurrentNamespace = true;
            }
            // no need, i think, or we will lost the variable register order
            //ksort($varArr);
            
            // style="border-bottom: 1px dashed black;"
            if ($isCurrentNamespace) {
                $currentNamespaceRows = <<<EOT
<tr><td colspan="4" style="font-weight:bold; background-color: #937983;color: white;">
    $namespace
</td></tr>
EOT;
            } else {
                $otherNamespaceRows .= <<<EOT
<tr><td colspan="4" style="font-weight:bold; background-color: #76A3B6;color: white;">
    $namespace
</td></tr>
EOT;
            }
            
            $line = <<<EOT
<tr style="border: 1px dashed black;">
    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td>%s</td>
    <td>%s</td>
    <td>%s</td>
</tr>
EOT;
            
            $rows = '';
            foreach ($varArr as $key => $val) {
                $output = '';
                $type = gettype($val);
                if (is_object($val)) {
                    $type = '[' . get_class($val) . ']';
                }
                // check if the array is simple array, if it is then print out it
                if (is_array($val)) {
                    if ($this->isSimpleArray($val)) {
                        $output = '<textarea cols="40">' . htmlspecialchars(print_r($val, true)) . '</textarea>';
                    } else {
                        // @todo, maybe we need explode the group.
                        $output = '';
                    }
                }
                if (!is_object($val) and !is_array($val)) {
                    $output = (string) $val;
                    if (is_bool($val)) {
                        if ($val) {
                            $output = 'True';
                        } else {
                            $output = 'False';
                        }
                    }
                    if (is_string($val)) {
                        $output = "<input type='text' size='50' name='ThisNameYouShouldNotCare' value='".htmlspecialchars($val)."' />";
                    }
                }
                
                $rows .= sprintf($line, $key, $type, $output);
            }
            if ($isCurrentNamespace) {
                $currentNamespaceRows .= $rows;
            } else {
                $otherNamespaceRows .= $rows;
            }
        }
        $result .= $currentNamespaceRows;
        $result .= $otherNamespaceRows;
        $result .= '</table>
        </div>
</div>';
        $javascript = <<<EOT
<script type="text/javascript">

	function bysoftDeveloperShowWindow(id, e, eA) 
	{
		e = e || event || window.event;
		
		var element = document.getElementById(id);
		var x = y = 0;
		if (document.all) {//IE
			x = (document.documentElement && document.documentElement.scrollLeft) ? document.documentElement.scrollLeft : document.body.scrollLeft;
			y = (document.documentElement && document.documentElement.scrollTop) ? document.documentElement.scrollTop : document.body.scrollTop;
            x += window.event.clientX;
            y += window.event.clientY;
		} else {//Good Browsers
			x = e.pageX;
			y = e.pageY;
		} 
		// @todo, cavin.deng
		// to get top left;
		if (document.body) {
			var currentTop = document.body.scrollTop;
			var currentLeft = document.body.scrollLeft;
    	} else {
			var currentTop = document.documentElement.scrollTop;
			var currentLeft = document.documentElement.scrollLeft;
    	}
    	
		
		var a = document.getElementById(eA);
		var aTop = a.offsetTop;
		var aLeft = a.offsetLeft;
		
		while (a = a.offsetParent) {
			aTop += a.offsetTop;
			aLeft += a.offsetLeft;
    	}
		//var pos = _getPosition(a);
		//element.style.left = (aTop - currentTop) + 'px';
		//element.style.top = (aLeft - currentLeft) + 'px';
		// popUp(e, id);
		//bysoftDeveloperToggleWindowById(id);
		bysoftDeveloperToggleWindowById(id);
    }
    
    function _getPosition (obj) {
        var curleft = curtop = 0;
        if (obj.offsetParent) {
            curleft = obj.offsetLeft;
            curtop = obj.offsetTop;
            while (obj = obj.offsetParent) {
                curleft += obj.offsetLeft;
                curtop += obj.offsetTop;
            }
        }
        return [curleft,curtop];
    };
    
    
	// toggle a element by id or object, 
	// if status afforded then set this element to that status
	function bysoftDeveloperToggleWindowById(e, status)
	{
		if( typeof e == 'string' ) e = document.getElementById(e);
				
			if( status != undefined ){
				var value = (status == 'block' || (status != 'none' && status) ) ? 'block' : 'none';
				e.style.display = value;
				return true;
			}
			
			if( e.style.display == 'block' ){
				e.style.display = 'none';
			}else if(e.style.display == 'none'){
				e.style.display = 'block';
			}else{
				e.style.display = 'none';
			}
	}
	
	function bysoftDeveloperCloseWindowById(e) {
		e = document.getElementById(e);
		e.style.display = 'none';
    }
	
	// drag popup window
    function bysoftDeveloperDragWindow(element, e){
    	e = e || event || window.event;
    	
    	if( document.addEventListener ){
    		document.addEventListener("mousemove", bysoftDeveloperStartDrag, true);
    		document.addEventListener("mouseup", bysoftDeveloperStopDrag, true);
    	}else{
    		document.onmousemove = bysoftDeveloperStartDrag;
    		document.onmouseup = bysoftDeveloperStopDrag;
    	}
    	
    	var target = element.parentNode;
    	
    	var delatX = e.clientX - parseInt(target.style.left);
    	var delatY = e.clientY - parseInt(target.style.top);
    	
    	function bysoftDeveloperStartDrag(e){
    		e = e || event || window.event;
    		target.style.left = (e.clientX - delatX) + 'px';
    		target.style.top = (e.clientY - delatY) + 'px';
    	}
    	
    	function bysoftDeveloperStopDrag(){
    		if( document.removeEventListener ){
    			document.removeEventListener("mousemove", bysoftDeveloperStartDrag, true);
    			document.removeEventListener("mouseup", bysoftDeveloperStopDrag, true);
    		}else{
    			document.onmousemove = "";
    			document.onmouseup = "";
    		}
    	}
    }
	
</script>
EOT;
        if (self::$developerVariablesJavascriptLoaded == false) {
            $result .= $javascript;
            self::$developerVariablesJavascriptLoaded =  true;
        }
        return $result;
    }
    
    public function isSimpleArray($arr)
    {
        foreach ($arr as $val) {
           if (is_object($val)) {
               return false;
           } 
           if (is_array($val) && !$this->isSimpleArray($val)) {
               return false;
           }
        }
        return true;
    }
    
    public static $developerVariablesJavascriptLoaded = false;
    
}

?>
