<?php
/**
 * Monarge.
 *
 * This file adds the front page to the Monarge Theme.
 */

add_action( 'genesis_meta', 'monarge_front_page_genesis_meta' );

function monarge_front_page_genesis_meta()
{
    wp_enqueue_script('front-script', get_stylesheet_directory_uri() . '/js/front-page.js', array('jquery'), CHILD_THEME_VERSION, true);

    // Add front-page body class.
    add_filter('body_class', 'monarge_body_class');

    // Remove breadcrumbs.
    remove_action('genesis_before_loop', 'genesis_do_breadcrumbs');

    // Remove the default Genesis loop.
    remove_action('genesis_loop', 'genesis_do_loop');

    // Add homepage widgets.
    add_action('genesis_loop', 'monarge_front_page_widgets');

    // Force full width content layout.
    add_filter('genesis_site_layout', '__genesis_return_full_width_content');

}


// Define front-page body class.
function monarge_body_class( $classes )
{

    $classes[] = 'front-page page-template page-template-homepage_template';
    return $classes;

}


// Add markup for front page widgets.
function monarge_front_page_widgets()
{
    if ( is_active_sidebar('front-page-1') ) {

        genesis_widget_area('front-page-1', array(
            'before' => '<div class="services-top-section fadeup-effect"><div class="container"><div class="row"><div class="col-md-12 col-sm-12 col-xs-12 text-center">',
            'after' => '</div></div></div></div>',
        ));
    }

    echo '<section id="features" class="section_wrapper" >
            <div class="our-last-themes-section fadeup-effect">
               <div class="container">
                  <div class="row">
                     <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                         <h2>OUR LATEST THEMES</h2>
                     </div>
                     <div class="col-md-12 col-sm-12 col-xs-12 text-center"><div class="wrap">';


    $cat_Id = get_cat_ID('themes' );

    if ( $cat_Id ) {

        $args = array(
            'taxonomy' => 'category',
            'orderby' => 'id',
            'order' => 'ASC',
            'hide_empty' => false,
            'parent' => $cat_Id
        );

        $terms = get_terms( $args ); // get all categories, but you can use any taxonomy

        $terms_ID_array = array();
        foreach ( $terms as $term ) {
            $terms_ID_array[] = $term->term_id; // Add each term's ID to an array
        }
        $terms_ID_string = implode(',', $terms_ID_array); // Create a string with all the IDs, separated by commas

        $args = array(
            'posts_per_page' => 3,
            'post_type' => 'post',
            'cat' => $terms_ID_string,
            'post_status' => 'publish',
            'order' => 'DESC'
        );

        $the_query = new WP_Query($args); // Display 12 posts that belong to the categories in the string

        if ( $the_query->have_posts() ) :

            while ($the_query->have_posts()) : $the_query->the_post();
                $termsArray = get_the_terms($post->ID, "category");  //Get the terms for this particular item
                $termsString = ""; //initialize the string that will contain the terms
                foreach ($termsArray as $term) { // for each term
                    $termsString .= $term->slug . ' '; //create a string that has all the slugs
                }

                echo '<div class="';
                echo $termsString;
                echo ' thumbnail">';
                echo '<a class="theme-info" href="';
                the_permalink();
                echo '"><div class="text"><h3>';
                the_title();
                echo '</h3>';
                the_excerpt();

                echo '<div class="button" >See Details and Pricing</div></div></a>';

                echo '<div class="feature-icon icon-font">';
                if (has_post_thumbnail()) {
                    the_post_thumbnail(array(330, 250));
                } else {
                    echo '<img src="';
                    echo get_stylesheet_directory_uri();
                    echo '/images/default-thumb.jpg" />';
                }
                echo '</div>';

                echo '<div class="caption"><p>';
                the_title();
                echo '</p></div>';
                echo '</div>';
            endwhile;
            wp_reset_query();

        endif;
    }

    echo '</div></div></div>';
    echo '<div class="row"><div class="col-md-12 col-sm-12 col-xs-12 text-center"><a class="button" href="';
    echo get_the_permalink(881);
    echo '">View All Themes</a></div></div></div></div><div class="clear-both"></div>';

    echo '<div class="need-some-help-section fadeup-effect"><div class="container">';

    if (is_active_sidebar('front-page-2')) {

        genesis_widget_area('front-page-2', array(
            'before' => '<div class="row"><div class="col-md-12 col-sm-12 col-xs-12 text-center">',
            'after' => '</div></div>',
        ));
    }

    echo '<div class="row">';

    if ( is_active_sidebar('front-page-3') ) {

        genesis_widget_area('front-page-3', array(
            'before' => '<div class="service_block col-md-4 col-sm-4 col-xs-12 text-center"><div class="thumbnail">',
            'after' => '</div></div>',
        ));
    }

    if ( is_active_sidebar('front-page-4') ) {

        genesis_widget_area('front-page-4', array(
            'before' => '<div class="service_block col-md-4 col-sm-4 col-xs-12 text-center"><div class="thumbnail">',
            'after' => '</div></div>',
        ));
    }

    if ( is_active_sidebar('front-page-5') ) {

        genesis_widget_area('front-page-5', array(
            'before' => '<div class="service_block col-md-4 col-sm-4 col-xs-12 text-center"><div class="thumbnail">',
            'after' => '</div></div>',
        ));
    }

    echo '</div>';

    if ( is_active_sidebar('front-page-2') ) {

        echo '<div class="row"><div class="col-md-12 col-sm-12 col-xs-12 text-center">
                         <a class="button" href="#">Hire us</a></div></div>';
    }

    if ( is_active_sidebar('subscribe') ) {

        genesis_widget_area('subscribe', array(
            'before' => '<div class="row r-subscribe fadeup-effect"><div class="col-md-12 col-sm-12 col-xs-12 text-center">',
            'after' => '</div></div>',
        ));
    }

    echo '</div></div></div></div></section>';
}

// Run the Genesis loop.
genesis();
