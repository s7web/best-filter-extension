<?php
/**
 * Main Controller class
 *
 * @package OtrsFilter\Common
 */

namespace OtrsFilter\Common;

/**
 * Class Controller
 *
 * Used for extending by controllers, provides core functions for controllers
 *
 * @package OtrsFilter\Common
 */
class Controller {

	/**
	 * Configuration paths
	 * @var object
	 */
	protected $config;

	/**
	 * Set up config for Controller
	 *
	 * @param object $config Configuration paths.
	 */
	public function __construct( $config ) {

		$this->config = $config;
	}

	/**
	 * Render view template
	 *
	 * @param string $view View name.
	 * @param array  $data Data to be passed to view.
	 *
	 * @return void
	 */
	protected function render( $view, $data = array() ) {

		extract( $data );
		include $this->config->base_path . '/inc/view/' . $view . '.php';
	}

	/**
	 * Get request variable
	 *
	 * @param string $key Key for value.
	 *
	 * @return string
	 */
	protected function get( $key ) {

		return isset( $_REQUEST[ $key ] ) ? $_REQUEST[ $key ] : false;
	}
}
