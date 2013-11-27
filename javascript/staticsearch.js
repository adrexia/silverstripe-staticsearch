jQuery(function($) {

	$('#tipue_search_input').submit(function(){
		return false;
	});
	$('#tipue_search_input').tipuesearch({
		'mode': 'static',
		'contentLocation': '/search_index.js'
	});
});