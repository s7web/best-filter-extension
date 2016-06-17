<?php
get_header();
$page_id = get_the_ID();
$content = get_the_content();

?>
	<div id="content" class="site-content otrs-faq otrs-page">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 ot_filter_page">
					<?php echo $content; ?>
					<div id="ot_filters_display">

					</div>
				</div>
			</div>
		</div>
	</div>
<?php
get_footer();
