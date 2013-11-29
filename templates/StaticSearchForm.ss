<form action="search" role="search" class="<% if $URLSegment == SearchPage_Controller %>no-pageload<% else %>button-submit<% end_if %>">
	<input id="tipue_search_input" class="search-query span2" type="search" name="q" placeholder="Search" value="" title="Enter search terms" />
	<input type="button" class="submit-button" value="Go" id="tipue_search_button" />
</form>