<?php
global $wpdb;
$msg = '';

$mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : '';

if (isset($_POST['template_submit'])) {    
    if ($mode == 'edit') {
        $data = array(
            'title' => addslashes($_POST['menu_title']),
            'short_detail' => addslashes($_POST['short_detail']),
            'svg_img' => addslashes($_POST['svg_img']),
            'svg_hover_img' => addslashes($_POST['svg_hover_img']),
            'menu_order' => addslashes($_POST['menu_order']),
            'detail' => addslashes($_POST['detail']),
            'status' => addslashes($_POST['status']));

        $response = $wpdb->update($wpdb->prefix . 'featured_menu', $data, array('id' => $_POST['template_id']));
        
        if($response){
            $result['success'] = "1";
            $result['msg'] = "Menu Item Updated Successfully";
            $_SESSION['flash_response'] = $result;
        }
    } else {
        if($_POST['menu_title'] == ''){
            $error_count++;
            $err_msg = "Menu title required";
        }         
        
        $data = array(
            'title' => addslashes($_POST['menu_title']),
            'short_detail' => addslashes($_POST['short_detail']),
            'svg_img' => addslashes($_POST['svg_img']),
            'svg_hover_img' => addslashes($_POST['svg_hover_img']),
            'menu_order' => addslashes($_POST['menu_order']),
            'detail' => addslashes($_POST['detail']),
            'status' => addslashes($_POST['status']));
        
        if($error_count>0){
           

        }else{
            $response = $wpdb->insert($wpdb->prefix . 'featured_menu', $data);
            
            if($response){
                $result['success'] = "1";
                $result['msg'] = "Menu Item Added Successfully";
                $_SESSION['flash_response'] = $result;
            }
        }        
    }
    echo '<script> window.location="admin.php?page=menu-template" </script>';
}

if ($mode == 'edit') {
    $result = $wpdb->get_row("select * from " . $wpdb->prefix . "featured_menu where id=$_REQUEST[cid]");
}
?>
<div id="wpbody" role="main">
    <?php 
    if(!empty($err_msg)){
    echo '<div class="wrap"><div id="message" class="error" style="margin-left:0;">';
    echo '<p>'.$err_msg.'</p>';
    echo '</div></div>';
  }
  ?>
    <div id="wrap">
        <h2> <?php _e('Add Menu Items', 'featuremenu'); ?></h2>


        <form action="" method="post" enctype="multipart/form-data">
            <table class="form-table">
                <tbody>
                   
                    <tr valign="top">
                        <th scope="row">Title</th>
                        <td><input type="text" size="50" id="menu_title" name="menu_title" value="<?php
                        if ($mode == 'edit') {
                            echo stripslashes($result->title);
                        }
                        ?>"></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Short Detail</th>
                        <td>
                            <textarea class="textareagroup" rows="2" cols="50"  name="short_detail"><?php
                            if ($mode == 'edit') {
                                echo $result->short_detail;
                            }
                            ?>
                            </textarea>
                    </td>
                    </tr>    
                    <tr valign="top">
                        <th scope="row">SVG Image Code</th>
                        <td>
                            <textarea class="textareagroup" rows="2" cols="50"  name="svg_img" ><?php
                            if ($mode == 'edit') {
                                echo $result->svg_img;
                            }
                            ?>
                            </textarea>
                    </td>
                    </tr>    
                    <tr valign="top">
                        <th scope="row">SVG Image Hover Code</th>
                        <td>
                            <textarea class="textareagroup" rows="2" cols="50"  name="svg_hover_img" ><?php
                            if ($mode == 'edit') {
                                echo $result->svg_hover_img;
                            }
                            ?>
                            </textarea>
                    </td>                    
                    </tr>  
                    <tr valign="top">
                        <th scope="row">Menu Order</th>
                        <td><input type="text" size="50" id="menu_order" name="menu_order" value="<?php
                        if ($mode == 'edit') {
                            echo stripslashes($result->menu_order);
                        }
                        ?>"> eg. 1,2,3...</td>
                    </tr>   
                    <tr valign="top">
                        <th scope="row">Status</th>
                        <td>
                        Publish
                        <input type="radio" <?php if ($mode == 'edit' && $result->status ==1) {
                            echo "checked='checked'";
                        }else{ echo "checked='checked'"; }?> id="status" name="status" value="1">
                        UnPublish
                        <input type="radio" <?php if ($mode == 'edit' && $result->status ==0) {
                            echo "checked='checked'";
                        }?>  id="status" name="status" value="0">
                        </td>
                    </tr>                
                 
                    <?php if (isset($result->is_admin) && $result->is_admin == '1') {
                    ?>
                    <tr valign="top">
                        <th scope="row">Admin Receiver Email</th>
                        <td><input type="text" size="50" name="email_template_admin_email" value="<?php
                        if ($mode == 'edit') {
                            echo stripslashes($result->admin_email);
                        }
                        ?>">
                        </td>
                    </tr>
                    <?php 
                    }?>
                    <tr valign="top">
                        <th scope="row">Content Body</th>
                        <td>
                           <?php
                            if ($mode == 'edit'){
                                $content = stripslashes($result->detail);
                            }
                            else
                                $content = '';

                            $settings = array(
                                'wpautop' => true,
                                'media_buttons' => true,
                                'textarea_name' => 'detail',
                                'textarea_rows' => 15,
                                'tabindex' => '',
                                'tabfocus_elements' => ':prev,:next',
                                'editor_css' => '',
                                'editor_class' => '',
                                'teeny' => false,
                                'dfw' => true,
                                'tinymce' => true, 
                                'quicktags' => true
                            );
                            wp_editor($content, 'detail', $settings);
                            ?>
                        </td>
                    </tr>
                </tbody>
            </table>
            <input type="hidden" name="template_id" value="<?php if ($mode == 'edit') echo $result->id; ?>" />
            <p class="submit"><input type="submit" name="template_submit" id="submit" class="button button-primary"
                    value="Save Changes"></p>
        </form>
    </div>
</div>