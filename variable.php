<?php
/**
 * Variable product add to cart
 * https://webtrafficgeeks.org/product/order-website-traffic/
 */
if ( ! defined('ABSPATH') ) {
    exit;
}

global $product;

$attribute_keys = array_keys( $attributes );

do_action('woocommerce_before_add_to_cart_form' ); ?>

    <form class="variations_form cart" method="post" enctype='multipart/form-data'
          data-product_id="<?php echo esc_attr( absint( $product->id ) ); ?>"
          data-product_variations="<?php echo htmlspecialchars( json_encode( $available_variations ) ) ?>">
        <?php do_action('woocommerce_before_variations_form'); ?>

        <?php if ( empty( $available_variations ) && false !== $available_variations ) : ?>
            <p class="stock out-of-stock"><?php _e( 'This product is currently out of stock and unavailable.', 'woocommerce' ); ?></p>
        <?php else :
            ?>

            <div class="order-wrap">
                <div class="order-step section-forms-1">
                    <div class="order-step">
                        <div class="choose-traffic-plan">
                            <header>
                                <p class="order-section-title"><?php _e('Choose your traffic plan', 'woocommerce'); ?></p>
                            </header>

                            <?php $attribute_name = 'pa_traffic-plan'; ?>

                            <div class="choose-order-step-section-plans" data-variation="<?php echo esc_attr( $attribute_name ); ?>">

                                <?php $options = $attributes[$attribute_name];
                                if ( ! empty($options) ) :
                                    $selected = isset($_REQUEST['attribute_' . sanitize_title($attribute_name)]) ?
                                        wc_clean($_REQUEST['attribute_' . sanitize_title($attribute_name)]) :
                                        $product->get_variation_default_attribute($attribute_name); ?>

                                    <?php $terms = wc_get_product_terms( $product->id, $attribute_name, array('fields' => 'all') );
                                    foreach ( $terms as $term ):
                                        if ( in_array( $term->slug, $options ) ):

                                            $vars = get_posts( array(
                                                'post_type'   => 'product_variation',
                                                'post_status' => array('publish'),
                                                'numberposts' => 1,
                                                'post_parent' => $product->id,
                                                'meta_query'  => array(
                                                    array(
                                                        'key'     => 'attribute_pa_traffic-plan',
                                                        'value'   => $term->slug,
                                                        'compare' => '=',
                                                    ),
                                                ),
                                            ));

                                            $var = $vars[0]->ID;
                                            $var_price = get_post_meta( $var, '_regular_price', true );
                                            $var_visitors = get_post_meta( $var, '_plan_visitors', true );
                                            $var_niches = get_post_meta( $var, '_plan_niches', true );
                                            $var_sku = get_post_meta( $var, '_sku', true );

                                            printf('<div class="order-step-section-plans widget_custom_html">
							<a href="#" data-value="%s" class="order-step-section %s %s">
								<div class="panel-widget-style">
									<div class="visitors-order">
										<span class="order-step-section-visitors-amount">%s</span>
									</div>
									<div class="order-step-section-price price-order">%s</div>
								</div>
							</a>
						</div>',

                                                $term->slug,
                                                $term->slug,
                                                sanitize_title($selected) == sanitize_title($term->slug) ? "selected" : "",
                                                $var_visitors,
                                                get_woocommerce_currency_symbol() . " " . number_format((float)$var_price, 2, '.', '')
                                            );

                                        endif;
                                    endforeach; ?>

                                    <div class="variations" style="display: none;">
                                        <?php wc_dropdown_variation_attribute_options ( array( 'options' => $options, 'attribute' => $attribute_name, 'product' => $product, 'selected' => $selected ) ); ?>
                                    </div>

                                <?php endif; ?>

                            </div>
                            <p><i class="fa fa-check"></i><?php _e('All plans target up to 3 Niches & Countries', 'woocommerce'); ?></p>
                        </div>
                    </div>

                    <div class="wrap">
                        <section class="order-step-section">
                            <div class="row">

                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <p class="order-section-sub-title"><?php _e('Select currency', 'woocommerce'); ?></p>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="currency-section">
                                        <?php do_action( 'wcml_currency_switcher', array( 'format' => '%code%', 'switcher_style' => 'wcml-horizontal-list') ); ?>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>

                    <div class="wrap">
                        <?php $attribute_name = 'pa_frequency'; ?>

                        <!-- Frequency selection -->
                        <div class="choose-frequency" data-variation="<?php echo esc_attr( $attribute_name ); ?>">

                            <h4 class="order-section-sub-title text-center"><?php _e('Choose payment type', 'woocommerce'); ?>
                                <span class="has-tip rbl-hint tooltip-help" rel="tooltip" title="Choose whether you want to receive visitors each month, recurring or just once">?</span>
                            </h4>

                            <?php $options = $attributes[ $attribute_name ];
                            if ( ! empty( $options ) ) :
                                $selected = isset($_REQUEST['attribute_' . sanitize_title( $attribute_name )]) ?
                                    wc_clean($_REQUEST['attribute_' . sanitize_title( $attribute_name )]) :
                                    $product->get_variation_default_attribute( $attribute_name );
                                $selected2 = $selected;
                                ?>

                                <?php $terms = get_terms( $attribute_name );

                                foreach ( $terms as $term ) { ?>
                                    <div class="frequency-item">
                                        <a href="#" data-value="<?php echo esc_attr( $term->slug ); ?>"
                                           class="frequency-item-section <?php echo esc_attr( stristr($term->slug, $selected) !== false ? 'selected' : '' ); ?>">
                                            <div class="panel-widget-style">
                                                <div class="visitors-order">
                                                    <span class=""><?php echo esc_attr( $term->name ); ?></span>
                                                </div>
                                                <div class="frequency-info">

                                                    <?php
                                                    if ( stristr($term->slug, 'monthly') !== false) { ?>
                                                        <li class="included"><?php _e('Cancel anytime you like', 'woocommerce'); ?></li>
                                                        <li class="included"><?php _e('5% Off', 'woocommerce'); ?></li>
                                                        <?php /*} else if ($term->slug == 'one-time') {*/
                                                    } else if ( stristr($term->slug, 'one-time') !== false) { ?>
                                                        <li class="included"><?php _e('No bonuses :(', 'woocommerce'); ?></li>
                                                        <li class="included freq-off"><?php _e('5% Off', 'woocommerce'); ?></li>
                                                    <?php } ?>

                                                </div>
                                            </div>
                                        </a>
                                    </div>

                                    <?php if ( stristr( $term->slug, $selected ) !== false ) {
                                        $selected2 = $term->slug;
                                    } ?>

                                <?php } ?>

                                <div class="variations" style="display: none;">
                                    <?php wc_dropdown_variation_attribute_options( array('options' => $options, 'attribute' => $attribute_name, 'product' => $product, 'selected' => $selected2) ); ?>
                                </div>

                            <?php endif; ?>
                        </div>


                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <p class="order-sub-title"><?php _e('Website you need the traffic to go', 'woocommerce'); ?>
                                    <span class="has-tip rbl-hint tooltip-help" rel="tooltip" title="Insert the URL you wish to target">?</span>
                                </p>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="order-step-section-block">
                                    <input type="url" name="properties[destination]" placeholder="https://example.com"
                                           value="<?php print $_REQUEST['properties']['destination']; ?>"
                                           class="order-step-input form-control"
                                           id="destination">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <?php

                $target = get_terms("pa_niches", array(
                    'hide_empty' => false,
                    'orderby'    => 'slug',
                    'order'      => 'ASC'
                ));

                $countries = get_terms("pa_countries", array(
                    'hide_empty' => false,
                    'orderby'    => 'slug',
                    'order'      => 'ASC'
                ));

                ob_start(); ?>

                <div class="order-step section-forms-2">
                    <div class="wrap">
                        <section class="order-step-section">
                            <div class="row">
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <p class="order-section-sub-title"><?php _e('Select up to 3 niches', 'woocommerce'); ?>
                                        <span class="has-tip rbl-hint tooltip-help" rel="tooltip"
                                              title="Select up to 3 niches you want to target. If you don't want to target a specific niche you can leave this field open">?</span>
                                    </p>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select name="properties[niches][]" class="chosen-select form-control" id="niches"
                                            tabindex="4" multiple
                                            data-placeholder="<?php esc_attr_e('Click to select niches...', 'woocommerce'); ?>">
                                        <?php foreach ($target as $item): ?>
                                            <option
                                                    value="<?php print trim($item->name); ?>"><?php print $item->name; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <p class="order-section-sub-title"><?php _e('Select up to 3 countries', 'woocommerce'); ?>
                                        <span class="has-tip rbl-hint tooltip-help" rel="tooltip"
                                              title="Select up to 3 countries you want to target. If you don't want to target a specific country and go worldwide, you can leave this field open">?</span>
                                    </p>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select name="properties[countries][]" class="chosen-select form-control"
                                            id="countries"
                                            style="max-width: 100%;" tabindex="4" multiple
                                            data-placeholder="<?php esc_attr_e('Click to select countries...', 'woocommerce'); ?>">

                                        <?php foreach ( $countries as $item ): ?>
                                            <option
                                                    value="<?php print trim( $item->name ); ?>"><?php print $item->name; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <p class="order-section-sub-title"><?php _e('Spread visitors over time', 'woocommerce'); ?>
                                        <span class="has-tip rbl-hint tooltip-help" rel="tooltip" title="Choose between 1 and 30 days">?</span>
                                    </p>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <p class="order-section-sub-title"><?php _e('Start campaign on this date', 'woocommerce'); ?>
                                        <span class="has-tip rbl-hint tooltip-help" rel="tooltip" title="Select the date at which you want to start the campaign">?</span>
                                    </p>
                                </div>

                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="input-group date" id='datepicker-order' data-provide="datepicker">
                                        <input type="text" name="properties[date]"
                                               value="<?php if ( $data['date'] ) {
                                                   echo $data['date'];
                                               } else { ?>""<?php } ?>"
                                        class="form-control">

                                        <div class="input-group-addon">
                                            <span class="glyphicon glyphicon-th"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>


                <div class="order-step section-forms-3">
                    <div class="wrap">
                        <div class="choose-payment-method">
                            <h4 class="order-section-sub-title text-center"><?php _e('Choose payment method', 'woocommerce'); ?></h4>
                            <div class="payment-wrapper">

                                <?php

                                $available_gateways = WC()->payment_gateways->get_available_payment_gateways();

                                $paypal = get_post_meta( $product->id, 'PayPal', true );

                                if ( $paypal && stristr($paypal, "off" ) ) {
                                    unset( $available_gateways[ 'paypal' ] );
                                }

                                $paytabs = get_post_meta( $product->id, 'PayTabs', true );

                                if ( $paytabs && stristr( $paytabs, "off" ) ) {
                                    unset( $available_gateways[ 'paytabs' ] );
                                }

                                ?>
                                <?php

                                if ( ! empty( $available_gateways ) ) {

                                    // Chosen Method
                                    if ( isset(WC()->session->chosen_payment_method ) && isset( $available_gateways[WC()->session->chosen_payment_method]) ) {
                                        $available_gateways[ WC()->session->chosen_payment_method ]->set_current();
                                    } elseif ( isset( $available_gateways[get_option('woocommerce_default_gateway')]) ) {
                                        $available_gateways[ get_option('woocommerce_default_gateway') ]->set_current();
                                    } else {
                                        current( $available_gateways )->set_current();
                                    }


                                    foreach ( $available_gateways as $gateway ) {

                                        if ( in_array($product->id, $nonPPproducts ) && $gateway->id == 'paypal')
                                            ?>
                                            <input id="payment_method_<?php echo esc_attr( $gateway->id ); ?>" type="radio" class="input-radio input-hidden" name="properties[payment_method]"
                                                                                                   value="<?php echo esc_attr($gateway->id); ?>" <?php checked($gateway->chosen, true); ?>
                                                                                                   data-order_button_text="<?php echo esc_attr($gateway->order_button_text); ?>"/>

                                        <label for="payment_method_<?php echo esc_attr( $gateway->id ); ?>" class="panel-widget-style">
                                            <img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/images/payment/' . $gateway->id . '-pm1.jpg' ); ?>"/>
                                        </label>
                                    <?php }
                                } else {
                                    echo '<li class="woocommerce-notice woocommerce-notice--info woocommerce-info">' . apply_filters('woocommerce_no_available_payment_methods_message', WC()->customer->get_billing_country() ? esc_html__('Sorry, it seems that there are no available payment methods for your state. Please contact us if you require assistance or wish to make alternate arrangements.', 'woocommerce') : esc_html__('Please fill in your details above to see available payment methods.', 'woocommerce')) . '</li>'; // @codingStandardsIgnoreLine
                                }
                                ?>                               

                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <?php
            // Custom hook after the plan selection
            //do_action('sd_after_trafficplans')
            ?>


            <?php do_action('woocommerce_before_add_to_cart_button'); ?>

            <div class="price-and-checkout-button">

                <div class="single_variation_wrap">
                    <?php
                    /**
                     * woocommerce_before_single_variation Hook.
                     */
                    do_action('woocommerce_before_single_variation');

                    /**
                     * woocommerce_single_variation hook. Used to output the cart button and placeholder for variation data.
                     * @since 2.4.0
                     * @hooked woocommerce_single_variation - 10 Empty div for variation data.
                     * @hooked woocommerce_single_variation_add_to_cart_button - 20 Qty and cart button.
                     */
                    do_action('woocommerce_single_variation');

                    /**
                     * woocommerce_after_single_variation Hook.
                     */
                    do_action('woocommerce_after_single_variation');
                    ?>
                </div>

            </div>


            <div class="buttons-next-back">
                <a href="#" class="btn btn-order-back"><?php _e('« BACK', 'woocommerce'); ?></a>
                <a href="#" class="btn btn-order-next"><?php _e('NEXT »', 'woocommerce'); ?></a>
            </div>

            <?php do_action('woocommerce_after_add_to_cart_button'); ?>
        <?php endif; ?>

        <?php do_action('woocommerce_after_variations_form'); ?>
    </form>


<?php
do_action('woocommerce_after_add_to_cart_form');

if (is_active_sidebar('order-widget')) {

    dynamic_sidebar('order-widget');

}

if (is_active_sidebar('order2-widget')) {

    dynamic_sidebar('order2-widget');

}

if (is_active_sidebar('order3-widget')) {

    dynamic_sidebar('order3-widget');

}
