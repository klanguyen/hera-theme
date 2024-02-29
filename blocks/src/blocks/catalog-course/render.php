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
<p <?php echo get_block_wrapper_attributes(); ?>>
	<?php while($query->have_posts()):
	$query->the_post();
	?>
	<a class="catalog-course" href="<?=get_the_permalink()?>">
		<h3><?= get_the_title() ?></h3>
		<?php endwhile; ?>
	</a>
</p>
