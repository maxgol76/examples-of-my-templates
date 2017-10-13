<?php
/**
 * Template Name: Page Properties
 */


get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">
        <?php
        // Start the loop.
        while ( have_posts() ) : the_post(); ?>
            <?php if ( has_post_thumbnail() ) { ?>
                <?php $thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full');
                $url = $thumb['0']; ?>
                <div class="banner" style="background-image: url( <?php echo $url ?> )"></div>

            <?php } ?>

            <div class="path-page">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <?php the_breadcrumb(); ?>
                        </div>
                    </div>
                </div>
            </div>


            <div class="properties-page">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 text-center">

                            <h1>Properties</h1>

                            <nav class="category_menu">
                                <ul>
                                    <?php
                                    $categories = get_categories( 'child_of=4 & hide_empty=0 & orderby=id' );
                                    $n = 0;
                                    foreach ( $categories as $category ) { ?>

                                        <li>
                                            <a href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>"
                                                <?php if ( $category->slug == 'current-properties' ) {
                                                          echo 'class="active"';
                                            } ?>>
                                                <?php echo esc_html( $category->name ) ?></a>
                                        </li>

                                        <?php if ( $n++ < 3 ) { ?><span>•</span> <?php } ?>

                                    <?php } ?>
                                </ul>
                            </nav>

                        </div>
                    </div>
                </div>
            </div>

            <div class="content-list-post">
                <div class="container">
                    <div class="row padTB30100">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <?php
                            $paged = ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1;
                            $args = array(
                                'paged' => $paged,
                                'post_type' => 'post',
                                'category_name' => 'current-properties',
                                'post_status' => 'publish',
                                'order' => 'DESC'
                            );
                            ?>
                            <?php $loop = new WP_Query( $args ); ?>
                            <?php if ( $loop->have_posts() ) : ?>
                                <div class="row_centered">
                                    <?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-5 col-md-5">
                                                <div class="post_element">
                                                    <div class="pe_img">
                                                        <?php if ( has_post_thumbnail() ) { ?>
                                                            <a href="<?php the_permalink(); ?>">
                                                                <?php the_post_thumbnail('large'); ?>
                                                            </a>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xs-12 col-sm-7 col-md-7">

                                                <div class="post_element">
                                                    <div class="pei_title"><a
                                                            href="<?php the_permalink(); ?>">
                                                            <h2><?php the_title(); ?></h2>
                                                        </a>
                                                    </div>

                                                    <div
                                                        class="square-foot"><?php echo s_get_meta_data( $post->ID, 'size' ); ?></div>
                                                    <div
                                                        class="square-foot"><?php echo s_get_meta_data( $post->ID, 'location' ); ?>
                                                    </div>

                                                    <div class="post_excerpt">
                                                        <?php echo s_get_meta_data( $post->ID, 'description' ); ?>
                                                    </div>

                                                    <div class="pe_info">

                                                        <div><a href="<?php the_permalink(); ?>"
                                                                class="button button_ndnw">Read More
                                                                <i class="fa fa-angle-right"></i> </a></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    <?php endwhile;
                                    wp_reset_query(); ?>
                                </div>
                            <?php endif; ?>
                            <div class="clear"></div>
                            <?php if ( function_exists('wp_corenavi2') ) {
                                      wp_corenavi2();
                               }
                            ?>
                            <div class="clear"></div>
                        </div>

                    </div>
                </div>
            </div>


        <?php endwhile; ?>

    </main><!-- .site-main -->

</div><!-- .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
