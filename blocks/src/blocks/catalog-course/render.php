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
	$institution = get_field_object('hera_course_institution')['value'];
	$format = ucfirst(get_post_meta(get_the_ID(), 'hera_course_format', true));
	$topics = get_post_meta(get_the_ID(), 'hera_course_topic', true);
	$audience = get_post_meta(get_the_ID(), 'hera_course_audience', true);
	$duration = get_field_object('hera_course_duration')['value'];
	?>
	<div class="card catalog-course mb-3">
		<div class="row no-gutters">
			<div class="col-md-4">
				<div class="course-institution <?= $institution['value'] ?>"></div>
			</div>
			<div class="col-md-8">
				<div class="card-body">
					<h5 class="card-title"><?= get_the_title() ?></h5>
					<p class="card-text"><?= get_the_excerpt() ?></p>
					<p>Format: <?= $format ?></p>
					<a href="#course-modal-<?= get_the_ID() ?>" id="modal-closed" class="btn btn-outline-secondary">Learn More</a>
				</div>
			</div>
		</div>
	</div>
	<div class="course-modal-container" id="course-modal-<?= get_the_ID() ?>">
		<div class="course-modal">
			<div class="course-modal-headings row">
				<div class="course-data col-7">
					<div class="offered-by">
						<p>Offered by</p><span class="course-institution <?= $institution['value'] ?>"></span>
					</div>
					<h1 class="course-title"><?= get_the_title(); ?></h1>
					<div class="course-meta">
						<div class="meta-item">
							<span class="meta-name">Topic</span>
							<?php
							foreach($topics as $topic){
								echo "<p class='topic-item'>$topic</p>";
							}
							?>
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
							<p><?= $duration['label'] ?></p>
						</div>
					</div>
				</div>
				<div class="course-image col-5">
					<img src="<?= get_the_post_thumbnail_url() ?>" alt="<?= get_the_title() ?>" />
				</div>
			</div>
			<div class="course-modal-details">
				<p class="course-description"><?= get_the_content(); ?></p>
			</div>
			<button class="course-modal-btn">
				<a target="_blank" href="<?= get_post_meta(get_the_ID(), 'hera_course_register_link', true) ?>">Register &rarr;</a>
			</button>

			<a href="#modal-closed" class="link-2"></a>

		</div>
	</div>
	<?php endwhile; ?>
</div>
