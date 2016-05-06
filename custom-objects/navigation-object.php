<?php

class get_main_navigation extends site_info {

  function __construct( ) {
    parent::__construct(); // get the site-wide variables that are typed into site-info.php
    $this->full_navigaiton = $this->get_full_navigation( $this->subs ); // find all the root pages
  }


  private function get_full_navigation( $subs = FALSE ) {
    // find all the root pages:
    $full_navigation = $this->get_pages_info(0);

    // there are not any pages that we need a drop down of.
    foreach( $full_navigation as $key => $page ) {
      if($subs != FALSE ){
        // go thru list of page ids that we need to show the children of:
        foreach($subs as $sub){
          if($sub == $page['ID']) {
            $full_navigation[$key]['children'] = $this->get_pages_info($page['ID']);
          }
        }
      } else {
        // echo "++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++\n";
        // OR, go thru every page and look for children.
        $full_navigation[$key]['children'] = $this->get_pages_info($page['ID']);
      }
    }
    return $full_navigation;
  }

  private function get_pages_info($parent_id) {
    // put together an array about all child pages of a specific id:
    if($parent_id == 0) {
  		$pages = get_pages('parent=0');
  	} else {
  		$pages = get_pages('child_of=' . $parent_id);
  	}

  	if(is_array($pages)){
  		foreach($pages as $page){
  			if($page->post_status == 'publish'){

  				$team[$page->menu_order]['ID'] = $page->ID;
  				$team[$page->menu_order]['title'] = $page->post_title;
  				$team[$page->menu_order]['excerpt'] = $page->post_excerpt;
  				$team[$page->menu_order]['content'] = $page->post_content;
  				$team[$page->menu_order]['permalink'] = get_permalink( $page->ID );

  				$thumb = wp_get_attachment_image_src(get_post_thumbnail_id($page->ID), medium);
  				$team[$page->menu_order]['thumb'] = $thumb[0];
  			}
  		}
  		if(is_array($team)){
        ksort($team); // sort by key to get proper order
      }
  		return $team;
  	} else {
  		return false;
  	}
  }
}
