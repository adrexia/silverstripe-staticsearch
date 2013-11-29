<?php
/**
 * Task to regenerate the search index JSON file, used by the search 
 * javascript library to return search results
 */
class BuildStaticSearchIndexTask extends BuildTask {

	protected $description = 'Generates a JSON file of all indexed pages';

	public function __construct() {
		parent::__construct();
		if ($this->config()->get('disabled') === true) {
			$this->enabled = false;
		}
	}

	/**
	 * 
	 * @param SS_HTTPRequest $request
	 */
	public function run($request) {

		$cacheBaseDir = singleton('FilesystemPublisher')->getDestDir();

		// First generate the search file for the base site
		$viewer = new SSViewer(array('StaticSearchJSON'));
		$item = new ViewableData($this); 
		$json = $viewer->process($this->getAllLivePages(0));

		$domain = Config::inst()->get('FilesystemPublisher', 'static_base_url');

		$urlFragments = parse_url($domain);
		$cacheDir = $cacheBaseDir . "/" . $urlFragments['host'];
		file_put_contents($cacheDir .'/search_index.html', $json); 

		if(class_exists('Subsite')) {

			// Then generate the files for the subsites
			$subsites = Subsite::all_sites();
			foreach ($subsites as $subsite) {
				$viewer = new SSViewer(array('StaticSearchJSON'));
				$item = new ViewableData($this); 
				$json = $viewer->process($this->getAllLivePages($subsite->ID));

				$domains = DataObject::get("SubsiteDomain")->filter(array("SubsiteID"=>$subsite->ID));

				foreach ($domains as $domain){
					$urlFragments = parse_url($domain->Domain);
					$cacheDir = $cacheBaseDir . "/" . $urlFragments['path'];
					file_put_contents($cacheDir .'/search_index.html', $json); 
				}
			}
		}
		return true;
	}

	/**
	 * 
	 * @return DataList
	 */
	protected function getAllLivePages($subsiteID = 0) {
		ini_set('memory_limit', '200M');
		$oldMode = Versioned::get_reading_mode();
		if(class_exists('Subsite')) {
			Subsite::disable_subsite_filter(true);
		}
		Versioned::reading_stage('Live');
		$pages = DataObject::get("SiteTree");
		Versioned::set_reading_mode($oldMode);
		if(class_exists('Subsite')) {
			return $pages->filter(array('SubsiteID'=>$subsiteID));
		}
		return $pages;
	}
	
}
