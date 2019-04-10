<?php
/*
Plugin Name: Custom map
Description:Create map from api script
Version: 1
Author: Saidani Ahmed
Author saidaniahmed125@gmail.com
*/

define('MAP__PLUGIN_DIR', plugin_dir_path(__FILE__));
define('MAP__PLUGIN_URL', plugin_dir_url(__FILE__));

require_once(MAP__PLUGIN_DIR . 'admin.php');

// function to create the DB / Options / Defaults					
function map_options_install() {
    global $wpdb;
    $table_name = $wpdb->prefix . "custom_map";
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE $table_name (
            `id` int CHARACTER SET utf8 NOT NULL AUTO_INCREMENT,
            `No` varchar(255) CHARACTER SET utf8 ,
            `Nom` varchar(255) CHARACTER SET utf8 ,
            `email` varchar(255) CHARACTER SET utf8 ,
            `Tel1` varchar(255) CHARACTER SET utf8 ,
            `Tel2` varchar(255) CHARACTER SET utf8 ,
            `Rue` varchar(255) CHARACTER SET utf8 ,
            `Num` varchar(255) CHARACTER SET utf8 ,
            `CodePostal` varchar(255) CHARACTER SET utf8 ,
            `Ville` varchar(255) CHARACTER SET utf8 ,
            `Pays` varchar(255) CHARACTER SET utf8 ,
            `Demo` varchar(255) CHARACTER SET utf8 ,
            `Minimal` varchar(255) CHARACTER SET utf8 ,
            `lat` varchar(255) CHARACTER SET utf8 ,
            `lng` varchar(255) CHARACTER SET utf8 ,
            `URLSite` varchar(255) CHARACTER SET utf8 ,
            `URLLogo` varchar(255) CHARACTER SET utf8 ,
            `keyword` varchar(255) CHARACTER SET utf8 ,
            PRIMARY KEY (`id`)
          ) $charset_collate; ";
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql);
}

// run the install scripts upon plugin activation
register_activation_hook(__FILE__, 'map_options_install');

add_action( 'wp_loaded', 'map_internal_rewrites' );
function map_internal_rewrites(){
    add_rewrite_rule( 'update_map$', 'index.php?event=updata_database', 'top' );
  	add_rewrite_rule( 'update_map$', 'index.php?event=updata_xml', 'top' );
  	add_rewrite_rule( 'update_map$', 'index.php?event=updata_json', 'top' );
  	add_rewrite_rule( 'update_map$', 'index.php?event=check_pagination', 'top' );
  	add_rewrite_rule( 'update_map$', 'index.php?event=create_js_file', 'top' );
}

// add url to update database with coach information
add_filter( 'query_vars', 'map_internal_query_vars' );
function map_internal_query_vars( $query_vars ){
    $query_vars[] = 'update_map';
    return $query_vars;
}

add_action( 'parse_request', 'map_internal_rewrites_parse_request' );
function map_internal_rewrites_parse_request( &$wp ){
	
    if ( 'update_map'!= $wp->query_vars['pagename'] ) {
        return;
    }
    $param=$_GET['event'];
    
    if(empty($param))
    	exit();

    switch ($param) {
    case "updata_database":
        require_once(MAP__PLUGIN_DIR . 'update_database.php');
        break;
    case "updata_xml":
        require_once(MAP__PLUGIN_DIR . 'update_xml_file.php');
        break;
    case "updata_json":
    	require_once(MAP__PLUGIN_DIR . 'JsonSimpleXMLElementDecorator.php');
        require_once(MAP__PLUGIN_DIR . 'update_json_file.php');
        break;
    // case "check_pagination":
   // 	require_once(MAP__PLUGIN_DIR . 'JsonSimpleXMLElementDecorator.php');
      //  require_once(MAP__PLUGIN_DIR . 'classes/index.php');
       // break;

     case "create_js_file":
        require_once(MAP__PLUGIN_DIR . 'create_js_location.php');
        break;
	}		
	
}

// Register the widget

// widget map 
// Register and load the widget
function wpb_load_widget() {
    register_widget( 'wpb_widget' );
}
add_action( 'widgets_init', 'wpb_load_widget' );
 
// Creating the widget 
class wpb_widget extends WP_Widget {
 
function __construct() {
parent::__construct(
 
// Base ID of your widget
'wpb_widget', 
 
// Widget name will appear in UI
__('Map Widget', 'wpb_widget_domain'), 
 
// Widget description
array( 'description' => __( 'Widget show map', 'wpb_widget_domain' ), ) 
);
}
 
// Creating widget front-end
 
public function widget( $args, $instance ) {

require_once( MAP__PLUGIN_DIR . 'show_map.php' );

echo $args['after_widget'];
}
         
// Widget Backend 
public function form( $instance ) {

}
     
// Updating widget replacing old instances with new
public function update( $new_instance, $old_instance ) {

}
} // Class wpb_widget ends here

function widget($atts) {
    
    global $wp_widget_factory;
    
    extract(shortcode_atts(array(
        'widget_name' => FALSE
    ), $atts));
    
    $widget_name = wp_specialchars($widget_name);
    
    if (!is_a($wp_widget_factory->widgets[$widget_name], 'WP_Widget')):
        $wp_class = 'WP_Widget_'.ucwords(strtolower($class));
        
        if (!is_a($wp_widget_factory->widgets[$wp_class], 'WP_Widget')):
            return '<p>'.sprintf(__("%s: Widget class not found. Make sure this widget exists and the class name is correct"),'<strong>'.$class.'</strong>').'</p>';
        else:
            $class = $wp_class;
        endif;
    endif;
    
    ob_start();
    the_widget($widget_name, $instance, array('widget_id'=>'arbitrary-instance-'.$id,
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '',
        'after_title' => ''
    ));
    $output = ob_get_contents();
    ob_end_clean();
    return $output;
    
}
add_shortcode('widget','widget'); 