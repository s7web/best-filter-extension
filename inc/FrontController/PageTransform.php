<?php
/**
 * Filter posts and pages in main query
 *
 * @package OtrsFilter\FrontController
 */

namespace OtrsFilter\FrontController;

use OtrsFilter\Common\Controller;

/**
 * Class PageTransform
 * @package OtrsFilter\FrontController
 */
class PageTransform extends Controller {

	/**
	 * Change template if is page from settings
	 *
	 * @param string $template Current template.
	 *
	 * @return string
	 */
	public function template_redirect( $template ) {
		$current_post_id = get_the_ID();
		$settings        = \OtrsFilter\get_page_settings_by_id( $current_post_id );
		if ( $settings ) {
			$path_template = 'filter_template.php';
			$new_template  = locate_template( array( $path_template ) );
			if ( '' !== $new_template ) {
				return $new_template;
			} else {
				return $this->config->base_path . '/inc/templates/filter_template.php';
			}
		}

		return $template;
	}

	public function provide_data() {

		$page_id  = (int) $this->get( 'page_id' );
		$settings = \OtrsFilter\get_page_settings_by_id( $page_id );
		$args    = array();
		$params = $this->get( 'params' );
		if ( false !== $params && (isset($params['categories']) || isset($params['tags'])) ) {

			if('both' === $settings['settings']['filter'] || 'categories' === $settings['settings']['filter'] || 'tags' === $settings['settings']['filter']) {
				if (isset($params['categories'])) {
					$args['category__in'] = array_map(function ($cat) {
						return get_cat_ID($cat);
					},
						$params['categories']);
				}
				if (isset($params['tags'])) {
					$args['tag__in'] = array_map(function ($tag) {
						$tag_obj = get_term_by('name', $tag, 'post_tag');

						return $tag_obj->term_id;
					},
						$params['tags']);
				}
				$args['post_type'] = 'post';
			}

		} else {
			$args         = \OtrsFilter\parse_settings( $settings );
			if(isset($args[0]) && isset($args[1])){
				$post_type = $args[0]['post_type'];
				$query1 = new \WP_Query($args[0]);
				$query2 = new \WP_Query($args[1]);
				$ids_of_queries = array_merge( array_map( function( $post ) { return $post->ID; }, $query1->posts ), array_map( function( $post ) { return $post->ID; }, $query2->posts ) );
				$args = array('post__in' => $ids_of_queries);
				$args['post_type'] = $post_type;
			}

		}
		$current_page = (isset($params['current_page'])) ? $params['current_page'] : 1;
		$per_page = (isset($settings['settings']['per_page'])) ? (int) $settings['settings']['per_page'] : 10;
		$args['offset'] = ($current_page - 1) * $per_page;
		$args['posts_per_page'] = $per_page;
		$args['suppress_filters'] = 0;
		$query  = new \WP_Query( $args );
		$output = array();
		$output['selected'] = array();
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$output['selected'][] = array(
					'post_id'   => get_the_ID(),
					'post_name' => get_the_title(),
					'excerpt'   => get_the_excerpt(),
					'thumbnail' => get_the_post_thumbnail(),
					'link'      => ('both_ref' === $settings['settings']['filter'] || 'ref_tags' === $settings['settings']['filter'] || 'ref_categories' === $settings['settings']['filter'])? get_post_meta(get_the_ID(), 'otrs_reference_url', true) : get_permalink()
				);
				$output['settings'] = $settings['settings'];
			}
		}
		$output['categories'] = ( isset( $settings['settings']['filter'] ) && ( 'categories' === $settings['settings']['filter'] || 'both' === $settings['settings']['filter'] ) && isset( $settings['settings']['categories'] ) ) ? array_map( function (
			$cat
		) use ($settings) {
			$filter = $settings['settings']['filter'];
			if( 'both' === $settings['settings']['filter'] || 'categories' === $settings['settings']['filter'] ) {
				$filter = 'category';
			}
			$term = get_term_by('id', (int) $cat, $filter );

			return $term->name;
		},
			$settings['settings']['categories'] ) : null;
		$output['tags']       = ( isset( $settings['settings']['filter'] ) && ( 'tags' === $settings['settings']['filter'] || 'both' === $settings['settings']['filter'] || 'both_ref' === $settings['settings']['filter'] ) && isset( $settings['settings']['tags'] ) ) ? array_map( function (
			$tag
		) use ($settings) {
			$filter = $settings['settings']['filter'];
			if( 'both' === $settings['settings']['filter'] || 'tags' === $settings['settings']['filter'] ) {
				$filter = 'post_tag';
			}
			$term = get_term_by('id', (int) $tag, $filter );

			return $term->name;
		},
			$settings['settings']['tags'] ) : null;
		wp_send_json_success( $output );
	}
}
