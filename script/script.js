//Our globals
var search_page;
var search_pages;
var search_keyword;
var search_total;
	
jQuery(document).ready(function() {
	//Catch the form submit
	jQuery('#flickr_search').submit(function() {
		
		//Get the keyword
		keyword = $('#keyword').val();
		
		//Call the search
		jQuery().do_search(keyword, 1);
		
		return false;
	});
	
	//Catch the page submit
	jQuery('.pagination a').each(function(index) { 
		$(this).click(function() {
			$(this).do_pagination();
			return false;
		});
	});
	
	jQuery().setup_slimbox();
});

/*
* Pagination call
*/
jQuery.fn.do_pagination = function() {
	//Get the values
	keyword 		= $('#keyword').val();
	page 			= parseInt($(this).attr('page'));
	
	//Call the search
	$result = jQuery().do_search(keyword, page);
	
	return false;
}

/*
* Setup slimbox options
*/
jQuery.fn.setup_slimbox = function() {
	jQuery("a[href^='http://www.flickr.com/photos/'] > img:first-child[src]").parent().slimbox({}, function(el) {
		return [el.firstChild.src.replace(/_[mts]\.(\w+)$/, ".$1"),
			(el.title || el.firstChild.alt) + '<br /><a href="' + el.href + '" target="_blank">View Flickr page</a>'];
	});
}

jQuery.fn.do_search = function(keyword, page) {
	if(search_keyword == '') return false;
	
	//Loading
	jQuery('#page_results').append('<div class="loading">&nbsp;</div>');	
	
	//Store these
	search_keyword 	= keyword;
	search_page 	= page;
	
	//The search string
	searchString 	= '/ajax?keyword=' + keyword + '&page=' + page;
	
	//Run the search
	jQuery.getJSON(searchString, function(data) {
		search_pages = data.photos.pages;
		
		//clear the old results
		jQuery('#page_results').html('<dl></dl>');
		
		//Loop through the Json and add it to the body
		jQuery.each(data.photos.photo, function(i,item){
			jQuery('#page_results dl').append(output_image_row(item));
		});
		
		output_pagination(data.pages);		
		
		//reset slimbox
		jQuery().setup_slimbox();
		
		return true;
	});
}


/*
*
*Function for outputing rows onto the page
*
*/
function output_image_row(item) {
	//build the item
	$item = '<dt><h2 class="title">' + (item.title != '' ? item.title : 'No title') + '</h2></dt>';
    //$item+= '<dd><a href="http://farm' + item.farm + '.static.flickr.com/' + item.server + '/' + item.id + '_' + item.secret + '.jpg" title="' + item.title + '" rel="Lightbox-flickr">';
	$item+= '<dd><a href="http://www.flickr.com/photos/' + item.owner + '/' + item.id + '/" title="' + item.title + '" rel="Lightbox-flickr" target="_blank">';
	//http://www.flickr.com/photos/{user-id}/{photo-id}
	$item+= '<img src="http://farm' + item.farm + '.static.flickr.com/' + item.server + '/' + item.id + '_' + item.secret + '_s.jpg" title="' + item.title + '" />';
	$item+= '</a></dd>';
	//return it
	return $item;
}


/*
*
*Function for outputing the pagination
*
*/
function output_pagination() {
	//Out back and forwards
	prev_page = (search_page > 1 ? search_page - 1 : 1);
	next_page = (search_page < search_pages ? search_page + 1 : search_pages);
	
	//generate the links
    $pagination = '<a href="?keyword=' + search_keyword + '" title="First page" id="page_first" page="1">&lt;&lt;</a> ';
    $pagination+= '<a href="?keyword=' + search_keyword + '&amp;page=' + prev_page +'" title="Previous page" id="page_prev" page="' + prev_page +'">&lt;</a> ';
    $pagination+= ' Page <span id="page_number">' + search_page + '</span> of '+ search_pages + ' ';
	$pagination+= '<a href="?keyword=' + search_keyword + '&amp;page=' + next_page + '" title="Next page" id="page_next" page="' + next_page + '">&gt;</a> ';
	$pagination+= '<a href="?keyword=' + search_keyword + '&amp;page=' + search_pages + '" title="Last page" id="page_last" page="' + search_pages + '">&gt;&gt;</a> ';
	
	//Output them
	jQuery('#pagination').html($pagination);
	
	//Catch the pagination clicks
	jQuery('.pagination a').each(function(index) { 
		$(this).click(function() {
			$(this).do_pagination();
			return false;
		});
	});
}