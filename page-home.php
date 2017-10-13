<?php
/**
 * Template Name: Page Home
 */

get_header(); ?>


<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">

        <div class="slider"> <?php putRevSlider( "homeslider" ) ?></div>

        <?php
        // Start the loop.
        while (have_posts()) : the_post(); ?>
        <?php if (has_post_thumbnail()) { ?>
            <?php $thumb = wp_get_attachment_image_src(get_post_thumbnail_id( $post->ID ), 'full');
            $url = $thumb['0']; ?>
            <div class="banner banner-home" style="background-image: url( <?php echo $url; ?> )"></div>
        <?php } ?>

        <div class="container title-header banner-home">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                    <h2>WELCOME TO</h2>

                    <h3>NDNW</h3>

                    <h1>MOBILE CAR WASH</h1>

                    <div class="aline text-center">
                        <div class="al-1"></div>
                        <div class="al-2"></div>
                        <div class="al-3"></div>
                    </div>

                </div>
            </div>
        </div>

        <div class="how-it-works">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                        <h1>How It Works</h1>
                    </div>
                </div>
                <div class="row">

                    <?php
                    if (have_rows( 'how_it_works')) {
                        while (have_rows( 'how_it_works' )) : the_row();
                            $image = get_sub_field( 'img' );
                            $title = get_sub_field( 'title' );
                            $text = get_sub_field( 'text' ); ?>

                            <div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-6 col-md-offset-0 work-marg">

                                <div class="work_element">
                                    <div class="work_photo">
                                        <img src="<?php echo $image; ?>" alt=""/>
                                    </div>
                                    <div class="work_text">
                                        <h3><?php echo $title; ?></h3>

                                        <p><?php echo $text; ?></p>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        <?php endwhile;
                    } ?>
                </div>
            </div>
        </div>

        <div class="our-services">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                        <h1>Our Services</h1>
                    </div>
                </div>
                <div class="row">
                    <?php
                    $column = 0;
                    if ( have_rows( 'our_services' ) ) {
                        while ( have_rows( 'our_services' ) ) : the_row();
                            $title = get_sub_field( 'title' );
                            $price1 = get_sub_field( 'cars' );
                            $price2 = get_sub_field( 'midsize' );
                            $price3 = get_sub_field( 'suv' );
                            $price4 = get_sub_field( 'truck' );

                            $column++;

                            if ( ($column % 2) ) { ?>
                            <div class="col-xs-12 col-sm-5 col-sm-offset-1 col-md-3 col-md-offset-0">
                                <?php } else { ?>
                                <div class="col-xs-12 col-sm-5 col-md-3">
                                    <?php } ?>
                                    <div class="services-element">
                                        <div class="services-title text-center">
                                            <h3><?php echo $title; ?></h3>
                                        </div>
                                        <div class="services-content">
                                            <div class="serv-cars serv-item serv-border">
                                                <div class="serv-icon text-left">
                                                    <img src="<?php bloginfo( 'template_url' ); ?>/images/cars-white.svg" alt=""/>
                                                    <span>Cars</span>
                                                </div>
                                                <div class="cost text-right">
                                                    <?php echo $price1; ?>
                                                </div>
                                            </div>
                                            <div class="serv-midsize serv-item serv-border">
                                                <div class="serv-icon text-left">
                                                    <img src="<?php bloginfo( 'template_url' ); ?>/images/midsize-white.svg"
                                                         alt=""/>
                                                    <span>Midsize</span>
                                                </div>
                                                <div class="cost text-right">
                                                    <?php echo $price2; ?>
                                                </div>

                                            </div>
                                            <div class="serv-suv serv-item serv-border">
                                                <div class="serv-icon text-left">
                                                    <img src="<?php bloginfo( 'template_url' ); ?>/images/suv-white.svg" alt=""/>
                                                    <span>SUV</span>
                                                </div>
                                                <div class="cost text-right">
                                                    <?php echo $price3; ?>
                                                </div>

                                            </div>
                                            <div class="serv-truck serv-item">
                                                <div class="serv-icon text-left">
                                                    <img src="<?php bloginfo( 'template_url' ); ?>/images/truck-white.svg"
                                                         alt=""/>
                                                    <span>Truck</span>
                                                </div>
                                                <div class="cost text-right">
                                                    <?php echo $price4; ?>
                                                </div>
                                            </div>
                                            <div class="serv-btn text-center">
                                                <a href="#" class="button button_ndnw" data-toggle="modal"
                                                   data-target="#modal-make-an-appointmen">Book Now</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        <?php endwhile;
                        } ?>
                    </div>
                    <div class="row text-center">
                        <div class="col-md-12">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-sm-offset-3 col-md-12 col-md-offset-0 text-center">
                            <div class="services-element-yachts">
                                <div class="services-title-yachts text-center">
                                    <h3>Boats and Yachts</h3>
                                </div>
                                <div class="services-content-yachts">
                                    <div class="serv-icon text-left">
                                        <div class="serv-img text-left"><img
                                                src="<?php bloginfo( 'template_url' ); ?>/images/yacht.svg" alt=""/></div>
                                        <div class="serv-text text-center"><p>Complete washdown of entire yacht above
                                                waterline</p></div>
                                    </div>

                                    <div class="serv-btn-yachts text-center">
                                        <a href="#" class="button button_ndnw" data-toggle="modal"
                                           data-target="#modal-make-an-appointment-boats">Book Now</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="specials">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                            <h1>Specials</h1>
                        </div>
                    </div>
                    <div class="row">

                        <?php
                        if ( have_rows( 'specials' ) ) {
                            while ( have_rows( 'specials' ) ) : the_row();
                                $image = get_sub_field( 'img' );
                                $title = get_sub_field( 'title' );
                                $text = get_sub_field( 'text' ); ?>

                                <div class="col-xs-12 col-sm-4 col-sm-offset-4 col-md-4 col-md-offset-0">
                                    <div class="specials-element">
                                        <div class="specials-photo">
                                            <img src="<?php echo $image; ?>" alt=""/>
                                        </div>
                                        <div class="specials-content text-center">

                                            <h2><?php echo $title; ?></h2>
                                            <p><?php echo $text; ?></p>
                                        </div>
                                    </div>
                                </div>

                            <?php endwhile;
                        }
                        ?>

                    </div>
                </div>
            </div>

            <div class="customers-say">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-7 col-md-offset-0 custom-say">
                            <div class="customers-say-element text-left">
                                <h1>What Our Customers Say</h1>
                            </div>
                            <div class="customers-say-text text-left">
                                <?php
                                $text = "When service which allows us to get to our costumers in the time of need.which allows us to get to our allows us to get to our costumers in the time of need We service service which allows us to get to our costumers in the time of need. We     service available in the industry, each vehicle is treated personally.";
                                ?>

                                <div id="carousel-what-customers-say" class="carousel slide" data-ride="carousel">
                                    <!-- Wrapper for slides -->
                                    <div class="carousel-inner" role="listbox">

                                        <?php
                                        $first = true;
                                        if ( have_rows( 'what_our_customers_say' ) ) {
                                            while ( have_rows( 'what_our_customers_say' ) ) : the_row();
                                                $text = get_sub_field( 'customers_say' );

                                                if ( $first == true ) {
                                                    $first = false;  ?>
                                                    <div class="item active">
                                                        <?php } else { ?>
                                                        <div class="item">
                                                        <?php } ?>
                                                        <?php echo $text; ?>
                                                        <div class="carousel-caption"></div>
                                                    </div>

                                                <?php endwhile;
                                            } ?>
                                        </div>

                                        <!-- Controls -->
                                        <a class="left carousel-control" href="#carousel-what-customers-say"
                                           role="button"
                                           data-slide="prev">

                                            <span class="fa fa-arrow-circle-left" aria-hidden="true"></span>
                                            <span class="sr-only">Previous</span>
                                        </a>
                                        <a class="right carousel-control" href="#carousel-what-customers-say"
                                           role="button"
                                           data-slide="next">
                                            <span class="fa fa-arrow-circle-right aria-hidden=" true"></span>
                                            <span class="sr-only">Next</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="about-us">
                    <div class="container">
                        <div class="row">
                            <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-push-6 col-md-offset-0">
                                <div class="about-us-element text-right">
                                    <h1>About Us</h1>

                                    <div class="about-us_text text-right">
                                        <?php
                                        if ( have_rows( 'about_us ') ) {
                                            while ( have_rows( 'about_us' ) ) : the_row();
                                                $text = get_sub_field( 'text' ); ?>

                                                <p><?php echo $text; ?></p>

                                            <?php endwhile;
                                        }
                                        ?>

                                        <div class="about-us-btn text-right">
                                            <a href="<?php echo get_the_permalink( 38 ); ?>"
                                               class="button button_ndnw button-big">Read More</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-6 col-sm-offset-3 col-md-6 col-md-pull-6 col-md-offset-0">
                                <div class="about-us-element">
                                    <div class="about-photo">
                                        <img src="<?php bloginfo( 'template_url' ); ?>/images/7.jpg" alt=""/>
                                    </div>
                                    <div class="about-us-btn2 text-center">
                                        <a href="<?php echo get_the_permalink( 38 ); ?>"
                                           class="button button_ndnw button-big">Read More</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php endwhile; ?>

                <!-- Modal -->
                <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" id="modal-make-an-appointmen"
                     aria-labelledby="myLargeModalLabel" aria-hidden="true">

                    <div class="modal-dialog modal-lg" role="document">

                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <h2 class="modal-title" id="myModalLabel">Make an Appointment</h2>
                            </div>
                            <div class="modal-body">
                                <?php echo do_shortcode( '[contact-form-7 id="120" title="Make an Appointment"]' ); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog"
                     id="modal-make-an-appointment-boats"
                     aria-labelledby="myLargeModalLabel" aria-hidden="true">

                    <div class="modal-dialog modal-lg" role="document">

                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <h2 class="modal-title" id="myModalLabel1">Make an Appointment</h2>
                            </div>
                            <div class="modal-body">
                                <?php echo do_shortcode( '[contact-form-7 id="169" title="Make an Appointment Boats"]' ); ?>

                            </div>
                        </div>
                    </div>
                </div>

    </main><!-- .site-main -->

    <?php get_sidebar( 'content-bottom' ); ?>

</div><!-- .content-area -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>



