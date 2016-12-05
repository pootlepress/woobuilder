<?php
/**
 * Pootle Pagebuilder Product Builder Admin class
 * @property string token Plugin token
 * @property string $url Plugin root dir url
 * @property string $path Plugin root dir path
 * @property string $version Plugin version
 */
class PPB_Product_Builder_Admin{

	/**
	 * @var 	PPB_Product_Builder_Admin Instance
	 * @access  private
	 * @since 	1.0.0
	 */
	private static $_instance = null;

	/**
	 * Main Pootle Pagebuilder Product Builder Instance
	 * Ensures only one instance of Storefront_Extension_Boilerplate is loaded or can be loaded.
	 * @return PPB_Product_Builder_Admin instance
	 * @since 	1.0.0
	 */
	public static function instance() {
		if ( null == self::$_instance ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	} // End instance()

	/**
	 * Constructor function.
	 * @access  private
	 * @since 	1.0.0
	 */
	private function __construct() {
		$this->token   =   PPB_Product_Builder::$token;
		$this->url     =   PPB_Product_Builder::$url;
		$this->path    =   PPB_Product_Builder::$path;
		$this->version =   PPB_Product_Builder::$version;
	} // End __construct()

	/**
	 * Adds editor panel tab
	 * @param array $tabs The array of tabs
	 * @return array Tabs
	 * @filter pootlepb_content_block_tabs
	 * @since 	1.0.0
	 */
	public function content_block_tabs( $tabs ) {
		if ( PPB_Product_Builder::is_ppb_product() ) {
			$tabs[ $this->token ] = array(
				'label' => 'Product Builder',
				'priority' => 5,
			);
		}
		return $tabs;
	}

	/**
	 * Adds content block panel fields
	 * @param array $fields Fields to output in content block panel
	 * @return array Tabs
	 * @filter pootlepb_content_block_fields
	 * @since 	1.0.0
	 */
	public function content_block_fields( $fields ) {
		if ( PPB_Product_Builder::is_ppb_product() ) {
			$fields[ $this->token ] = array(
				'name'     => 'Display',
				'type'     => 'select',
				'priority' => 1,
				'options'  => array(
					''                                => 'Choose...',
					'[ppb_product_add_to_cart]'       => 'Add to Cart',
					'[ppb_product_short_description]' => 'Short Description',
					'[ppb_product_tabs]'              => 'Product tabs',
					'[ppb_product_related]'           => 'Related products',
				),
				'tab'      => $this->token,
			);
		}

		return $fields;
	}

	/**
	 * Adds new slider link to admon bar
	 * @param WP_Admin_Bar $admin_bar
	 */
	function admin_bar_menu( $admin_bar ) {
		$new_live_page_url = admin_url( 'admin-ajax.php' );
		$new_live_page_url = wp_nonce_url( $new_live_page_url, 'ppb-new-live-post', 'ppbLiveEditor' ) . '&action=pootlepb_live_page';

		$admin_bar->add_menu( array(
			'parent' => 'new-content',
			'id'     => 'ppb-new-live-product',
			'title'  => 'Live Product',
			'href'   => $new_live_page_url . '&post_type=product'
		) );

	}
}