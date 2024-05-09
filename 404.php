<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header();

$container = get_theme_mod( 'understrap_container_type' );
?>

<div class="wrapper" id="error-404-wrapper">

    <!-- wp:columns -->
    <div class="wp-block-columns"><!-- wp:column {"width":"15%"} -->
        <div class="wp-block-column" style="flex-basis:15%"></div>
        <!-- /wp:column -->

        <!-- wp:column {"width":"30%"} -->
        <div class="wp-block-column" style="flex-basis:30%"><!-- wp:heading {"level":1} -->
            <h1 class="wp-block-heading">Ooops!</h1>
            <!-- /wp:heading -->

            <!-- wp:paragraph -->
            <p>We can't seem to find a page that you're looking for.</p>
            <!-- /wp:paragraph -->

            <!-- wp:buttons -->
            <div class="wp-block-buttons home-btn"><!-- wp:button {"textColor":"gray-dark","style":{"elements":{"link":{"color":{"text":"var:preset|color|gray-dark"}}},"color":{"background":"#efca60"}}} -->
                <div class="wp-block-button"><a class="wp-block-button__link has-gray-dark-color has-text-color has-background has-link-color wp-element-button" href="https://dev.herawisconsin.org/" style="background-color:#efca60">Back To Home</a></div>
                <!-- /wp:button --></div>
            <!-- /wp:buttons --></div>
        <!-- /wp:column -->

        <!-- wp:column {"width":"51.28%"} -->
        <div class="wp-block-column" style="flex-basis:51.28%"><!-- wp:image {"id":3346,"sizeSlug":"full","linkDestination":"none","align":"right"} -->
            <figure class="wp-block-image alignright size-full"><img src="https://dev.herawisconsin.org/wp-content/uploads/2024/05/error.png" alt="Person climbing 404 error" class="wp-image-3346"/></figure>
            <!-- /wp:image --></div>
        <!-- /wp:column --></div>
    <!-- /wp:columns -->

</div><!-- #error-404-wrapper -->

<?php
get_footer();
