<?php
/**
 * Understrap Child Theme functions and definitions
 *
 * @package UnderstrapChild
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;



/**
 * Removes the parent themes stylesheet and scripts from inc/enqueue.php
 */
function understrap_remove_scripts() {
	wp_dequeue_style( 'understrap-styles' );
	wp_deregister_style( 'understrap-styles' );

	wp_dequeue_script( 'understrap-scripts' );
	wp_deregister_script( 'understrap-scripts' );
}
add_action( 'wp_enqueue_scripts', 'understrap_remove_scripts', 20 );



/**
 * Enqueue our stylesheet and javascript file
 */
function theme_enqueue_styles() {

	// Get the theme data.
	$the_theme     = wp_get_theme();
	$theme_version = $the_theme->get( 'Version' );

	$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
	// Grab asset urls.
	$theme_styles  = "/css/child-theme{$suffix}.css";
	$theme_scripts = "/js/child-theme{$suffix}.js";

	$css_version = $theme_version . '.' . filemtime( get_stylesheet_directory() . $theme_styles );

	wp_enqueue_style( 'child-understrap-styles', get_stylesheet_directory_uri() . $theme_styles, array(), $css_version );
	wp_enqueue_script( 'jquery' );

	$js_version = $theme_version . '.' . filemtime( get_stylesheet_directory() . $theme_scripts );

	wp_enqueue_script( 'child-understrap-scripts', get_stylesheet_directory_uri() . $theme_scripts, array(), $js_version, true );
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );



/**
 * Load the child theme's text domain
 */
function add_child_theme_textdomain() {
	load_child_theme_textdomain( 'understrap-child', get_stylesheet_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'add_child_theme_textdomain' );



/**
 * Overrides the theme_mod to default to Bootstrap 5
 *
 * This function uses the `theme_mod_{$name}` hook and
 * can be duplicated to override other theme settings.
 *
 * @return string
 */
function understrap_default_bootstrap_version() {
	return 'bootstrap5';
}
add_filter( 'theme_mod_understrap_bootstrap_version', 'understrap_default_bootstrap_version', 20 );



/**
 * Loads javascript for showing customizer warning dialog.
 */
function understrap_child_customize_controls_js() {
	wp_enqueue_script(
		'understrap_child_customizer',
		get_stylesheet_directory_uri() . '/js/customizer-controls.js',
		array( 'customize-preview' ),
		'20130508',
		true
	);
}
add_action( 'customize_controls_enqueue_scripts', 'understrap_child_customize_controls_js' );

/**
 * Filter the excerpt length to 50 words.
 *
 * @param int $length Excerpt length.
 * @return int (Maybe) modified excerpt length.
 */
function hera_excerpt_length( $length ) {
    if ( is_admin() ) {
        return $length;
    }
    return 40;
}
add_filter( 'excerpt_length', 'hera_excerpt_length', 999 );

// Get the excerpt without "Read more" link
function custom_excerpt_without_read_more( $excerpt ) {
    $excerpt = preg_replace( '/<a[^>]+>(.*)<\/a>/', '', $excerpt );
    return $excerpt;
}
add_filter( 'get_the_excerpt', 'custom_excerpt_without_read_more' );

include 'blocks/custom-blocks.php';




add_action('widgets_init', 'hera_widgets_init');
function hera_widgets_init() {
    register_sidebar(
        array(
            'name'          => esc_html__( 'Search HERA', 'hera-theme' ),
            'id'            => 'search-1',
            'description'   => esc_html__( 'Add widgets here.', 'hera-theme' ),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        )
    );
}

// redirect user to their account page when they're logged in
function hera_add_login_check()
{
    if ( is_user_logged_in() && is_page(1978) ) {
        wp_redirect('/user-account/');
        exit;
    }
}
add_action('wp', 'hera_add_login_check');

if ( ! wp_next_scheduled( 'hera_course_completion_check' ) ) {
    wp_schedule_event( time(), 'hourly', 'hera_course_completion_check' );
}

function hera_set_content_type(){
    return "text/html";
}
add_filter( 'wp_mail_content_type','hera_set_content_type' );

add_action( 'hera_course_completion_check', 'hera_course_completion_check_exec' );
function hera_course_completion_check_exec(){
    $db = @mysqli_connect(
        getenv("WORDPRESS_DB_HOST"),
        getenv("WORDPRESS_DB_USER"),
        getenv("WORDPRESS_DB_PASSWORD"),
        getenv("WORDPRESS_DB_NAME"))
    or die('Error connecting to database');

    $query = "SELECT u.ID, u.user_login, u.user_email, u.display_name, c.end_time
                FROM `wp_users` as u
                LEFT JOIN `wp_stm_lms_user_courses` c
                ON u.ID = c.user_id
                WHERE c.progress_percent = 100 AND
                c.end_time > (SELECT executed
                FROM `wp_cron_logs`
                WHERE info = 'Finished hera_course_completion_check'
                ORDER BY executed DESC
                LIMIT 1);";
    $result = @mysqli_query($db, $query) or die('Error loading details.');

    $count = mysqli_num_rows($result);
    if($count > 0) {
        $body = '<p>These following students completed the HERA AI Literacy Course.</p><ul>';

        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $body .= "<li><strong>{$row['display_name']}</strong> <i>({$row['user_email']})</i></li>";
        }

        $body .= '</ul>';

        @mysqli_close($db);

        $subject = "Students Have Completed HERA AI Literacy Course";

        /*echo 'start';
        add_action('wp_mail_failed', function($error){
            var_dump($error);
        });*/

        $headers = 'From: HERA <' . get_option('admin_email') . '>';
        wp_mail(get_option('admin_email'), $subject, $body, $headers);
    }
}




