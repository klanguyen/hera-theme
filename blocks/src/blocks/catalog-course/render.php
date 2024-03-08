<?php
/**
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */

/**
 * @var array $attributes Attributes from the block
 * @var string $content The HTML returned from the save() function
 * @var WP_Block $block All the
 */

$query = new WP_Query([
	'post_type' => 'catalog-course',
	'orderby' => 'post_title',
	'order' => 'asc'
])

?>
<div <?php echo get_block_wrapper_attributes(); ?>>
	<?php while($query->have_posts()):
	$query->the_post();
	$insitution = get_field_object('hera_course_institution')['value'];
	?>
	<div class="card catalog-course mb-3">
		<div class="row no-gutters">
			<div class="col-md-4">
				<div class="course-institution <?= $insitution['value'] ?>"></div>
			</div>
			<div class="col-md-8">
				<div class="card-body">
					<h5 class="card-title"><?= get_the_title() ?></h5>
					<p class="card-text"><?= get_the_excerpt() ?></p>
					<p>Format: <?= get_post_meta(get_the_ID(), 'hera_course_format', true); ?></p>
					<a href="<?= get_the_permalink() ?>" class="btn btn-outline-secondary">Learn More</a>
				</div>
			</div>
		</div>
	</div>
	<?php endwhile; ?>
</div>
