<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package _s
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<link rel="icon" href="<?php bloginfo( 'url' ); ?>/favicon.ico" type="image/x-icon" />
<link rel="shortcut icon" href="<?php bloginfo( 'url' ); ?>/favicon.ico" />
<link rel="apple-touch-icon-precomposed" href="<?php bloginfo( 'template_url' ); ?>/images/favicon-icon.png" />

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<header id="site_header" class="site_header">
	  <div class="outside">
	    <span class=stripe>

	      <span class="toggle_menu">
	        <div class="line line-1"></div>
	  			<div class="line line-2"></div>
	  			<div class="line line-3"></div>
	        <a class="toggle"></a>
	      </span>

	      <a class="site-title" href="{{ site.baseurl }}">logo</a>

	    </span>

	    <nav id="site-nav" class="site-nav">
	      <ul id="mainNav" class="mainNav">
	        <li><a class="page-link" href="#">Link</a></li>

	      </ul>
	    </nav>

	  </div>

	</header>
	<div id="page" class="page">
