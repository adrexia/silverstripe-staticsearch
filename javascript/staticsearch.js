jQuery(function($) {
	$('#tipue_search_input').tipuesearch({
		'mode': 'static',
		'contentLocation': '/search_index.js'
	});

	$("form.no-pageload input").keypress(function(e){
		var k = e.keyCode || e.which;
		if(k == 13){
			e.preventDefault();
		}
	});

	$("form.button-submit input.submit-button").click(function(e){
		this.form.submit();
	});
});