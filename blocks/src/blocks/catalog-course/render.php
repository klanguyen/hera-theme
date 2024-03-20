<?php
/**
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */

/**
 * @var array $attributes Attributes from the block
 * @var string $content The HTML returned from the save() function
 * @var WP_Block $block All the
 */

$searchKey = $_GET['searchKey'] ?? '';
$searchKey = strip_tags($searchKey);

if(!(isset($_GET['orderby']) && isset($_GET['order']))) {
	$_GET['orderby'] = 'post_title';
	$_GET['order'] = 'ASC';
}

$query = new WP_Query([
	'post_type' => 'catalog-course',
	's' => $searchKey,
	'search_columns' => ['post_title'],
	'meta_query' => [
		'institution' => [
			'key' => 'hera_course_institution',
			'compare' => 'EXISTS'
		],
	],
	'orderby' => [
		$_GET['orderby'] => $_GET['order']
	]
])

?>
<div <?php echo get_block_wrapper_attributes(); ?>>
	<section class="search-section">
		<h2>Search for courses</h2>
		<form method="get">
			<div class="input-group mb-3">
				<input id="searchKey" value="<?= $searchKey ?>" name="searchKey" type="text" class="form-control" placeholder="e.g. marketing" aria-label="Search keywords" aria-describedby="A search bar for course name">
			</div>
			<button type="submit" class="btn btn-primary btn-sm mb-3">Search</button>
		</form>
	</section>
	<section class="filter-section">
		<div class="input-group mb-3">
			<select
				class="form-select"
				id="sortBy"
				name="sortBy"
				onchange="document.location.href='?searchKey=<?= $searchKey ?>&'+this.options[this.selectedIndex].value;"
			>
				<option
					<?php
					if( isset($_GET["orderby"]) &&
						trim($_GET["orderby"]) == 'post_title' &&
						isset($_GET["order"]) &&
						trim($_GET["order"]) == 'ASC' ) {
						echo 'selected';
					}
					?>
					value="orderby=post_title&order=ASC"
				>Course Name (A-Z)</option>
				<option
					<?php
					if( isset($_GET["orderby"]) &&
						trim($_GET["orderby"]) == 'post_title' &&
						isset($_GET["order"]) &&
						trim($_GET["order"]) == 'DESC' ) {
						echo 'selected';
					}
					?>
					value="orderby=post_title&order=DESC"
				>Course Name (Z-A)</option>
				<option
					<?php
					if( isset($_GET["orderby"]) &&
						trim($_GET["orderby"]) == 'institution' &&
						isset($_GET["order"]) &&
						trim($_GET["order"]) == 'ASC' ) {
						echo 'selected';
					}
					?>
					value="orderby=institution&order=ASC"
				>Institution (A-Z)</option>
				<option
					<?php
					if( isset($_GET["orderby"]) &&
						trim($_GET["orderby"]) == 'institution' &&
						isset($_GET["order"]) &&
						trim($_GET["order"]) == 'DESC' ) {
						echo 'selected';
					}
					?>
					value="orderby=institution&order=DESC"
				>Institution (Z-A)</option>
			</select>
			<div class="btn-group">
				<a href="#" id="list" class="btn btn-outline-info btn-sm active"><i class="fas fa-list-ul"></i></a>
				<a href="#" id="grid" class="btn btn-outline-info btn-sm"><i class="fas fa-th-large"></i></a>
			</div>
		</div>

	</section>
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
