<?php
/**
 * Setting for individual pages template
 *
 * @package OtrsFilter
 */

?>
<div class="wrap">
	<div id="poststuff">

		<div id="post-body" class="metabox-holder columns-2">

			<!-- main content -->
			<div id="post-body-content">

				<div class="meta-box-sortables ui-sortable">

					<div class="postbox">

						<div class="handlediv" title="Click to toggle"><br></div>
						<!-- Toggle -->

						<h3 class="hndle"><span><span class="dashicons dashicons-admin-settings"></span> <?php esc_attr_e( 'Pages settings', 'otrs-filter' ); ?></span>
						</h3>

						<div class="inside">
								<p>
									<label for="ot_add_new_page"> <?php esc_html_e( 'Specify page for filter:', 'otrs-filter' ) ?></label>
									<input type="text" name="ot_add_new_page" id="ot_add_new_page">
									<button class="button button-primary" id="ot_add_page_button"> <?php esc_html_e( 'Add page', 'otrs-filter' ); ?></button>
									<span class="description" id="ot_pages_validation" style="color: red;">
										<!-- javascript validation message -->
									</span><br>
								</p>
							<div id="ot_pages_list_container">

								<?php if ( $pages ) : ?>
									<?php foreach ( $pages as $id => $page ) : ?>
										<h3>
											<?php esc_html_e( $page['title'] ); ?>
										</h3>
										<form action="<?php esc_url( admin_url() ); ?>admin-post.php" method="post">
											<h4>Filter by:</h4>
											<input type="radio" <?php if ( $page[ 'settings' ] && $page[ 'settings' ][ 'filter' ] === 'tags' ) echo 'checked'; ?> value="tags" name="otrs-data[filter]"> Tags
											<br>
											<input type="radio" <?php if ( $page[ 'settings' ] && $page[ 'settings' ][ 'filter' ] === 'categories' ) echo 'checked'; ?> value="categories" name="otrs-data[filter]"> Categories
											<br>
											<input type="radio" <?php if ( $page[ 'settings' ] && $page[ 'settings' ][ 'filter' ] === 'both' ) echo 'checked'; ?> value="both" name="otrs-data[filter]"> Both categories and tags
											<br>
											<input type="radio" <?php if ( $page[ 'settings' ] && $page[ 'settings' ][ 'filter' ] === 'none' ) echo 'checked'; ?> value="none" name="otrs-data[filter]"> None
											<br>
											<h4><?php esc_html_e( 'Maximum posts per this page:', 'otrs-filter' ) ?></h4>
											<div class="wppf-fold">
												<input type="text" value="<?php if ( $page[ 'settings' ] ) echo $page[ 'settings' ][ 'per_page' ]; ?>" class="4_posts_per_page" size="3" name="otrs-data[per_page]">
											</div>
											<h4><?php esc_html_e( 'Show pages containing tags:', 'otrs-filter' ); ?></h4>
											<div class="wppf-fold">
												<fieldset>
													<?php if ( $tags ) : ?>
														<?php foreach ( $tags as $tag ) : ?>
															<input type="checkbox" <?php if ( $page[ 'settings' ] && $page[ 'settings' ][ 'tags' ] && in_array( $tag->term_id, $page[ 'settings' ][ 'tags' ]) ) echo 'checked'; ?> value="<?php echo esc_attr($tag->term_id); ?>" name="otrs-data[tags][]"> <?php echo esc_html( $tag->name ); ?><br>
														<?php endforeach; ?>
													<?php else: ?>
														<?php esc_html_e( 'No tags.', 'otrs-filter' ); ?>
													<?php endif; ?>
												</fieldset>
											</div>
											<h4><?php esc_html_e( 'Show pages from categories:', 'otrs-filter' ); ?></h4>
											<div class="wppf-fold">
												<fieldset>
													<?php if ( $categories ) : ?>
														<?php foreach ( $categories as $category ) : ?>
															<input type="checkbox" <?php if ( $page[ 'settings' ] && $page[ 'settings' ][ 'categories' ] && in_array( $category->term_id, $page[ 'settings' ][ 'categories' ]) ) echo 'checked'; ?> value="<?php echo esc_attr($category->term_id); ?>" name="otrs-data[categories][]"> <?php echo esc_html($category->name); ?><br>
														<?php endforeach; ?>
													<?php else : ?>
														<?php esc_html_e( 'No categories.', 'otrs-filter' ); ?>
													<?php endif; ?>
												</fieldset>
											</div>
											<h4><?php esc_html_e( 'Post list template:', 'otrs-filter' ); ?></h4>
											<div class="wppf-fold">
												<select value="default" size="1" class="4_template wppf-controls" id="wppf_opts[4][template]" name="otrs-data[template]">
													<option <?php if ( $page[ 'settings' ] && $page[ 'settings' ][ 'template' ] == 'default-thumb-enabled' ) echo 'selected'; ?> value="default-thumb-enabled">default-thumb-enabled</option>
													<option <?php if ( $page[ 'settings' ] && $page[ 'settings' ][ 'template' ] == 'default' ) echo 'selected'; ?> value="default">default</option>
												</select>
											</div><h4><?php esc_html_e( 'Date/time settings for the page:', 'otrs-filter' ); ?></h4>
											<div class="wppf-fold">
												<input type="text" value="<?php if ( $page[ 'settings' ] ) echo $page[ 'settings' ][ 'dateformat' ]; ?>" class="4_dateformat wppf-controls" id="wppf_opts[4][dateformat]" name="otrs-data[dateformat]">
											</div><h4><?php esc_html_e( 'Heading tag for the posts on this page:', 'otrs-filter' ); ?></h4>
											<div class="wppf-fold">
												<input type="text" value="<?php if ( $page[ 'settings' ] ) echo $page[ 'settings' ][ 'heading' ]; ?>" class="4_heading_tag wppf-controls" name="otrs-data[heading]">
											</div>
											<h4><?php esc_html_e( 'Heading class for the posts on this page:', 'otrs-filter' ); ?></h4>
											<div class="wppf-fold">
												<input type="text" value="<?php if ( $page[ 'settings' ] ) echo $page[ 'settings' ][ 'heading_class' ]; ?>" class="4_heading_class wppf-controls" name="otrs-data[heading_class]">
											</div>

											<h4><?php esc_html_e( 'Category headline:', 'otrs-filter' ); ?></h4>
											<div class="wppf-fold">
												<input type="text" value="<?php if ( $page[ 'settings' ] ) echo $page[ 'settings' ][ 'category_headline' ]; ?>" class="4_heading_class wppf-controls" name="otrs-data[category_headline]">
											</div>
											<h4><?php esc_html_e( 'Tag headline:', 'otrs-filter' ); ?></h4>
											<div class="wppf-fold">
												<input type="text" value="<?php if ( $page[ 'settings' ] ) echo $page[ 'settings' ][ 'tag_headline' ]; ?>" class="4_heading_class wppf-controls" name="otrs-data[tag_headline]">
											</div>
											<h4><?php esc_html_e( 'Used filter headline:', 'otrs-filter' ); ?></h4>
											<div class="wppf-fold">
												<input type="text" value="<?php if ( $page[ 'settings' ] ) echo $page[ 'settings' ][ 'used_headline' ]; ?>" class="4_heading_class wppf-controls" name="otrs-data[used_headline]">
											</div>
											<h4><?php esc_html_e( 'Delete this setting page?', 'otrs-filter' ); ?></h4>
											<div class="wppf-fold">
												<input type="checkbox" class="4_heading_class wppf-controls" name="otrs_delete_setting">
											</div>
											<br>
											<input type="hidden" name="action" value="otrs_save_page_settings">
											<input type="hidden" name="page-id" value="<?php echo esc_attr( $id ); ?>">
											<input type="submit" class="button button-primary" value="Save">
										</form>
									<?php endforeach; ?>
								<?php endif;?>
							</div>

						</div>
						<!-- .inside -->

					</div>
					<!-- .postbox -->

				</div>
				<!-- .meta-box-sortables .ui-sortable -->

			</div>
			<!-- post-body-content -->

			<!-- sidebar -->
			<div id="postbox-container-1" class="postbox-container">

				<div class="meta-box-sortables">

					<div class="postbox">

						<div class="handlediv" title="Click to toggle"><br></div>
						<!-- Toggle -->

						<h3 class="hndle"><span><span class="dashicons dashicons-sos">

								</span>
								<?php
								esc_attr_e(
									'Pages settings',
									'otrs-filter'
								); ?>
							</span>
						</h3>

						<div class="inside">
							<p>
								<?php
								esc_html_e(
									'Set up pages on which will be shown filter for posts, also every page has her
									own settings, like number of displayed posts, tags, styls and etc',
									'otrs-filter'
								);
								?>
							</p>
						</div>
						<!-- .inside -->

					</div>
					<!-- .postbox -->

				</div>
				<!-- .meta-box-sortables -->

			</div>
			<!-- #postbox-container-1 .postbox-container -->

		</div>
		<!-- #post-body .metabox-holder .columns-2 -->

		<br class="clear">
	</div>
	<!-- #poststuff -->

</div> <!-- .wrap -->
