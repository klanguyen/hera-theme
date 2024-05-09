<?php
/**
 * Single post partial template
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
$institution = get_field_object('hera_course_institution') ? get_field_object('hera_course_institution')['value']['value'] : '';
$format = get_post_meta(get_the_ID(), 'hera_course_format', true) ? ucfirst(get_post_meta(get_the_ID(), 'hera_course_format', true)) : 'N/A';
$topics = get_post_meta(get_the_ID(), 'hera_course_topic', true) ?? 'N/A';
$audience = get_post_meta(get_the_ID(), 'hera_course_audience', true) ?? 'N/A';
$duration = get_field_object('hera_course_duration') ? get_field_object('hera_course_duration')['value']['label'] : 'N/A';
?>

<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">

    <header class="entry-header">

        <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
        <div class="offered-by">
            <p>Offered by</p><span class="course-institution <?= $institution ?>"></span>
        </div>

        <div class="course-meta">

            <div class="meta-item">
                <span class="meta-name">Topic</span>
                <p>
                    <?= is_array($topics) ? join(', ', $topics) : $topics ?>
                </p>
            </div>
            <div class="meta-item">
                <span class="meta-name">Format</span>
                <p><?= $format ?></p>
            </div>
            <div class="meta-item">
                <span class="meta-name">Audience</span>
                <p><?= $audience ?></p>
            </div>
            <div class="meta-item">
                <span class="meta-name">Duration</span>
                <p><?= $duration ?></p>
            </div>

        </div><!-- .entry-meta -->

    </header><!-- .entry-header -->

    <?php echo get_the_post_thumbnail( $post->ID, 'large' ); ?>

    <div class="entry-content mt-4">

        <?php
        the_content();
        understrap_link_pages();
        ?>

    </div><!-- .entry-content -->

    <footer class="entry-footer">

        <?php understrap_entry_footer(); ?>

    </footer><!-- .entry-footer -->

</article><!-- #post-<?php the_ID(); ?> -->
