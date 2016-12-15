<?php
// Remove ppb content rendering to avoid recursion
remove_filter( 'the_content', array( $GLOBALS['Pootle_Page_Builder_Render_Layout'], 'content_filter' ) );
?>
<div class="product">
	<?php echo Pootle_Page_Builder_Render_Layout::render( get_the_ID() ); ?>
</div>
