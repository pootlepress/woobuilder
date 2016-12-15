<?php
class PPB_Product_Builder_Modules {

	public $class = 'PPB_Product_Builder';

	/** @var PPB_Product_Builder_Modules Instance */
	private static $_instance = null;

	/**
	 * Gets PPB_Product_Builder_Modules instance
	 * @return PPB_Product_Builder_Modules instance
	 * @since 	1.0.0
	 */
	public static function instance() {
		if ( null == self::$_instance ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	} // End instance()

	/**
	 * PootlePB_Meta_Slider constructor.
	 */
	function __construct() {
		if ( class_exists( $this->class ) ) {
			// Adding modules to live editor sidebar
			add_action( 'pootlepb_modules', array( $this, 'module' ), 25 );
		}
	}

	public function module( $mods ) {

		$token = PPB_Product_Builder::$token;

		if ( ! PPB_Product_Builder::is_ppb_product() ) {
			return $mods;
		}

		$mods['ppb-product-add-to-cart']       = array(
			'label'       => 'WC - Add to Cart',
			'icon_class'  => 'dashicons dashicons-cart',
			//'tab'         => "#pootle-$token-tab",
			'callback'    => 'ppbProd_a2c',
			'ActiveClass' => $this->class,
		);
		$mods['ppb-product-short-description'] = array(
			'label'       => 'WC - Short Description',
			'icon_class'  => 'dashicons dashicons-cart',
			//'tab'         => "#pootle-$token-tab",
			'callback'    => 'ppbProd_desc',
			'ActiveClass' => $this->class,
		);
		$mods['ppb-product-tabs']              = array(
			'label'       => 'WC - Product tabs',
			'icon_class'  => 'dashicons dashicons-cart',
			//'tab'         => "#pootle-$token-tab",
			'callback'    => 'ppbProd_tabs',
			'ActiveClass' => $this->class,
		);
		$mods['ppb-product-related']           = array(
			'label'       => 'WC - Related products',
			'icon_class'  => 'dashicons dashicons-cart',
			//'tab'         => "#pootle-$token-tab",
			'callback'    => 'ppbProd_related',
			'ActiveClass' => $this->class,
		);
		$mods['ppb-product-images']           = array(
			'label'       => 'WC - Product images',
			'icon_class'  => 'dashicons dashicons-cart',
			//'tab'         => "#pootle-$token-tab",
			'callback'    => 'ppbProd_images',
			'ActiveClass' => $this->class,
		);
		$mods['ppb-product-reviews']           = array(
			'label'       => 'WC - Product reviews',
			'icon_class'  => 'dashicons dashicons-cart',
			//'tab'         => "#pootle-$token-tab",
			'callback'    => 'ppbProd_reviews',
			'ActiveClass' => $this->class,
		);

		return $mods;
	}
}

PPB_Product_Builder_Modules::instance();