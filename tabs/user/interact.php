<?php 

$bysoftdeveloperUserUrl			= 'bysoftdeveloper/user';
eZURI::transformURI($bysoftdeveloperUserUrl, false);

return <<<EOT
function  bysoftdeveloperShowUserTab(){
    var data = {action: 'info'};
    var options = {url: '$bysoftdeveloperUserUrl', data:data, callback:bysoftdeveloperUpdateUserinfo};
    bysoftdeveloperAjax(options);
    
    function bysoftdeveloperUpdateUserinfo(result) {
        _get('bysoftdeveloper-user-wrap').innerHTML = result; 
        var forms = _get('bysoftdeveloper-user-switchform', 'name', 'list');
        for(key in forms){
        	forms[key].onsubmit = function(e){
        		form = e.currentTarget;
        		var uid = form.uid.value;
        		var data = {action: 'switchuser', uid: uid};
			    var options = {url:'$bysoftdeveloperUserUrl', data: data, callback: bysoftdeveloperUserSwticher};
			    bysoftdeveloperAjax(options);
			    
			    function bysoftdeveloperUserSwticher(result){
			    	if(result.trim() == 'success'){
        				if(confirm('Switch successfully! Do you want to refresh the page?')){
        					location.reload(true);
        				}
        			}else{
        				alert('Switch fail! ' + result.trim());
        			}
			    }
        		return false;
        	}
        }
    }
}
EOT;
