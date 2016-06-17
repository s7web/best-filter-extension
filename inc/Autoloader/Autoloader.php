<?php
/**
 * Autoloader for plugin
 *
 * @package OtrsFilter\Autoloader
 */

namespace OtrsFilter\Autoloader;

/**
 * Class Autoloader
 *
 * @package S7licence\Autoloader
 */
class Autoloader {

	/**
	 * Path
	 * @var string
	 */
	private $dir;

	/**
	 * Set up class properties
	 *
	 * @param string $dir Root dir of project.
	 */
	public function __construct( $dir ) {

		$this->dir = $dir;
	}

	/**
	 * Run autoload
	 *
	 * @return void
	 */
	public function load() {

		spl_autoload_register( array( $this, 'autoload' ) );
	}

	/**
	 * Autoload classes
	 *
	 * @param string $cls Class for autoload.
	 *
	 * @return void
	 */
	public function autoload( $cls ) {

		$cls = ltrim( $cls, '\\' );
		$cls = str_replace( __NAMESPACE__, '', $cls );
		$cls = str_replace( '\\', '/', $cls );
		$cls = explode( '/', $cls );

		if ( isset( $cls[0] ) && 'OtrsFilter' === $cls[0]  ) {
			array_shift( $cls );
		}

		$cls = implode( '/', $cls );

		$path = $this->dir . '/inc/' .
		        $cls . '.php';
		if ( is_readable( $path ) ) {
			require_once( $path );
		}
	}
}
