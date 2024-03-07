<?php
/**
 * Plugin Name:       Catalog Course Blocks
 * Description:       Add course catalog to your site with these blocks
 * Requires at least: 6.1
 * Requires PHP:      7.0
 * Version:           0.1.0
 * Author:            The WordPress Contributors
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       catalog-course
 *
 * @package           hera
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
function catalog_course_catalog_course_block_init() {
	register_block_type( __DIR__ . '/build/blocks/catalog-course' );
}
add_action( 'init', 'catalog_course_catalog_course_block_init' );
include "patterns.php";
