<div id="wpbody" role="main">
    <div id="wrap">
    <div class="wrap">
        <h2><?php _e('Settings', 'featuremenu'); ?></h2>
        <p><?php _e('Available Style and Shortcode', 'featuremenu'); ?></p>
    </div>

    <div class="wrap">
    <div class="padding-box">               
        <table width="100%" class="order_list wp-list-table widefat fixed striped pages">
            <thead>
                <tr>
                    <th width="6%">#</th>
                    <th width="15%"><?php  _e('Position', 'featuremenu') ?></th>
                    <th width="50%"><?php  _e('Display Layout', 'featuremenu') ?></th>
                    <th width="20%"><?php  _e('Shortcode', 'featuremenu') ?></th>   
                </tr>
            </thead>
            <tbody>
                <tr class="">
                    <td>1</td> 
                    <td >Top Header </td>                        
                    <td ><img src="<?php echo plugin_dir_url( __DIR__ ).'img/header_top.png';?>" class="image_thumb_disp"></td>
                    <td >Shortcode <br/> [FEATURED_MENU_TOP_HEADER]</td>
                </tr>
                <tr class="alternate">
                    <td>2</td>   
                    <td>Inside Page /Post</td>                      
                    <td><img src="<?php echo plugin_dir_url( __DIR__ ).'img/toggle_layout.png';?>" class="image_thumb_disp"></td>
                    <td>Shortcode <br/> [FEATURED_LIST_TOGGLE] </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>