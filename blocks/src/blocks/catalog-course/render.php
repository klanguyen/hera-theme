<?php
/**
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */

/**
 * @var array $attributes Attributes from the block
 * @var string $content The HTML returned from the save() function
 * @var WP_Block $block All the
 */

// get search keywords
$searchKey = $_GET['searchKey'] ?? '';
$orderBy = $_GET['orderby'] ?? 'post_title';
$dir = $_GET['order'] ?? 'ASC';
$style = $_GET['style'] ?? 'list';

// validate input
$searchKey = strip_tags($searchKey);
$orderBy = strip_tags($orderBy);
$dir = strip_tags($dir);
$style = strip_tags($style);

if($_GET['orderby'] === 'title') {
	$orderBy = 'post_title';
}

// get all the options of ACF fields
$institutionField = get_field_object('field_65e107c02dd8e');
$topicField = get_field_object('field_65e108d52dd93');
$formatField = get_field_object('field_65e108062dd8f');
$audienceField = get_field_object('field_65e108b82dd92');
$durationField = get_field_object('field_65e108f62dd94');

// get filter inputs
$topicInput = $_GET['course-topic'] ?? '';
$institutionInput = $_GET['course-institution'] ?? '';
$audienceInput = $_GET['course-audience'] ?? '';
$durationInput = $_GET['course-duration'] ?? '';
$formatInput = $_GET['course-format'] ?? '';

if($topicInput === '') {
	$topicMeta = '';
} else {
	$topicMeta = array(
		'key' => 'hera_course_topic',
		'value' => $topicInput,
		'compare' => 'LIKE'
	);
}

if($institutionInput === '') {
	$institutionMeta = '';
} else {
	$institutionMeta = array(
		'key' => 'hera_course_institution',
		'value' => $institutionInput,
		'compare' => '='
	);
}

if($audienceInput === '') {
	$audienceMeta = '';
} else {
	$audienceMeta = array(
		'key' => 'hera_course_audience',
		'value' => $audienceInput,
		'compare' => '='
	);
}

if($durationInput === '') {
	$durationMeta = '';
} else {
	$durationMeta = array(
		'key' => 'hera_course_duration',
		'value' => $durationInput,
		'compare' => '='
	);
}

if($formatInput === '') {
	$formatMeta = '';
} else {
	$formatMeta = array(
		'key' => 'hera_course_format',
		'value' => $formatInput,
		'compare' => '='
	);
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
		array(
			'relation' => 'AND',
			$topicMeta,
			$institutionMeta,
			$audienceMeta,
			$durationMeta,
			$formatMeta
		)
	],
	'orderby' => [
		$orderBy => $dir
	]
]);
?>
<div <?php echo get_block_wrapper_attributes(); ?>>
	<section class="search-section">
		<h2>Search for courses</h2>
		<form method="get">
			<div class="input-group mb-3">
				<input id="searchKey" value="<?= $searchKey ?>" name="searchKey" type="text" class="form-control" placeholder="e.g. marketing" aria-label="Search keywords" aria-describedby="A search bar for course name">
			</div>

			<!-- advanced search -->
			<div class="accordion mb-3" id="filterAccordion">
				<div class="accordion-item">
					<h2 class="accordion-header">
						<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFilters" aria-expanded="true" aria-controls="collapseFilters">
							Advanced Search
						</button>
					</h2>
					<div id="collapseFilters" class="accordion-collapse collapse" data-bs-parent="#filterAccordion">
						<div class="accordion-body">
							<div class="row">
								<div class="col-md-6 mb-3">
									<label for="course-topic">Topic</label>
									<select class="form-select" name="course-topic" id="course-topic">
										<option
											value=""
											<?php
											if( isset($_GET["course-topic"]) &&
												trim($_GET["course-topic"]) == '') {
												echo 'selected';
											}
											?>
										>
											Any
										</option>
										<?php
										foreach($topicField['choices'] as $value => $label):
										?>
										<option
											value="<?= $value ?>"
											<?php
											if( isset($_GET["course-topic"]) &&
												trim($_GET["course-topic"]) == $value) {
												echo 'selected';
											}
											?>
										>
											<?= $label ?>
										</option>
										<?php endforeach; ?>
									</select>
								</div>
								<div class="col-md-6 mb-3">
									<label for="course-institution">Institution</label>
									<select class="form-select" name="course-institution" id="course-institution">
										<option
											value=""
											<?php
											if( isset($_GET["course-institution"]) &&
												trim($_GET["course-institution"]) == '') {
												echo 'selected';
											}
											?>
										>Any</option>
										<?php
										foreach($institutionField['choices'] as $value => $label):
											?>
											<option
												value="<?= $value ?>"
												<?php
												if( isset($_GET["course-institution"]) &&
													trim($_GET["course-institution"]) == $value) {
													echo 'selected';
												}
												?>
											><?= $label ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6 mb-3">
									<label>Audience</label>
									<?php
									foreach($audienceField['choices'] as $value => $label):
										?>
										<div>
											<input
												type="checkbox"
												id="<?= $value ?>"
												value="<?= $value ?>"
												name="course-audience"
												<?php
												if( isset($_GET["course-audience"]) &&
													trim($_GET["course-audience"]) == $value) {
													echo 'checked';
												}
												?>
											/>
											<label for="<?= $value ?>"><?= $label ?></label>
										</div>
									<?php endforeach; ?>
								</div>
								<div class="col-md-6 mb-3">
									<label>Duration</label>
									<?php
									foreach($durationField['choices'] as $value => $label):
										?>
										<div>
											<input
												type="checkbox"
												id="<?= $value ?>"
												value="<?= $value ?>"
												name="course-duration"
												<?php
												if( isset($_GET["course-duration"]) &&
													trim($_GET["course-duration"]) == $value) {
													echo 'checked';
												}
												?>
											/>
											<label for="<?= $value ?>"><?= $label ?></label>
										</div>
									<?php endforeach; ?>
								</div>
							</div>
							<div class="row">
								<label>Format</label>
								<?php
								foreach($formatField['choices'] as $value => $label):
									?>
									<div class="course-format-item">
										<input
											type="radio"
											id="<?= $value ?>"
											value="<?= $value ?>"
											name="course-format"
											<?php
											if( isset($_GET["course-format"]) &&
												trim($_GET["course-format"]) == $value) {
												echo 'checked';
											}
											?>
										/>
										<div>
										  <span class="format-option text-center">
											  <span class="icon <?= $value ?>"></span><br/>
											  <?= $label ?>
										  </span>
										</div>
									</div>
								<?php endforeach; ?>
							</div>
						</div>
					</div>
				</div>
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
				onchange="document.location.href=this.options[this.selectedIndex].value;"
			>
				<option
					<?php
					if( isset($_GET["orderby"]) &&
						trim($_GET["orderby"]) == 'title' &&
						isset($_GET["order"]) &&
						trim($_GET["order"]) == 'ASC' ) {
						echo 'selected';
					}
					?>
					value="?searchKey=<?= $searchKey ?>&orderby=title&order=ASC&style=<?= $style ?>"
				>Course Name (A-Z)</option>
				<option
					<?php
					if( isset($_GET["orderby"]) &&
						trim($_GET["orderby"]) == 'title' &&
						isset($_GET["order"]) &&
						trim($_GET["order"]) == 'DESC' ) {
						echo 'selected';
					}
					?>
					value="?searchKey=<?= $searchKey ?>&orderby=title&order=DESC&style=<?= $style ?>"
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
					value="?searchKey=<?= $searchKey ?>&orderby=institution&order=ASC&style=<?= $style ?>"
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
					value="?searchKey=<?= $searchKey ?>&orderby=institution&order=DESC&style=<?= $style ?>"
				>Institution (Z-A)</option>
			</select>
			<div class="btn-group">
				<div class="course-style-item">
					<input
						type="radio"
						id="list-style"
						value="?searchKey=<?= $searchKey ?>&orderby=<?= $_GET['orderby'] ?? 'title' ?>&order=<?= $_GET['order'] ?? 'ASC' ?>&style=list"
						name="course-style"
						onclick="document.location.href=this.value"
						<?php
						if(isset($_GET['style']) && $_GET['style'] === 'list') {
							echo 'checked';
						}
						?>
					/>
					<div>
					  <span class="style-option text-center">
						  <span class="icon list"></span>
					  </span>
					</div>
				</div>
				<div class="course-style-item">
					<input
						type="radio"
						id="grid-style"
						value="?searchKey=<?= $searchKey ?>&orderby=<?= $_GET['orderby'] ?? 'title' ?>&order=<?= $_GET['order'] ?? 'ASC' ?>&style=grid"
						name="course-style"
						onclick="document.location.href=this.value"
						<?php
						if(isset($_GET['style']) && $_GET['style'] === 'grid') {
							echo 'checked';
						}
						?>
					/>
					<div>
					  <span class="style-option text-center">
						  <span class="icon grid"></span>
					  </span>
					</div>
				</div>
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
	<div class="card catalog-course mb-3 <?= $style ?>" id="course-list">
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
