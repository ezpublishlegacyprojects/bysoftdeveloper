<?php 
$bysoftdeveloperTestUrl 		= 'bysoftdeveloper/test';

eZURI::transformURI($bysoftdeveloperTestUrl, false);

return <<<EOT
// classes ajax content
function bysoftdeveloperShowTestTab(){
    var data = {};
    var options = {url: '$bysoftdeveloperTestUrl', data:data, callback:bysoftdeveloperUpdateTestArea};
    bysoftdeveloperAjax(options);
   
    
    function bysoftdeveloperUpdateTestArea(result) {
        _get('bysoftdeveloper-test-content').innerHTML = result; 
    }
}
EOT;
