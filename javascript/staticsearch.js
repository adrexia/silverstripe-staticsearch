jQuery(function($) {
	
	//prevent undeclared variable
	var tipuesearch = {"pages": [
		{"title": "", "text":"", "tags":"", "loc":""}
	]};

	$('#tipue_search_input').tipuesearch({
		'mode': 'static',
		'contentLocation': '/search_index'
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