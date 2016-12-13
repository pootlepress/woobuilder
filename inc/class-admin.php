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
	 * Adds admin only actions
	 * @action admin_init
	 */
	public function admin_init() {
		add_filter( 'pootlepb_builder_post_types', array( $this, 'remove_ppb_product' ), 99 );
	}

	public function enqueue() {
		global $post;

		if ( $post->post_type == 'product' ) {
			wp_enqueue_script(  $this->token, "$this->url/assets/edit-product.js", array( 'jquery' ) );

			$nonce_url =
				wp_nonce_url( get_the_permalink( $post->ID ), 'ppb-live-edit-nonce', 'ppbLiveEditor' ) .
				'&ppb-product-builder-nonce=' . wp_create_nonce( 'enable_ppb_product_builder' );

			wp_localize_script(  $this->token, 'wcProductBuilderLiveEditLink', $nonce_url );

			echo <<<HTML
<style>
	a.button.pootle {
		margin: .5em 0 .25em .5em;
	}
	button.wp-switch-editor {
		padding: .5em .7em;
	}
</style>
HTML;

		}
	}

	/**
	 * Removes product from ppb supported posts on admin end.
	 * @param $post_types Post types
	 * @return array Post types
	 */
	public function remove_ppb_product( $post_types ) {
		$post_types = array_unique( $post_types );
		unset( $post_types[ array_search( 'product', $post_types ) ] );

		return $post_types;
	}

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
					'[ppb_product_reviews]'           => 'Product reviews',
					'[ppb_product_related]'           => 'Related products',
				),
				'tab'      => $this->token,
			);
		}

		return $fields;
	}
}