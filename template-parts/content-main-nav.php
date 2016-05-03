<?php

// <nav id="site-nav" class="site-nav">
//   <ul id="mainNav" class="mainNav">
//     <li><a class="page-link" href="#">Linking format...</a></li>
//
//   </ul>
// </nav>

$subs = array(6);
$main_nav = get_custom_main_nav( $subs );
// $bread_crumb = $GLOBALS['ak_page2']['bread_crumb'];

// require get_template_directory() . '/navigation-object.php';
// $navigation = new get_main_navigation($subs);
?>

<nav id="site-nav" class="site-nav">
  <ul id="mainNav" class="mainNav">
  <?php foreach($main_nav as $key1=>$item) {

  	$li_class = array();
  	if( $bread_crumb[0]['ID'] == $item['ID'] ) {
      array_push( $li_class, 'current' );
    }
  	if( isset($item['children']) ) {
      array_push( $li_class, 'parent' );
    }

  	if( count($li_class) > 0 ) {
  		$li_class = implode( ' ', $li_class );
  		$li_class = ' class="' . $li_class . '"';
  	} else {
  		$li_class = '';
  	}

  ?>


  	<li<?php echo $li_class ?>><a href="<?php echo $item['permalink'] ?>"><?php echo $item['title'] ?></a>
      <?php
        // if(isset($item['children'])) { get_template_part( 'template-parts/content', 'main-nav-children' ); }
      ?>
  	</li>

  <?php } ?>

  </ul>
</nav>
