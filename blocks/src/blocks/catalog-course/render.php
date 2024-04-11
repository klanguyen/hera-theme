<?php
/**
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */

/**
 * @var array $attributes Attributes from the block
 * @var string $content The HTML returned from the save() function
 * @var WP_Block $block All the
 */

if(isset($_POST['reset']) && $_POST['reset'] == 'reset') $_POST = array();

// get search keywords
$searchKey = $_POST['searchKey'] ?? '';
$sortBy = $_POST['sortBy'] ?? 'title-ASC';
$orderBy = explode('-', $sortBy)[0];
$dir = explode('-', $sortBy)[1];
$style = $_POST['course-style'] ?? 'list';

// validate input
$searchKey = strip_tags($searchKey);
$orderBy = strip_tags($orderBy);
$dir = strip_tags($dir);
$style = strip_tags($style);

if($orderBy === 'title') {
	$orderBy = 'post_title';
}

// get all the options of ACF fields
$institutionField = get_field_object('field_65e107c02dd8e');
$topicField = get_field_object('field_65e108d52dd93');
$formatField = get_field_object('field_65e108062dd8f');
$audienceField = get_field_object('field_65e108b82dd92');
$durationField = get_field_object('field_65e108f62dd94');

// get filter inputs
$topicInput = $_POST['course-topic'] ?? '';
$institutionInput = $_POST['course-institution'] ?? '';
$audienceInput = $_POST['course-audience'] ?? '';
$durationInput = $_POST['course-duration'] ?? '';
$formatInput = $_POST['course-format'] ?? '';

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
		<form id="search-tools" method="post">
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
											if( isset($_POST["course-topic"]) &&
												trim($_POST["course-topic"]) == '') {
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
											if( isset($_POST["course-topic"]) &&
												trim($_POST["course-topic"]) == $value) {
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
											if( isset($_POST["course-institution"]) &&
												trim($_POST["course-institution"]) == '') {
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
												if( isset($_POST["course-institution"]) &&
													trim($_POST["course-institution"]) == $value) {
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
												if( isset($_POST["course-audience"]) &&
													trim($_POST["course-audience"]) == $value) {
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
												if( isset($_POST["course-duration"]) &&
													trim($_POST["course-duration"]) == $value) {
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
											if( isset($_POST["course-format"]) &&
												trim($_POST["course-format"]) == $value) {
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
			<button type="submit" class="btn btn-primary mb-3 mr-3">Search</button>
			<button
				name="reset" value="reset"
				class="btn btn-outline-secondary"
			>Reset</button>
			<div class="input-group mb-3">
				<select
					class="form-select"
					id="sortBy"
					name="sortBy"
					onchange="this.form.submit()"
				>

					<option
						<?php
						if( isset($_POST["sortBy"]) &&
							trim($_POST["sortBy"]) == 'title-ASC') {
							echo 'selected';
						}
						?>
						value="title-ASC"
					>Course Name (A-Z)</option>
					<option
						<?php
						if( isset($_POST["sortBy"]) &&
							trim($_POST["sortBy"]) == 'title-DESC') {
							echo 'selected';
						}
						?>
						value="title-DESC"
					>Course Name (Z-A)</option>
					<option
						<?php
						if( isset($_POST["sortBy"]) &&
							trim($_POST["sortBy"]) == 'institution-ASC') {
							echo 'selected';
						}
						?>
						value="institution-ASC"
					>Institution (A-Z)</option>
					<option
						<?php
						if( isset($_POST["sortBy"]) &&
							trim($_POST["sortBy"]) == 'institution-DESC') {
							echo 'selected';
						}
						?>
						value="institution-DESC"
					>Institution (Z-A)</option>
				</select>
				<div class="btn-group">
					<div class="course-style-item">
						<input
							type="radio"
							id="list-style"
							value="list"
							name="course-style"
							onclick="this.form.submit()"
							<?php
							if(isset($_POST['course-style']) && $_POST['course-style'] === 'list') {
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
							value="grid"
							name="course-style"
							onclick="this.form.submit()"
							<?php
							if(isset($_POST['course-style']) && $_POST['course-style'] === 'grid') {
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
		</form>
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
