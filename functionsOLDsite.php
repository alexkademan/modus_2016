<?php
/*
 * Functions2.php first written on 3-23-14,
 * imported to the program in the last line of functions.php
 *
 */

/*
 *  ak_startup to set some variables at pageload time:
 *
 *  adds $GLOBALS['ak_page2'] This is used to trace parts of the portfolio back to their source.
 *  I had to get creative with the portfolio matching pages and post categories to work in ways that WP just doesn't like.
 *
 */

function ak_startup() {

	$permalink = basename( get_permalink() );
	// print_r($permalink);

	if(is_page()) {

		$lineage = get_post_ancestors( $the_ID );

		$this_page['ID'] = get_queried_object_id();;
		$this_page['title'] = $permalink;

		if( count($lineage) > 0 ) {


			foreach( $lineage as $key => $generation ) {

				$bread_crumb[$key]['ID'] = $generation;
				$bread_crumb[$key]['title'] = get_the_title( $generation );

			}
		}
		$bread_crumb[count($bread_crumb)] = $this_page;
		$page['bread_crumb'] = $bread_crumb;
	}


	if(is_single()) {


		$lineage = get_the_category( );

		foreach( $lineage as $key=>$cat ) {

			if(isset($this_one)) unset($this_one);

			$this_one['cat_ID'] = $cat->cat_ID;
			$this_one['name'] = $cat->name;
			$this_one['slug'] = $cat->slug;
			$this_one['category_count'] = $cat->category_count;
			$this_one['category_parent'] = $cat->category_parent;



			$categories[$key] = $this_one;

		}


		if($categories[0]['category_parent'] !== '' && isset($categories[0]['category_parent'])) {
			$cat_list[0] = get_category($categories[0]['cat_ID'], ARRAY_A);
		}


		$x = 0;
		for($i = 0; $i < 1; ++$x ){

			if( $cat_list[$x]['category_parent'] == '' OR !isset($cat_list[$x]['category_parent']) ) {
				$i = 1;
			} else {
				array_push( $cat_list, get_category( $cat_list[$x]['category_parent'], ARRAY_A ));

			}

		}

		$cat_list = array_reverse($cat_list);

		foreach($cat_list as $key=>$cat) {
			$page_list[$key] = get_page_by_title( $cat_list[$key]['name'], ARRAY_A );
			$bread_crumb[$key]['ID'] = $page_list[$key]['ID'];
			$bread_crumb[$key]['title'] = $page_list[$key]['post_name'];
		}



		$page['bread_crumb'] = $bread_crumb;
	}



	if( isset($page) ) {

		global $ak_page2;

		$ak_page2 = $page;

	}


}

/*
 *  Find a list of the sibling pages so I can list them out on screen
 *
 */

function get_siblings() {

	$parentID = $GLOBALS['ak_page2']['bread_crumb'][0]['ID'];

	$all = get_children( "post_parent=$parentID", ARRAY_A ); // array of posts

	//sort by menu_order:
	function cmp($a, $b) {
		return $a['menu_order'] - $b['menu_order'];
	}

	usort($all,"cmp");

	// throw to the site:
	$GLOBALS['ak_page2']['siblings'] = $all;
}

/*
 * Find what level of the portfolio we are looking at:
 *
 */

function get_work_depth() {
	$level = $GLOBALS['ak_page2']['bread_crumb'];
	$level = count($level);

	// call the get_siblings for later on this page:
	get_siblings();
	return $level;
}

/*
 *  get the posts from a category even though we only know of a page by the same title
 *
 */
function ak_get_cat_posts( $categoryID = FALSE ) {

  if( $categoryID == FALSE ) {

    // the function was NOT given a category ID:
    $all_cats = get_categories( );

    $parent = $GLOBALS['ak_page2']['bread_crumb'][1]['title'];
    $parent = str_replace('-', ' ', $parent); // swap out the hyphens form the page name to match category slug


    foreach($all_cats as $cat) {
      if(strtolower($cat->cat_name) == strtolower($parent)) {

        $post_parent = $cat->cat_ID;

      }
    }
  } else {
    $post_parent = $categoryID->cat_ID;

  }

  // print_r($post_parent);

	if(isset($post_parent)) {

		$args = array(
			'posts_per_page'   => -1,
			'offset'           => 0,
			'category'         => $post_parent,
			'orderby'          => 'post_date',
			'order'            => 'DESC',
			'include'          => '',
			'exclude'          => '',
			'meta_key'         => '',
			'meta_value'       => '',
			'post_type'        => 'post',
			'post_mime_type'   => '',
			'post_parent'      => '',
			'post_status'      => 'publish',
			'suppress_filters' => false
		);
		$posts_array = get_posts( $args );

		return $posts_array;
	} else {
		return false;
	}
}


/*
 *  Allow for excerpts in the pages (not just posts)
 *
 * */
add_action( 'init', 'my_add_excerpts_to_pages' );
function my_add_excerpts_to_pages() {
     add_post_type_support( 'page', 'excerpt' );
}
/*
 * Get the pages that are the child of a certain ID
 *
 */
function get_abt_pages($parent_id) {

	if($parent_id == 0) {
		$pages = get_pages('parent=0');

	} else {
		$pages = get_pages('child_of=' . $parent_id);
	}

	if(is_array($pages)){
		foreach($pages as $page){

			// print_r($page);

			if($page->post_status == 'publish'){

				$team[$page->menu_order]['ID'] = $page->ID;
				$team[$page->menu_order]['title'] = $page->post_title;
				$team[$page->menu_order]['excerpt'] = $page->post_excerpt;
				$team[$page->menu_order]['content'] = $page->post_content;

				// $team[$page->menu_order]['thumbID'] = get_post_thumbnail_id($page->ID);
				$thumb = wp_get_attachment_image_src(get_post_thumbnail_id($page->ID), medium);
				$team[$page->menu_order]['thumb'] = $thumb[0];

			}

		}
		ksort($team); // sort by key to get proper order
		return $team;
	} else {
		return false;
	}

}

/*
 *  Get custom main navigation, dependent on get_abt_pages()
 * 	$subs = any pages that should include sub pages (array of IDs)
 */
function get_custom_main_nav($subs = FALSE) {
	$pages = get_abt_pages(0); // fetch all the root pages

	foreach($pages as $key=>$page) {

		$pages[$key]['permalink'] = get_permalink($pages[$key]['ID']);

		foreach($subs as $sub){
			if($sub == $page['ID']) {
				$pages[$key]['children'] = get_abt_pages($page['ID']);
			}
		}

		if(isset($pages[$key]['children'])) {
			foreach($pages[$key]['children'] as $key2=>$child) {

				// print_r(get_permalink($child['ID']));
				$pages[$key]['children'][$key2]['permalink'] = get_permalink($child['ID']);
			}
		}

	}

	return $pages;

}


/**
 * Show one single individual post and an "edit" button if the client is logged in:
 *
 * */
function ak_individual_post($postID, $shortcode = TRUE) {

	// echo $postID;
	$post_details = get_post($postID);


	$content = $post_details->post_content;
	// echo $content;

	if($shortcode == TRUE) { // if true, than process the shortcodes. if false than strip them from the content:
		// echo $content;
		$content = do_shortcode($content);
	} else {
		$content = remove_all_shortcodes( $content );
	}
	$content = wpautop($content); // format for readability



	if ( current_user_can('edit_post', $postID) ) { // the page is being viewed by someone who is logged in as an admin:

		echo '<span class="edit-link1">' . "\n";
		echo  $content;
		edit_post_link( 'Edit', '<span class="edit-link2">', '</span>', $postID );
		echo '</span>' . "\n";
	} else {
		echo  $content;
	}
}

/*
 * takes the WP generated thumbnail tag and strips everything off of the src="" info
 *
 *
 */

function cut_out_thumbnail($id) {

	$html = get_the_post_thumbnail($id, 'full');

	// this should be done with some nice clean regex, but I don't know how right now!@#!
	// it's only 3 lines, but still, this seems like a terrible solution?
	$html = explode( 'src="', $html );
	$html = explode( '"', $html[1] );
	$html = $html[0];

	return $html;

}


?>
