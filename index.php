<?php
/**
 * Plugin Name: Rebel Rating Plugin
 * Description:
 * Version:     1.0
 * Author:      Michael Edwards, Lukas Cerny
 * Author URI:  mailto:rebel@rebelinternet.eu
 * Text Domain: rebel-rating
 */

namespace Rebel;

defined( 'ABSPATH' ) or die( 'No direct access allowed' );

define('REBEL_RATING_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . dirname(plugin_basename(__FILE__)));
define('REBEL_RATING_PLUGIN_URL', plugin_dir_url(__FILE__));

require 'functions.php';

class RebelRating {

    private $minVote;
    private $maxVote;
    private $minRating;
    private $maxRating;
    private $minDailyVotes;
    private $maxDailyVotes;

    public function __construct() {
        $this->initValues();
        $this->initFilters();
        $this->initActions();
        $this->initAjax();

        add_action( 'wp', function () {
            if ( ! wp_next_scheduled('rebel/rating/auto_update')) {
                wp_schedule_event(strtotime('now'), 'daily', 'rebel/rating/auto_update');
            }
            if ( wp_next_scheduled('mrk/shop/auto_update_rating')) {
                 wp_clear_scheduled_hook('mrk/shop/auto_update_rating');
            }
        });
    }

    private function initValues() {
        $this->minVote = get_option( 'rebel-rating-min-vote', 1);
        $this->maxVote = get_option( 'rebel-rating-max-vote', 5);

        $this->minRating = get_option( 'rebel-rating-min-rating', 4.5);
        $this->maxRating = get_option( 'rebel-rating-max-rating', 5);

        $this->minDailyVotes = get_option( 'rebel-rating-min-daily-votes', 0);
        $this->maxDailyVotes = get_option( 'rebel-rating-max-daily-votes', 3);
    }

    private function initFilters() {
        add_filter('rebel/rating/vote', array($this, 'getVote'));
        add_filter('rebel/rating/ratings', array($this, 'getRating'));
        add_filter('rebel/rating/daily_votes', array($this, 'getDailyVotes'));

        add_filter('rebel/rating/votes', array($this, 'ratingVotes'), 10, 2);
    }

    private function initActions() {
        add_action('rebel/rating/update', array($this, 'updateVotes'), 10, 2);
        add_action('rebel/rating/auto_update', array($this, 'autoUpdate'));

	    add_action('wp_enqueue_scripts', array($this, 'initScripts'));
	    add_action('admin_enqueue_scripts', array($this, 'initAdminScripts'));
    }

    private function initAjax() {
        // TODO implement ability to vote
        add_action('wp_ajax_rebel/rating/vote', array($this, 'vote'));
        add_action('wp_ajax_nopriv_rebel/rating/vote', array($this, 'vote'));
    }

    //////////////////////////////////////
    //
    // Filters
    //
    //////////////////////////////////////

    public function getVote( $value = false ) {
        $votes = array('min' => $this->minVote, 'max' => $this->maxVote);
        if ( in_array($value, array('min', 'max')) ) {
            return $votes[$value];
        }

        return $votes;
    }

    public function getRating( $value = false ) {
        $ratings = array('min' => $this->minRating, 'max' => $this->maxRating);
        if ( in_array($value, array('min', 'max'))) {
            return $ratings[$value];
        }

        return $ratings;
    }

    public function getDailyVotes( $value = false ) {
        $dailyVotes = array('min' => $this->minDailyVotes, 'max' => $this->maxDailyVotes);
        if ( in_array($value, array('min', 'max'))) {
            return $dailyVotes[$value];
        }

        return $dailyVotes;
    }

    public function ratingVotes( array $votes, $shopId) {
        $avg   = get_post_meta( $shopId, 'rating_votes_avg', true );
        $count = get_post_meta( $shopId, 'rating_votes_count', true );

        if ( is_numeric($avg) && is_numeric($count) ) {
            return compact('avg', 'count');
        }

        return $votes;
    }

    //////////////////////////////////////
    //
    // Actions
    //
    //////////////////////////////////////

    public function updateVotes( array $votes, $shopId) {
        if ( isset( $votes['avg'], $votes['count']) ) {
            $min = $this->minRating;
            $max = $this->maxRating;

            $votes['avg'] = ($votes['avg'] > $min) ? $votes['avg'] : $min;
            $votes['avg'] = ($votes['avg'] < $max) ? $votes['avg'] : $max;

            // Normalize number format (locale stuff).
            $avg   = number_format($votes['avg'], 1, '.', '');
            $count = intval( $votes['count'] );

            update_post_meta( $shopId, 'rating_votes_avg', $avg );
            update_post_meta( $shopId, 'rating_votes_count', $count );
        }
    }

    public function autoUpdate() {
        global $wpdb;

        $types = get_option( 'rebel-rating-types', array('shop') );
        foreach ( $types as $type ) {

            $min = $this->minRating;
            $max = $this->maxRating;

            $avg = sprintf(
                'ROUND((RAND() * (%2$s - %1$s)) + %1$s, 2)',
                number_format($min, 1, '.', ''),
                number_format($max, 1, '.', '')
            );

            $randTpl = 'ROUND((RAND() * (%2$d - %1$d)) + %1$d)';
            $initCount = sprintf($randTpl, 5, 10); // min, max
            $increase = sprintf($randTpl, $this->minDailyVotes, $this->maxDailyVotes);  // min, max

            // Insert votes for new shops.
            $wpdb->query("
            INSERT
            INTO `$wpdb->postmeta` (`post_id`, `meta_key`, `meta_value`)
            SELECT `ID`, 'rating_votes_avg', $avg
            FROM `{$wpdb->prefix}posts`
            WHERE `ID` NOT IN (
                SELECT `post_id`
                FROM `$wpdb->postmeta`
                WHERE `meta_key` = 'rating_votes_avg'
            ) AND `post_type` = '$type'"
            );

            $wpdb->query("
            INSERT
            INTO `$wpdb->postmeta` (`post_id`, `meta_key`, `meta_value`)
            SELECT `ID`, 'rating_votes_count', $initCount
            FROM `{$wpdb->prefix}posts`
            WHERE `ID` NOT IN (
                SELECT `post_id`
                FROM `$wpdb->postmeta`
                WHERE `meta_key` = 'rating_votes_count'
            ) AND `post_type` = '$type'"
            );

            // Update votes.
            $wpdb->query("
            UPDATE `$wpdb->postmeta`
            SET `meta_value` = $avg
            WHERE `meta_key` = 'rating_votes_avg'"
            );

            $wpdb->query("
            UPDATE `$wpdb->postmeta`
            SET `meta_value` = ROUND(`meta_value` + $increase)
            WHERE `meta_key` = 'rating_votes_count'"
            );
        }
    }

	public function initScripts() {
		wp_enqueue_style('rebel-rating-style', REBEL_RATING_PLUGIN_URL . 'css/style.css');

		wp_enqueue_script('rebel-rating-script', REBEL_RATING_PLUGIN_URL . 'js/script.js', array('jquery'));
	}

	public function initAdminScripts() {
        wp_enqueue_style('rebel-rating-admin-select2-style', REBEL_RATING_PLUGIN_URL . 'js/select2/css/select2.min.css');
        wp_enqueue_style('rebel-rating-admin-style', REBEL_RATING_PLUGIN_URL . 'css/admin.css', array('rebel-rating-admin-select2-style'));

        wp_enqueue_script('rebel-rating-admin-select2-script', REBEL_RATING_PLUGIN_URL . 'js/select2/js/select2.min.js', array('jquery'));
        wp_enqueue_script('rebel-rating-admin-script', REBEL_RATING_PLUGIN_URL . 'js/admin.js', array('rebel-rating-admin-select2-script'));
    }

    //////////////////////////////////////
    //
    // Ajax
    //
    //////////////////////////////////////

    public function vote() {
	    $cookie = unserialize(filter_input( INPUT_COOKIE, 'rating_vote' ));

        $shopId  = filter_input(INPUT_POST, 'shop_id');
        $rating  = filter_input(INPUT_POST, 'rating');
        $referer = filter_input(INPUT_SERVER, 'HTTP_REFERER');
        $path    = parse_url($referer, PHP_URL_PATH);

	    if ( is_array($cookie) && array_key_exists($shopId)) {
		    // User already voted.
		    return wp_send_json_error();
	    }

	    if ( empty( $cookie) || !is_array($cookie) ) {
		    $cookie = array($shopId => $rating);
        } else {
		    $cookie[$shopId] = $rating;
	    }

        if ( ! in_array($rating, range(1, 5))) {
            // Rating out of range.
            return wp_send_json_error();
        }

        if ( ! in_array(get_post_type($shopId), get_option('rebel-rating-types', array('shop')))) {
            // Not a shop.
            return wp_send_json_error();
        }

        $votes = apply_filters(
            'rebel/rating/votes',
            array('count' => 1, 'avg'=> $rating),
            $shopId
        );

        // Update votes.
        $count = $votes['count'];
        $avg   = $votes['avg'];

        $votes['count'] = $count + 1;
        $votes['avg']   = round((($avg * $count) + $rating) / $votes['count'], 1);

        do_action('rebel/rating/update', $votes, $shopId);

        setcookie('rating_vote', serialize($cookie), strtotime('+30 days'), $path);

        wp_send_json_success();
    }
}

new RebelRating();