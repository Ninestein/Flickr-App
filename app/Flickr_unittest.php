<?php
require_once 'PHPUnit/Framework.php';

class Flickr_unittest extends PHPUnit_Framework_TestCase {
    // contains the object handle of the string class
    var $Flickr;

    // constructor of the test suite
    function Flickr_unittest($name) {
		$this->PHPUnit_TestCase($name);
    }

    // called before the test functions will be executed
    // this function is defined in PHPUnit_TestCase and overwritten
    // here
    function setUp() {
		$this->Flickr=new Flickr();
    }

    // called after the test functions are executed
    // this function is defined in PHPUnit_TestCase and overwritten
    // here
    function tearDown() {
		//Tidying memory
		unset($this->Flickr);
    }
	
	
	//Test keyword validation
	function testKeywordValidation_empty() {
        //Set the test value
		$_GET['keyword'] = '';
		$result = $this->Flickr->keyword();
		$expected = ''; //Expected empty result
		$this->assertTrue($result == $expected);
    }
	
	/**
     * @depends testKeywordValidation_empty
     */
	function testKeywordValidation() {
        //Set the test value
		$_GET['keyword'] = 'test';
		$result = $this->Flickr->keyword();
		$expected = ''; //Expected empty result
		$this->assertTrue($result == $expected);
    }
	
	
	//Test page number validation
	function testPageNumberValidation_notNumber() {
        //Set the test value
		$_GET['page'] = 'abc';
		$result = $this->Flickr->get_page_number();
		$expected = '1'; //Expected empty result
		$this->assertTrue($result == $expected);
    }
	
	/**
     * @depends testPageNumberValidation
     */
	function testPageNumberValidation() {
        //Set the test value
		$_GET['page'] = '7';
		$result = $this->Flickr->get_page_number();
		$expected = '7'; //Expected empty result
		$this->assertTrue($result == $expected);
    }
	
	
	//Test keyword validation
	function testKeywordValidation() {
        //Set the test value
		$_GET['page'] = '7';
		$result = $this->Flickr->get_page_number();
		$expected = '7'; //Expected empty result
		$this->assertTrue($result == $expected);
    }
	
	
	//Test search with a basic keyword
	function testSearch() {
        //Set the test value
		$_GET['keyword'] = 'test';
		$result = $this->Flickr->search_flickr();
		$this->assertNotEmpty($result);
    }
  }
?>