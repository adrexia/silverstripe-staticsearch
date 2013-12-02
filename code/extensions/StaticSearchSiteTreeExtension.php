<?php
/**
 * Extension to add Basic methods required by Static Search. 
 * If you wish, you can override these methods at page level to add your own 
 * Search criteria
 *
 * @package StaticSearch
 * @category extension
 */
class StaticSearchSiteTreeExtension extends DataExtension {

	/*
	 * Returns an array of words to be excluded from search
	 *
	 * @return array
	 */
	public function getExcludedWords(){
		return array(
			" a ", " and ", " be ", " by ",
			" do ", " for ", " he ",
			" how ", " if ", " is ",
			" it ", " my ", " not ",
			" of ", " or ", " the ",
			" to ", " up ", " what ",
			" when "
		);
	}

	/*
	 * Returns the fields needed for the static site search
	 * - Title: Name of page
	 * - Text: All searchable content
	 * - Tags: Extra means of associating words with search
	 * - Loc: Absolute url to page
	 *
	 * @return ArrayList
	 */
	public function getStaticSearchFields(){
		$results = new ArrayList();

		$results->push(new ArrayData(array(
			'Title' => $this->owner->Title,
			'Text' => $this->owner->getSearchableContent(),
			'Tags' => $this->owner->getSearchableTags(),
			'Loc' => $this->owner->Link()
		)));

		return $results;
	}

	/*
	 * Returns all content and related dataobject content that should be searchable 
	 * Tip: if you have a lot of pages on your site, use an abstract field for this
	 * and search via a tag (keyword) based system rather than fulltext.
	 *
	 * Should not contain html or unescaped double quotations. 
	 * Removes a list of excluded words, plus all line breaks
	 * 
	 * @return Text
	 */
	public function getSearchableContent(){
		$strip = array('"',"\r", "\n");
		$replace = array("'"," ", " ");
		$content = $this->owner->dbObject('Content')->NoHTML();

		$abstract = substr($content, 0, 100);
		$prepped =  str_replace($this->getExcludedWords(), ' ', substr($content, 100));

		$result = $abstract.$prepped;

		return str_replace($strip, $replace, $result);
	}


	/*
	 * Returns a list of searchable tags. 
	 * 
	 * @return Text
	 */
	public function getSearchableTags(){
		$data = $this->owner->MetaDescription();
		return str_replace(array('"',"\r", "\n"), array("'"," ", " "), $data);
	}

	/**
	 * Cache search with the home url to make sure it 
	 * a. belongs to a subsite and
	 * b. only gets cached once
	 *
	 * @return array
	 */
	public function urlsToCache() {
		if($this->owner->URLSegment == 'home'){
			$urls[Director::BaseURL().'search'] = 0;
		}
		$urls[$this->owner->Link()] = 0;

		return $urls;
	}
}
