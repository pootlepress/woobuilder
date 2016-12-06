<?php

/**
 * Pootle Pagebuilder Product Builder public class
 * @property string $token Plugin token
 * @property string $url Plugin root dir url
 * @property string $path Plugin root dir path
 * @property string $version Plugin version
 */
class PPB_Product_Builder_Public{

	/**
	 * @var 	PPB_Product_Builder_Public Instance
	 * @access  private
	 * @since 	1.0.0
	 */
	private static $_instance = null;

	/**
	 * Main Pootle Pagebuilder Product Builder Instance
	 * Ensures only one instance of Storefront_Extension_Boilerplate is loaded or can be loaded.
	 * @since 1.0.0
	 * @return PPB_Product_Builder_Public instance
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
	 * @since   1.0.0
	 */
	private function __construct() {
		$this->token   =   PPB_Product_Builder::$token;
		$this->url     =   PPB_Product_Builder::$url;
		$this->path    =   PPB_Product_Builder::$path;
		$this->version =   PPB_Product_Builder::$version;

		add_shortcode( 'ppb_product_short_description', function() {
			ob_start();
			woocommerce_template_single_excerpt();
			return ob_get_clean();
		} );
		add_shortcode( 'ppb_product_related', function() {
			ob_start();
			woocommerce_related_products();
			return ob_get_clean();
		} );
		add_shortcode( 'ppb_product_add_to_cart', function() {
			ob_start();
			woocommerce_template_single_add_to_cart();
			return ob_get_clean();
		} );
		add_shortcode( 'ppb_product_tabs', function() {
			ob_start();
			woocommerce_output_product_data_tabs();
			return ob_get_clean();
		} );
		add_shortcode( 'ppb_product_reviews', function() {
			ob_start();
			comments_template();
			return ob_get_clean();
		} );

	} // End __construct()

	/**
	 * Adds front end stylesheet and js
	 * @action wp_enqueue_scripts
	 * @since 1.0.0
	 */
	public function wc_get_template_part( $template, $slug, $name ) {
		if (
			'content' == $slug &&
			'single-product' == $name &&
		    PPB_Product_Builder::is_ppb_product()
		) {
			$template = dirname( __FILE__ ) . '/ppb-product-tpl.php';
			add_filter( 'woocommerce_product_tabs', function ( $tabs ) {
				unset( $tabs['description'] );
				return $tabs;
			}, 11 );
		}

		return $template;
	}

	/**
	 * Sets post meta for ppb product
	 * @param array  $page_data
	 * @param int    $post_id
	 * @param string $post_type
	 * @return array
	 */
	public function set_ppb_product_builder_meta( $page_data, $post_id, $post_type ) {
		if ( 'product' == $post_type ) {
			add_post_meta( $post_id, 'ppb-product-builder', 1 );
		}
		return $page_data;
	}

	/**
	 * Prints live editor scripts
	 * @since 1.0.0
	 */
	public function live_editor_scripts() {
		add_action( 'wp_footer', function () {
			?>
			<script>
				jQuery( function ( $ ) {

					ppbProdbuilderSetting = function( $t, val ) {
						$t.find( '.ppb-edit-block .dashicons-edit' ).click();
						$('select[dialog-field="ppb-product-builder"]').val( val );
						jQuery('#pootlepb-content-editor-panel + div button').click()
					}

					window.ppbModules.ppbProd_a2c = function ( $t ) {
						ppbProdbuilderSetting( $t, '[ppb_product_add_to_cart]' );
					};
					window.ppbModules.ppbProd_desc = function ( $t ) {
						ppbProdbuilderSetting( $t, '[ppb_product_short_description]' );
					};
					window.ppbModules.ppbProd_tabs = function ( $t ) {
						ppbProdbuilderSetting( $t, '[ppb_product_tabs]' );
					};
					window.ppbModules.ppbProd_related = function ( $t ) {
						ppbProdbuilderSetting( $t, '[ppb_product_related]' );
					};
					window.ppbModules.ppbProd_reviews = function ( $t ) {
						ppbProdbuilderSetting( $t, '[ppb_product_reviews]' );
					};
				} );
			</script>
			<?php
		} );
	}

	/**
	 * Adds front end stylesheet and js
	 * @action wp_enqueue_scripts
	 * @since 1.0.0
	 */
	public function enqueue() {
		$token = $this->token;
		$url = $this->url;

		wp_enqueue_style( $token . '-css', $url . '/assets/front-end.css' );
		wp_enqueue_script( $token . '-js', $url . '/assets/front-end.js', array( 'jquery' ) );
	}

	/**
	 * Adds or modifies the row attributes
	 * @param array $attr Row html attributes
	 * @param array $settings Row settings
	 * @return array Row html attributes
	 * @filter pootlepb_row_style_attributes
	 * @since 1.0.0
	 */
	public function process_shortcode( $data ) {
		if ( empty( $data['info'] ) || empty( $data['info']['style'] ) ) {
			return;
		}
		$settings = json_decode( $data['info']['style'], 'associative_array' );

		if ( ! empty( $settings[ $this->token ] ) ) {

			if ( $_SERVER['REQUEST_METHOD'] === 'POST' && Pootle_Page_Builder_Live_Editor_Public::is_active() ) {
				add_filter( 'woocommerce_product_tabs', function ( $tabs ) {
					unset( $tabs['description'] );
					return $tabs;
				}, 11 );
				global $post, $product;
				$post = get_post( $_POST['post'] );
				$product = wc_get_product( $post );
			}
			$shortcode = $settings[ $this->token ];
			$code = str_replace( array( '[', ']' ), '', $shortcode ); // Remove square brackets
			$code = explode( $code, ' ' )[0]; // Get shortcode name
			$shortcode = str_replace( '%id%', get_the_ID(), $shortcode );
			?>
			<div id="ppb-product-builder-<?php echo $code ?>" class="ppb-product-builder-module">
			<!--<?php echo $settings[ $this->token ] ?>-->
			<?php echo do_shortcode( $shortcode ); ?>
			</div>
			<?php
		}
	}
}