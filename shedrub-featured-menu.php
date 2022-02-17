<?php 
/**
* Plugin Name: Shedub Featured Menu
* Plugin URI: https://shedrub.org/
* Description: Feature Menu for top header to expand hidden sub menu and featurs and Feature listing toogle inside any page with short code.
* Version: 1.0.4
* Author: Shedrub.org
* Author URI: https://shedrub.org/
* License: GPL2
*/
define('_pkb_VERSION',"1.0.4");


global $jal_db_version;
$jal_db_version = _pkb_VERSION;

function pkb_feature_menu_install() {
    global $wpdb;
    global $jal_db_version;

    $table_parent_name = $wpdb->prefix . 'featured_menu';
    $table_child_name = $wpdb->prefix . 'featured_menu_child';
    
    $charset_collate = $wpdb->get_charset_collate();

    $parent_sql = "CREATE TABLE $table_parent_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,           
        title tinytext,
        short_detail tinytext,
        svg_img tinytext,
        svg_hover_img tinytext,
        detail text,
        menu_order int DEFAULT 0,
        status enum('1','0') NOT NULL DEFAULT '1',
        time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";


    $child_sql = "CREATE TABLE $table_child_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        parent_id mediumint(9) NOT NULL,
        title tinytext NULL,
        domain_detail tinytext NULL, 
        detail text,
        menu_order int DEFAULT 0,
        status enum('1','0') NOT NULL DEFAULT '1',
        time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        PRIMARY KEY  (id),
        FOREIGN KEY (parent_id) REFERENCES $table_parent_name(id)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $parent_sql );
    dbDelta( $child_sql );

    add_option('pkb_menu_db_option', $jal_db_version );
}


function pkb_feature_menu_install_data() {
    global $wpdb;   
    
    $table_name = $wpdb->prefix . 'featured_menu';
    
    $wpdb->insert( 
        $table_name, 
        array(                
            'title' => 'Monks & Nuns', 
            'short_detail' =>'Ka-Nying Shedrub Ling and affiliated Buddhist Monasteries and Nunneries.',
            'svg_img' =>'Svg image code here ',
            'svg_hover_img'=> 'Svg image hover code here ',
            'detail'=>'detail text goes here',
            'time' => current_time( 'mysql' ), 
        ) 
    );
}

register_activation_hook( __FILE__, 'pkb_feature_menu_install' );
register_activation_hook( __FILE__, 'pkb_feature_menu_install_data' );

include( plugin_dir_path( __FILE__  ) . 'unstall.php' );

function menu_templates_fallback(){
    include( plugin_dir_path( __FILE__  ) . 'admin/feature_menu_list.php' );
}

function menu_templates_add_fallback(){
    include( plugin_dir_path( __FILE__  ). 'admin/feature_menu_add.php' );
}

function sub_menu_templates_fallback(){
    include( plugin_dir_path( __FILE__  ). 'admin/feature_sub_menu_list.php' );
}

function menu_setting_shortcode_fallback(){
    include( plugin_dir_path( __FILE__  ). 'admin/plugin-settings.php' );
}

function pkb_feature_menu() {
    add_menu_page( __('Feature Menu', 'featuremenu'), __('Feature Menu', 'featuremenu'), 'manage_options', 'menu-template', 'menu_templates_fallback', $dashboard , 26);
    add_submenu_page('menu-template', __('Add Items', 'featuremenu'), __('Add Menu Items', 'featuremenu'), 'manage_options', 'add-menu-template', 'menu_templates_add_fallback' );
    add_submenu_page('menu-template', __('update Items', 'featuremenu'), '', 'manage_options', 'sub-menu-template', 'sub_menu_templates_fallback' );
    add_submenu_page('menu-template', __('Plugin Setting', 'featuremenu'), __('Settings', 'featuremenu'), 'manage_options', 'menu-shortcode-settings', 'menu_setting_shortcode_fallback' );
    }
add_action( 'admin_menu', 'pkb_feature_menu', 3 );

      

/**
 * Enqueue a script in the WordPress admin on edit.php.
 *
 * @param int $hook Hook suffix for the current admin page.
 */
function pkb_feature_menu_admin_script( $hook ) {
    global $post;  
    // if ( $hook == 'post-new.php' || $hook == 'post.php' ) {
    //     if ('featured-menu-items' === $post->post_type ) {   
        // wp_enqueue_style( 'bootstrap-cdn-min', 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css', array(), _pkb_VERSION, 'all' );
        // wp_enqueue_script( 'bootstrap-min','https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js', array( 'jquery' ), true );
        wp_enqueue_style( 'feature_menu_admin_style', plugin_dir_url( __FILE__ ) . 'css/admin-style.css', false, _pkb_VERSION );
        wp_enqueue_script( 'feature_menu_admin_scripts', plugin_dir_url( __FILE__ ) . 'js/admin-custom.js', array('jquery'), _pkb_VERSION);
    //     }
    // }
}
add_action( 'admin_enqueue_scripts', 'pkb_feature_menu_admin_script' );




function pkb_feature_menu_front_script( $hook ) {
    // if ( 'edit.php' != $hook ) {
    //     return;
    // }
    wp_enqueue_style( 'feature_menu_front_style', plugin_dir_url( __FILE__ ) . 'css/style.css', false,_pkb_VERSION );
    wp_enqueue_script( 'feature_menu_front_scripts', plugin_dir_url( __FILE__ ) . 'js/custom.js', array(), _pkb_VERSION );
}
add_action( 'wp_enqueue_scripts', 'pkb_feature_menu_front_script' );

function header_showhidetoggle_menu($atts) {

	$Content .= '<h3 class="demoClass">This is Header tooggle menu!</h3>';	
    
    $Content .= '<table>
            <tr>
                <th>Firstname</th>
                <th>Lastname</th>
                <th>Savings</th>
            </tr>
            <tr>
                <td>Peter</td>
                <td>Griffin</td>
                <td>$100</td>
            </tr>
            <tr>
                <td>Lois</td>
                <td>Griffin</td>
                <td>$150</td>
            </tr>
            <tr>
                <td>Joe</td>
                <td>Swanson</td>
                <td>$300</td>
            </tr>
            <tr>
                <td>Cleveland</td>
                <td>Brown</td>
                <td>$250</td>
            </tr>
            </table>'; 
    return $Content;
}

function featured_toggle_display_content($atts) {
	$Content .= '<h3 class="demoClass">This is Featured list of menu item!</h3>';	
    $Content .= '<svg width="100" height="100">
        <circle cx="50" cy="50" r="40" stroke="green" stroke-width="4" fill="yellow" />
        Sorry, your browser does not support inline SVG.
        </svg> 
        <svg width="300" height="110">
        <rect width="300" height="100" style="fill:rgb(0,0,255);stroke-width:3;stroke:rgb(0,0,0)" />
        </svg> 
        <svg height="100" width="100">
        <circle cx="50" cy="50" r="40" stroke="black" stroke-width="3" fill="red" />
        </svg> ';
    
    return $Content;
}

add_shortcode('FEATURED_MENU_TOP_HEADER', 'header_showhidetoggle_menu');

add_shortcode('FEATURED_LIST_TOGGLE', 'featured_toggle_display_content');
?>