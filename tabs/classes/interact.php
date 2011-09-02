<?php 
$bysoftdeveloperClassesUrl 		= 'bysoftdeveloper/classes';

eZURI::transformURI($bysoftdeveloperClassesUrl, false);

$class_interactcontent = <<<EOT
// classes ajax content
var developerClassesFormLoaded = false;
function bysoftdeveloperShowClassesTab(){
    if (developerClassesFormLoaded) { 
        return true;
    }
    var data = {action: 'form'};
    var options = {url: '$bysoftdeveloperClassesUrl', data:data, callback:bysoftdeveloperUpdateClassesForm};
    bysoftdeveloperAjax(options);
   
    
    function bysoftdeveloperUpdateClassesForm(result) {
        _get('bysoftdeveloper-classes-form').innerHTML = result; 
         developerClassesFormLoaded = true;
    }
}
function bysoftdeveloperChangeClass(){
    var selectedClass = _get('bysoftdeveloperSelectedClass');
    var selectedClass = bysoftdeveloperGetOptionValue(selectedClass);
    
    if (!selectedClass) return;
    
    var data = {action: 'content', selectedClass: selectedClass};
    var options = {url:'$bysoftdeveloperClassesUrl', data: data, callback: bysoftdeveloperUpdateClassesContent};
    bysoftdeveloperAjax(options);
    
    function bysoftdeveloperUpdateClassesContent(result){
        _get('bysoftdeveloper-classes-content').innerHTML = result;
    }
}
// added by Alva
var objectcontent_loaded = Array();
function bysoftdeveloperObjectList(){
    var selectedClass = _get('bysoftdeveloperSelectedClass');
    var selectedClass = bysoftdeveloperGetOptionValue(selectedClass);
    
    if (!selectedClass) return;
    
    var data = {action: 'object', selectedClass: selectedClass};
    var options = {url:'$bysoftdeveloperClassesUrl', data: data, callback: bysoftdeveloperUpdateClassesObjectlistContent};
    bysoftdeveloperAjax(options);
    
    function bysoftdeveloperUpdateClassesObjectlistContent(result){
    	objectcontent_loaded = Array();
    	_get('bysoftdeveloper-classes-objectlist').innerHTML = result;
    }
    return false;
}

function bysoftdeveloperObjectContent(object_id){
    
    if (!object_id) return;
    
    var data = {action: 'objectcontent', object_id: object_id};
    var options = {url:'$bysoftdeveloperClassesUrl', data: data, callback: bysoftdeveloperUpdateClassesObjectContent};
    bysoftdeveloperAjax(options);
    
    function bysoftdeveloperUpdateClassesObjectContent(result){
    	objectcontent_loaded[object_id] = 1;
        _get('bysoftdeveloper-classes-objectcontent-' + object_id).innerHTML = result;
    }
    return false;
}
function bysoftdeveloperClassesObjectcontentToggle(object_id){
	var ctn = _get('bysoftdeveloper-classes-objectcontent-' + object_id);
	if(ctn.style.display == 'none'){
		if(objectcontent_loaded[object_id] == null){
			bysoftdeveloperObjectContent(object_id);
		}
    	ctn.style.display = '';
   // 	console.log(ctn);
    	ctn.parentNode.style.display = '';
    }else{
    	ctn.style.display = 'none';
    	ctn.parentNode.style.display = 'none';
    }
}
EOT;
return $class_interactcontent;