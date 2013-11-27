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


	function init() { 
		parent::init(); 
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
			'Loc' => $this->owner->AbsoluteLink()
		)));

		return $results;
	}

	/*
	 * Returns all content and related dataobject content that should be searchable 
	 * Tip: if you have a lot of pages on your site, use an abstract field for this
	 * and search via a tag (keyword) based system rather than fulltext.
	 *
	 * Should not contain html or unescaped double quotations
	 * 
	 * @return Text
	 */
	public function getSearchableContent(){
		$result = $this->owner->dbObject('Content')->NoHTML();
		return str_replace(array('"',"\r", "\n"), array("'"," ", " "), $result);
	}


	/*
	 * Returns a list of searchable tags. 
	 * 
	 * @return Text
	 */
	public function getSearchableTags(){
		if(class_exists('TaxonomyTerm') && $this->owner->TaxonomyTerm()) {
			$results = array();
			$data = $this->owner->TaxonomyTerm();
			foreach($data as $tag){
				array_push($results, $tag->Name);
			}
			return implode(', ', $results);
		}
		$data = $this->owner->MetaDescription();
		return str_replace(array('"',"\r", "\n"), array("'"," ", " "), $data);
	}
}
