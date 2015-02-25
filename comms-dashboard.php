<?php
/**
 * Plugin Name: WP-MU Comments Dashboard Widget
 * Plugin URI: https://github.com/lafent/comms-dashboard
 * Description: Helps administrators locate which networked sites have comments in need of moderation.
 * Version: 0.0.3
 * Author: Lee Fent
 * Author URI: http://lafent.github.io/
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * 
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
  die;
}

add_action('wp_dashboard_setup', array('Comments_Dashboard_Widget','init') );

class Comments_Dashboard_Widget {

  const wid = 'comments_dashboard_widget';

  public static function init() {
    self::update_dashboard_widget_options( self::wid, array( 'only_pending' => true, ), true );

    wp_add_dashboard_widget( 
      self::wid, 
      __( 'Comments Dashboard', 'nouveau' ), 
      array('Comments_Dashboard_Widget','widget'), 
      array('Comments_Dashboard_Widget','config')
    );
  }

  public static function widget() {
    require_once( 'comms-dashboard-view.php' );
  }

  public static function config() {
    require_once( 'comms-dashboard-config.php' );
  }

  //
  // Below here are stock functions provided on the WP Codex site at:
  // http://codex.wordpress.org/Example_Dashboard_Widget
  //

  /**
   * Gets the options for a widget of the specified name.
   *
   * @param string $widget_id Optional. If provided, will only get options for the specified widget.
   * @return array An associative array containing the widget's options and values. False if no opts.
   */
  public static function get_dashboard_widget_options( $widget_id='' ) {
    //Fetch ALL dashboard widget options from the db...
    $opts = get_option( 'communications_dashboard_widget_options' );

    //If no widget is specified, return everything
    if ( empty( $widget_id ) ) {
      return $opts;
    }
    //If we request a widget and it exists, return it
    if ( isset( $opts[$widget_id] ) ) {
      return $opts[$widget_id];
    } 

    //Something went wrong...
    return false;
  }

  /**
   * Gets one specific option for the specified widget.
   * @param $widget_id
   * @param $option
   * @param null $default
   *
   * @return string
   */
  public static function get_dashboard_widget_option( $widget_id, $option, $default=NULL ) {
    $opts = self::get_dashboard_widget_options($widget_id);

    //If widget opts dont exist, return false
    if ( ! $opts ) {
      return false;
    }

    //Otherwise fetch the option or use default
    if ( isset( $opts[$option] ) && ! empty($opts[$option]) ) {
      return $opts[$option];
    }
    else {
      return ( isset($default) ) ? $default : false;
    }
  }

  /**
   * Saves an array of options for a single dashboard widget to the database.
   * Can also be used to define default values for a widget.
   *
   * @param string $widget_id The name of the widget being updated
   * @param array $args An associative array of options being saved.
   * @param bool $add_only If true, options will not be added if widget options already exist
   */
  public static function update_dashboard_widget_options( $widget_id , $args=array(), $add_only=false ) {
    //Fetch ALL dashboard widget options from the db...
    $opts = get_option( 'communications_dashboard_widget_options' );

    //Get just our widget's options, or set empty array
    $w_opts = ( isset( $opts[$widget_id] ) ) ? $opts[$widget_id] : array();
    if ( $add_only ) {
      //Flesh out any missing options (existing ones overwrite new ones)
      $opts[$widget_id] = array_merge($args,$w_opts);
    }
    else {
      //Merge new options with existing ones, and add it back to the widgets array
      $opts[$widget_id] = array_merge($w_opts,$args);
    }

    //Save the entire widgets array back to the db
    return update_option('communications_dashboard_widget_options', $opts);
  }
}

