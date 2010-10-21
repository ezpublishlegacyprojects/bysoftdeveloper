//
// @author cavin.deng<altsee@gmail.com>
// @since, 17/11/2010
//
var easypublishEvent = {};

if( document.addEventListener ){
	easypublishEvent.add = function( element, eventType, handler ){
		element.addEventListener( eventType, handler, false);
	};
	
	easypublishEvent.remove = function( element, eventType, handler){
		element.removeEventListener( eventType, handler, false );
	};
// In ie 5 and later, we use attachEvent() and detachEvent(), 
// with a number of hacks to make them ecompatible with addEventListenr and removeEventListener.
}else{
	easypublishEvent.add = function( element, eventType, handler ){
		element.attachEvent("on" + eventType, handler);
	}
}

easypublishEvent.add( window, 'load', easypublishAddIdentifierChangeEvent);

function easypublishAddIdentifierChangeEvent(){
	
	var inputs = document.getElementsByTagName('input');
	
	for(var i=0; i < inputs.length; i++ ){
		input = inputs[i];
		var inputName = input.getAttribute('name');
		if( inputName == 'class_identifier' ||
				/attribute_identifier\[\d+\]/.test(inputName) ){
			
			easypublishEvent.add( input, 'change', easypublishClassIdentifierChange);
		}
		if( inputName == 'class_name' || /attribute_name\[\d+\]/.test(inputName) ){
			
			easypublishEvent.add( input, 'change', easypublishClassNameChange);
		}
	}
}


function easypublishTrim(str){
	return (str+'').replace(/(^\s*)|(\s*$)/g, '');
}

function easypublishClassNameChange(event){
	var e = e || event || window.event;
	
	var target = ( e.srcElement ) ? e.srcElement : e.currentTarget;
	// trim space
	value = easypublishTrim(target.value);
	if( value ){
		value = value.replace(/\s+/g, '__');
		value = value.replace(/__/g, ' ');
	}
	target.value = value;
}

//
// lowercase the identifier, trim left and right space, and replace space with _
// only for input field
//
function easypublishClassIdentifierChange(event){
	
	var e = e || event || window.event;
	
	var target;
	target = ( e.srcElement ) ? e.srcElement : e.currentTarget;
	
	var value = easypublishTrim(target.value).toLowerCase();
	
	target.value = value.replace(/\s+/g, '_');
}

function easypublishClassSwitchClass(e){
	var e = e || event || window.event;
	
	var target;
	if( e.srcElement ){
		target = e.srcElement;
	}else{
		target = e.currentTarget;
	}
	var value = target.value;
	if( ! value ){
		return;
	}
	if( value == 1 ){
		document.location = easypublishClassBaseUrl;
		return;
	}
	var location = easypublishClassBaseUrl + '/' + value;
	document.location = location;
	return;
}


function easypublishClassChangeDataType(e){
	var e = e || event || window.event;
	
	var target;
	if( e.srcElement ){
		target = e.srcElement;
	}else{
		target = e.currentTarget;
	}
	var value = target.value;
	
	var datatype = datatypeInfoArray[value];
	var name = target.getAttribute('name');
	var matches = name.match(/(\d+)/);
	var index = matches[0];
	
	var element = document.getElementsByName('attribute_is_searchable[' + index + ']')[0];
	if( ! datatype.is_indexable ){
		element.removeAttribute("checked");
		element.setAttribute("disabled", "disabled");
	}else{
		element.removeAttribute("disabled");
		element.setAttribute("checked", "checked");
	}
	
	var element = document.getElementsByName('attribute_is_information_collector[' + index + ']')[0];
	if( ! datatype.is_information_collector ){
		
		element.removeAttribute("checked");
		element.setAttribute("disabled", "disabled");
	}else{
		element.removeAttribute("disabled");
		element.removeAttribute("checked");
	}
	
	var element = document.getElementsByName('attribute_can_translate[' + index + ']')[0];
	if( ! datatype.translatable ){
		element.setAttribute("checked", "checked");
		element.setAttribute("disabled", "disabled");
	}else{
		element.removeAttribute("disabled");
		element.removeAttribute("checked");
	}
}

function easypublishClassAddAttributeRow(){
	var table = document.getElementById('attributeTable');
	var rows = table.getElementsByTagName("tr");
	
	var length = rows.length;
	
	var found = 0;
	var i = 1;
	while( ! found ){
		lastElement = rows[length - i];
		var html = lastElement.innerHTML;
		if( html.match(/attribute_/) ){
			found = 1;
		}
		i++;
	}
	
	var newRow = lastElement.cloneNode(true);
	tbody = table.getElementsByTagName('tbody')[0];
	tbody.appendChild(newRow);
	
	// replace newRow content;
	var inputs = newRow.getElementsByTagName('input');
	
	// modify the content, reset the value;
	var one = inputs[1];
	var name = one.getAttribute('name');
	var matches =  name.match(/(\d+)/);
	var index = parseInt(matches[0]);
	var newIndex = index + 1;
	
	for(var i = 0; i < inputs.length; i++){
		var input = inputs[i];
		name = input.getAttribute('name');
		if( name ){
			name = name.replace(/(\d+)/, newIndex);
			input.setAttribute('name', name);
			input.value = "";
		}
	}
	
	var selects = newRow.getElementsByTagName('select');
	for(var i = 0; i < selects.length; i++){
		var select = selects[i];
		name = select.getAttribute('name');
		if( name ){
			name = name.replace(/(\d+)/, newIndex);
			select.setAttribute('name', name);
		}
	}
}

function easypublishClassCheckForm(){
	
	var class_name = document.getElementsByName('class_name')[0];
	var class_identifier = document.getElementsByName('class_identifier')[0];
	var class_original_identifier = document.getElementsByName('class_original_identifier')[0];
	var classes_list = document.getElementById('classes_list');
	
	var id = easypublishTrim(class_identifier.value);
	var original_id = easypublishTrim(class_original_identifier.value);
	var name = easypublishTrim(class_name.value);
	
	var options = classes_list.options;
	var classes = [];
	
	for( var i = 0; i < options.length; i++ ){
		classes.push(options[i].value);
	}
	
	// check name at first
	if( name == '' ){
		alert( 'Class name must not been empty!');
		class_name.focus();
		return false;
	}
	
	// new class
	if( original_id == '' ){
		if( inArray(id, classes) ){
			alert( 'Class identifier has already exists!');
			class_identifier.focus();
			return false;
		}
		return true;
	// edit class interface
	}else{
		if( id != original_id && inArray(id, classes) ){
			alert( 'Class identifier has been used, please choose another!' );
			class_identifier.
			class_identifier.focus();
			return flase;
		}
		return true;
	}
	
	
	function inArray(e, arr){
		for(var i=0; i < arr.length; i++){
			if( e == arr[i] ){
				return true;
			}
			return false;
		}
	}
	
}


function easypublishClassOnDocumentLoad(){
	var input = document.getElementsByName('class_name')[0];
	input.focus();
}

easypublishEvent.add( window, 'load', easypublishClassOnDocumentLoad);
