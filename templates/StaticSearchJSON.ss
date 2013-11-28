var tipuesearch = {"pages": [
	<% loop $Me %><% loop $getStaticSearchFields %>{"title": "$Title", "text":"$Text", "tags":"$Tags", "loc":"$Loc"}<% end_loop %><% if not $Last %>,<% end_if %><% end_loop %>
]};