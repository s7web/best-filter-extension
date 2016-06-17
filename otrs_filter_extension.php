<?php
/**
 * Plugin Name: Otrs plugin extension
 * Plugin URI: www.example.com
 * Description: Filter plugin for otrs website
 * Version: 0
 * Author: S7design
 * Author URI: http://www.s7designcreative.com/v2/
 * Text Domain: otrs-filter
 *
 * @package OtrsFilter
 */

namespace OtrsFilter;

use OtrsFilter\Autoloader\Autoloader;

/**
 * Hooked with this action:
 *                          - setup()
 */
add_action( 'plugins_loaded', __NAMESPACE__ . '\setup' );


/**
 * Setup of plugin
 *
 * @wp-hook plugins_loaded
 *
 * @return void
 */
function setup() {

	$config = init();
	$plugin = new Plugin( $config );
	$plugin->boot();
}

/**
 * Init of plugin
 *
 * Require autolaoder, set up paths object
 *
 * @return object
 */
function init() {

	require_once 'inc/Autoloader/Autoloader.php';

	$autoloader = new Autoloader( __DIR__ );
	$autoloader->load();

	$plugin_uri = plugin_dir_url( __FILE__ );

	return (object) array(
		'base_path' => __DIR__,
		'js_path'   => $plugin_uri . 'assets/js/',
		'css_path'  => $plugin_uri . 'assets/css/',
		'img_path'  => $plugin_uri . 'assets/img/',
	);
}


/**
 * Get page settings by ID
 *
 * @param int $id Id of page for settings.
 *
 * @return array|false
 */
function get_page_settings_by_id( $id ) {

	$settings = get_option( 'ot_filter_pages' );
	if ( isset( $settings[ $id ] ) ) {
		return $settings[ $id ];
	} else {
		return false;
	}
}

/**
 * Build query args array based on config
 *
 * @param array $settings Settings from database.
 *
 * @return array
 */
function parse_settings( array $settings ) {
	$args = array();
	if ( isset( $settings['settings']['filter'] ) &&  ( 'tags' === $settings['settings']['filter'] || 'categories' === $settings['settings']['filter'] || 'both' === $settings['settings']['filter'] ) ) {
		switch ( $settings['settings']['filter'] ) {
			case 'categories':
				if ( isset( $settings['settings']['categories'] ) ) {
					$categories           = array_map( 'intval', $settings['settings']['categories'] );
					$args['category__in'] = $categories;
				}
				$args['post_type'] = 'post';
				break;
			case 'tags':
				if ( isset( $settings['settings']['tags'] ) ) {
					$tags      = array_map( 'intval', $settings['settings']['tags'] );
					$args['tag__in'] = $tags;
				}
				$args['post_type'] = 'post';
				break;
			case 'ref_categories':
				$args['post_type'] = 'references';
			case 'both':
				if ( isset( $settings['settings']['categories'] ) ) {
					$categories           = array_map( 'intval', $settings['settings']['categories'] );
					$args[0]['category__in'] = $categories;
					$args[0]['post_type'] = 'post';
				}
				if ( isset( $settings['settings']['tags'] ) ) {
					$tags      = array_map( 'intval', $settings['settings']['tags'] );
					$args[1]['tag__in'] = $tags;
					$args[1]['post_type'] = 'post';
				}
				break;
			default:
				break;
		}
	}
	if( isset( $settings['settings']['filter'] ) &&  ( 'ref_tags' === $settings['settings']['filter'] || 'ref_categories' === $settings['settings']['filter'] || 'both_ref' === $settings['settings']['filter'] ) ) {
		switch ($settings['settings']['filter']) {
			case 'ref_categories':
				if (isset($settings['settings']['categories'])) {
					$categories           = array_map('intval', $settings['settings']['categories']);
					$args[ 'tax_query' ] = array(
						array(
							'taxonomy' => 'ref_categories',
							'field'    => 'term_id',
							'terms'    => $categories,
						),
					);
				}
				$args['post_type'] = 'references';
				break;
			case 'ref_tags':
				if (isset($settings['settings']['tags'])) {
					$tags            = array_map('intval', $settings['settings']['tags']);
					$args[ 'tax_query' ] = array(
						array(
							'taxonomy' => 'ref_tags',
							'field'    => 'term_id',
							'terms'    => $tags,
						),
					);
				}
				$args['post_type'] = 'references';
				break;
			case 'both_ref':
				if (isset($settings['settings']['categories'])) {
					$categories           = array_map('intval', $settings['settings']['categories']);
					$args[0][ 'tax_query' ] = array(
						'relation' => 'OR',
						array(
							'taxonomy' => 'ref_categories',
							'field'    => 'term_id',
							'terms'    => $categories,
						),
					);
					$args[0]['post_type']    = 'references';
					$args[0]['posts_per_page'] = -1;
				}
				if (isset($settings['settings']['tags'])) {
					$tags                 = array_map('intval', $settings['settings']['tags']);
					$args[1][ 'tax_query' ] = array(
						'relation' => 'OR',
						array(
							'taxonomy' => 'ref_tags',
							'field'    => 'term_id',
							'terms'    => $tags,
						),
					);
					$args[1]['post_type'] = 'references';
					$args[1]['posts_per_page'] = -1;
				}
				break;
			default:
				break;
		}
	}

	if( isset( $settings['settings']['filter'] ) &&  ( 'portfolio_tags' === $settings['settings']['filter'] || 'portfolio_categories' === $settings['settings']['filter'] || 'both_port' === $settings['settings']['filter'] ) ) {
		switch ($settings['settings']['filter']) {
			case 'portfolio_categories':
				if (isset($settings['settings']['categories'])) {
					$categories           = array_map('intval', $settings['settings']['categories']);
					$args[ 'tax_query' ] = array(
						array(
							'taxonomy' => 'portfolio_categories',
							'field'    => 'term_id',
							'terms'    => $categories,
						),
					);
				}
				$args['post_type'] = 'portfolio';
				break;
			case 'portfolio_tags':
				if (isset($settings['settings']['tags'])) {
					$tags            = array_map('intval', $settings['settings']['tags']);
					$args[ 'tax_query' ] = array(
						array(
							'taxonomy' => 'portfolio_tags',
							'field'    => 'term_id',
							'terms'    => $tags,
						),
					);
				}
				$args['post_type'] = 'portfolio';
				break;
			case 'both_port':
				if (isset($settings['settings']['categories'])) {
					$categories           = array_map('intval', $settings['settings']['categories']);
					$args[0][ 'tax_query' ] = array(
						'relation' => 'OR',
						array(
							'taxonomy' => 'portfolio_categories',
							'field'    => 'term_id',
							'terms'    => $categories,
						),
					);
					$args[0]['post_type']    = 'portfolio';
				}
				if (isset($settings['settings']['tags'])) {
					$tags                 = array_map('intval', $settings['settings']['tags']);
					$args[1][ 'tax_query' ] = array(
						'relation' => 'OR',
						array(
							'taxonomy' => 'portfolio_tags',
							'field'    => 'term_id',
							'terms'    => $tags,
						),
					);
					$args[1]['post_type'] = 'portfolio';
				}
				break;
			default:
				break;
		}
	}

	return $args;
}
