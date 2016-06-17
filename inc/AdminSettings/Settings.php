<?php

/**
 * Settings page for admin menu
 *
 * @package OtrsFilter\AdminSettings
 */

namespace S7GreatFilter\AdminSettings;

use S7GreatFilter\Common\Controller;

/**
 * Class Settings
 * @package OtrsFilter\AdminSettings
 */
class Settings extends Controller {

	/**
	 * Set admin menu page for plugin
	 *
	 * @wp-hook admin_menu
	 *
	 * @return void
	 */
	public function init_menu() {
		add_menu_page(
			__( 'Filter settings', 'otrs-filter' ),
			__( 'Filter settings', 'otrs-filter' ),
			'manage_options',
			'otrs-posts-filter-settings',
			array( $this, 'display' ),
			'dashicons-filter',
			81
		);
	}

	/**
	 * Get all pages saved for filtering
	 *
	 * @wp-hook wp_ajax_ot_get_all_pages
	 *
	 * @return void
	 */
	public function get_all_pages() {

		$pages = get_option( 'ot_filter_pages' );

		wp_send_json( $pages );
	}

	/**
	 * Get pages for auto complete functionality on front end
	 *
	 * @wp-hook wp_ajax_ot_get_all_pages_autocomplete
	 *
	 * @return void
	 */
	public function get_pages_from_table_with_params() {

		$term    = $this->get( 'term' );
		$pages   = new \WP_Query( array( 's' => $term, 'post_type' => 'page' ) );
		$results = array();
		if ( $pages->have_posts() ) {
			while ( $pages->have_posts() ) {
				$pages->the_post();
				$results[] = array( 'label' => get_the_title(), 'value' => get_the_ID() );
			}
		} else {
			wp_send_json_error();
		}

		wp_send_json( $results );
	}


	/**
	 * Save new pages to filter plugin options ( Save configuration )
	 *
	 * @wp-hook wp_ajax_ot_save_option_pages
	 *
	 * @return void
	 */
	public function save_option_pages() {

		$data       = (int) $this->get( 'page_id' );
		$page       = new \WP_Query( array( 'page_id' => $data ) );
		$oldOptions = get_option( 'ot_filter_pages' );
		if ( $page->have_posts() ) {
			while ( $page->have_posts() ) {
				$page->the_post();
				$oldOptions[ $data ] = array( 'title' => get_the_title(), 'settings' => array() );
			}
		} else {
			wp_send_json_error();
		}

		if ( update_option( 'ot_filter_pages', $oldOptions ) ) {
			wp_send_json_success();
		} else {
			wp_send_json_error();
		}
	}

	/**
	 * Save general settings to options table
	 *
	 * @return void
	 */
	public function save_general_settings() {

		update_option( $this->get( 'action' ), $this->get( 'otrs-data' ) );
		wp_redirect( admin_url( 'admin.php?page=otrs-posts-filter-settings' ) );

	}

	/**
	 * Save settings for single page
	 *
	 * @return void
	 */
	public function save_page_settings() {

		$data                                        = get_option( 'ot_filter_pages' );
		$data[ $this->get( 'page-id' ) ]['settings'] = $this->get( 'otrs-data' );
		if( $this->get( 'otrs_delete_setting' ) ) {
			unset($data[ $this->get('page-id' ) ]);
		}
		update_option( 'ot_filter_pages', $data );
		wp_redirect( admin_url( 'admin.php?page=otrs-posts-filter-settings&settings_page=pages' ) );
	}

	/**
	 * Display admin page for plugin
	 *
	 * @return void
	 */
	public function display() {

		$general_settings = add_query_arg( 'settings_page', 'general' );
		$pages            = add_query_arg( 'settings_page', 'pages' );
		$post_settings    = get_option( 'otrs_post_settings' );
		$style_settings   = get_option( 'otrs_style_settings' );
		?>
		<h1><span class="dashicons dashicons-filter"></span><?php esc_html_e( 'Filter settings', 'otrs-filter' ); ?>
		</h1>
		<h2 class="nav-tab-wrapper">
			<a href="<?php echo esc_attr( $pages ); ?>"
			   class="nav-tab nav-tab-active"><?php esc_html_e( 'Pages settings',
					'otrs-filter' ); ?></a>
		</h2>
		<?php
			$this->render( 'pages_settings',
				array(
					'pages'                => get_option( 'ot_filter_pages' ),
					'categories'           => get_categories( array( 'hide_empty' => 0 ) ),
					'tags'                 => get_tags( array( 'hide_empty' => 0 ) )
				) );
	}
}
