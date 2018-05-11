<?php
/**
Template Name: Donate Page
A Custom Page Template
@package starter
*/

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<?php
			while ( have_posts() ) : the_post();

				get_template_part( 'template-parts/content', 'page' );
            
			endwhile; // End of the loop.
			?>

		</main><!-- #main -->
	</div><!-- #primary -->
<script>
    jQuery(function($){
        $(window).load(function(){
            $( "label#give-gateway-option-stripe" ).html( '<img src="/wp-content/themes/starter/images/cc-logos.png" border="0" />' );
            $( "label#give-gateway-option-paypal" ).html( '<img src="/wp-content/themes/starter/images/paypal-logo.png" border="0" />' );
            
            $( "label.give-tributes-yes" ).html( 'Yes' );
            $( "label.give-tributes-no" ).html( 'No' );
            $('#give-earmark-list, #give-honor-list, #give-honor-text, #give-amsny-alum-program-wrap').hide();
            $('.give-tributes-notification-lists').append( $('#give-ecard-select-wrap') );
            $('#show-earmark').change(function(){
                if($(this).prop("checked")) {
                    $('#give-earmark-list').show();
                } else {
                    $('#give-earmark-list').hide();
                }
            });


        });
        
    });
    jQuery('#is-alum').change(function(){
        if(jQuery(this).prop("checked")) {
            jQuery('#give-amsny-alum-program-wrap').show();
        } else {
            jQuery('#give-amsny-alum-program-wrap').hide();
        }
    });
    
    
    
    
</script>
<?php
get_footer();
