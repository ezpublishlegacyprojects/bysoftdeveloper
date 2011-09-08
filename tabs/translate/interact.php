<?php 
return <<<EOT
function bysoftdeveloperShowTranslateTab(){
	_get('bysoftdeveloper-translate-source').onblur = function(e){
		var sourceText = _get('bysoftdeveloper-translate-source').value;
	    sourceText = sourceText + '';
	    sourceText = sourceText.replace(/\\&/g, '&amp;');
	    sourceText = sourceText.replace(/\\\"/g, '&quot;');
	    sourceText = sourceText.replace(/\\'/g, '&apos;');
	    sourceText = sourceText.replace(/\\</g, '&lt;');
	    sourceText = sourceText.replace(/\\>/g, '&gt;');
	    
	    // target
	    _get('bysoftdeveloper-translate-result').value = sourceText;
	}
}

EOT;
