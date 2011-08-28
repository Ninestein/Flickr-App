<?php
class Flickr extends F3instance {
	private $ajax = false;
	
	//Our Flickr details
	private $flickr = array(
		'apiKey' 	=> '5a8f8c7d813afca675ebc7bd0bb3c5ff',
		'url'		=> 'http://flickr.com/services/rest/',
		'method'	=> 'flickr.photos.search',
		'async'		=> 1,
		'is_public' => 1,
		'format'=> 'json',
	);
	
	//results per page
	public $perpage = 5;
	
	//The start value
	public $page_number 	= 1; 

	/*
	*
	* This is the default page handler
	*
	*/
	function home() {
		//If there's a keyword set, do a search
		if(isset($_GET['keyword'])) $this->search_flickr();		
		
		// Use the home.htm template
		$this->set('pagetitle','Home');
		$this->set('template','home');
	}
	
	
	/*
	*
	* Ajax request handler.
	*
	* Note, it sets a header and ends the request once it has the data with an exit to ensure all framework tidying is done correctly
	*
	*/
	function ajax() {
		$this->ajax = true;
		
		if(isset($_GET['keyword'])) $this->search_flickr();		
		
		//Set the mime type (helps the jquery)
		header('Content-type: application/json');
		
		//Stop the page processing and output the result
		exit($this->get('results'));
	}
	
	
	/*
	*
	* Searches flickr for images based on a keyword submitted
	*
	*/
	function search_flickr() {
		// Reset previous error message, if any
		$this->clear('message');
		
		// Form field validation
		$this->keyword();
		
		//See if there's a page set
		$this->get_page_number();
		
		//If theres a keyword, start the fetch
		if (!$this->exists('message')) {
			
			//Build the search string parameters
			$search_args = array(
				'method'	=> 'flickr.photos.search',
				'async' 	=> $this->flickr['async'],
				'is_public'	=> $this->flickr['is_public'],
				'api_key' 	=> $this->flickr['apiKey'],
				'text'		=> urlencode($_GET['keyword']),
				'per_page'	=> $this->perpage,
				'format'	=> $this->flickr['format'],
				'page'		=> $this->page_number,
			);			
			
			//Make the request
			$curl =	curl_init($this->flickr['url']);
					curl_setopt($curl, CURLOPT_POST, true);
					curl_setopt($curl, CURLOPT_POSTFIELDS, $search_args);
					curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
					
			$results = curl_exec($curl);
					curl_close($curl);
			
			//Strip out the flickr api wrapper (gets in the way)
			$results = preg_replace('|jsonFlickrApi\(|','',$results);
			$results = substr($results, 0, strlen( $results ) - 1 );
			
			//If it's not an ajax request, convert the json to an array for the template
			if(!$this->ajax) {
				//Decode it to an array
				$results = json_decode($results, true);		
			}
			
			//And save the result for the template
			$this->set('results',$results);
			
			return $results;
		}
	}	
	
	
	/*
	*
	* Minify files
	*
	*/
	function minified() {
		if (isset($_GET['base']) && isset($_GET['files'])) {
			$_GET=$this->scrub($_GET);
			Web::minify($_GET['base'],explode(',',$_GET['files']));
		}
	}

	/*
	*
	* Get the keyword search
	*
	*/
	function keyword() {
		
		$this->input('keyword',
			function($value) {
				if (!F3::exists('message')) {
					if (empty($value))
						F3::set('message','You did not enter a search term');
				}
				//No processing required but saved back for completeness
				$_GET['keyword']=$value;
				
				return $value;
			}
		);
	}
	
	/*
	*
	* Get the page number
	*
	*/
	function get_page_number() {
		if(isset($_REQUEST['page'])) {
			$this->input('page',
				function($value) {
					if((int)$value == '') $_GET['page'] = $this->page_number;
					//No processing required
					//$_GET['page']=$value;
				}
			);
			$this->page_number = $_GET['page'];
			
			return $this->page_number;
		}
	}

	/*
	*
	* The first bit of the page load
	*
	*/
	function beforeroute() {
		//Nothing required here
	}

	/**
		Process run, now serve the template
	**/
	function afterroute() {
		//Default serve the layout template
		echo Template::serve('layout.htm');
	}

}
