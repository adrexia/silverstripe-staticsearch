# Static Search

An implementation of Tipue search (http://www.tipue.com/search/docs/) designed to work in SilverStripe with the Static Publish Queue module. This implementation uses the static mode of Tipue search.

(for in development branch of static publish queue)

This module works with:
	* subsites (optional)
	* taxonomy module (optional)
	* static publish queue (mandatory)

## Approach

* Extension to SiteTree to add methods that generate data for searchable fields. These can be overridden at Page level if necessary.

* Build Task to generate json index files for each subsite (stored in the cache directory). Format:
	{
		"title": "page title", 
		"text":"Prepped page content", 
		"tags":"Taxonomy or meta description", 
		"loc":"Page URL"
	}

* Basic implementation of Tipue JavaScript search, in static mode.

* Search page controller to hold results of search (filled in by JavaScript search)

## Set-up

1. Add this to your composer.json file: "adrexia/staticsearch": "dev-master"

2. Run composer update

3. Add the search form to your page: <% include SearchForm %>

4. Include StaticSearchRequirements in the _head_ of your page. If you like, copy this to your theme, and override with any customized files. These files are only needed for the search page itself. Read the docs if experience any issues with the search itself. It can be a bit particular about its set up. http://www.tipue.com/search/docs/

5. If needed, copy SearchPage.ss to your theme folder so you can make it fit your page design. There is base css provided in staticsearch/css/tipuesearch.css. You can use this as is, or replace with your own css.

6. Decide if you need to override any of the methods generating the data for the json files (staticsearch/code/extensions/StaticSearchSiteTreeExtension). You may wish to have a smaller range of data appear, or you might want to index data objects as content on the page. These methods can be overridden at Page level. It is suggested that if you are working with larger statically published sites, you use the tagging system (getSearchableTags() ) as the main search mechanism, and limit the content block to a simple abstract.

7. Run the BuildStaticSearchIndexTask to generate the json search_index files (note, the static publish queue tasks must have been run at least once to generate the cache itself).

    dev/tasks/BuildStaticSearchIndexTask

You will want to set this task up to run once a day to update the search index.
