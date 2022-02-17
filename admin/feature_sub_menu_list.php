<?php
global $wpdb;

if (isset($_POST['child_feature_submit'])) {
    $update_id = $_POST['template_id']; 

    if (!empty($update_id)) {
        $data = array(
            'title' => addslashes($_POST['title']),
            'domain_detail' => addslashes($_POST['domain_detail']),
            'detail' => addslashes(trim($_POST['detail'])),
            'menu_order' => addslashes($_POST['menu_order']),
            'parent_id' => addslashes($_POST['parent_id']),
            'status' => addslashes($_POST['status']));

        $response = $wpdb->update($wpdb->prefix . 'featured_menu_child', $data, array('id' => $update_id));
        
        if($response){
            $result['success'] = "1";
            $result['msg'] = "Feature Updated Successfully";
            $_SESSION['flash_response'] = $result;
        }
    } else {

        if($_POST['title'] == ''){
            $error_count++;
            $err_msg = "Menu title required";
        }         
        
        $data = array(
            'title' => addslashes($_POST['title']),
            'domain_detail' => addslashes($_POST['domain_detail']),
            'detail' => addslashes($_POST['detail']),
            'menu_order' => addslashes($_POST['menu_order']),
            'parent_id' => addslashes($_POST['parent_id']),
            'status' => addslashes($_POST['status']));
        
        if($error_count>0){
           

        }else{
            $response = $wpdb->insert($wpdb->prefix . 'featured_menu_child', $data);
            
            if($response){
                $result['success'] = "1";
                $result['msg'] = "Feature  Added Successfully";
                $_SESSION['flash_response'] = $result;
            }
        }
        
    }
   
}



$msg = '';
$mode = isset($_REQUEST['mode'])?$_REQUEST['mode']:'';
$edit_id = $_REQUEST['cid'];

$parent_id = isset($_REQUEST['parent_id']) ? $_REQUEST['parent_id'] : '';
if(!empty($parent_id))
{
    $parent_menu = 'SELECT * from '.$wpdb->prefix . 'featured_menu where id = '.$parent_id;
    $parent_result = $wpdb->get_results( $parent_menu );
}


if ($mode == 'delete') {    
    $response = $wpdb->delete( $wpdb->prefix . 'featured_menu_child', array( 'id' => $edit_id ) );
    if($response){
        $res['success'] = "1";
        $res['msg'] = "Feature Content Deleted Successfully";
        $_SESSION['flash_response'] = $res;
    }
}

if ($mode == 'edit') {   
    $edit_query = 'SELECT * from '.$wpdb->prefix . 'featured_menu_child where id='.$edit_id;
    $result = $wpdb->get_row( $edit_query ); 
}

$child_query = 'SELECT * from '.$wpdb->prefix . 'featured_menu_child where parent_id='.$parent_result[0]->id." order by menu_order asc";
$list_features = $wpdb->get_results( $child_query );


/** 
 *  Save Sub menu content when clicking submit buttons
 * 
 * 
 */
?>
<div id="wpbody" role="main">
    <div id="wrap">
        <div class="wrap">
           <h2><?php _e('Inner Content for : ', 'featuremenu');  echo stripslashes($parent_result[0]->title);?></h2>
        </div>
        <?php
      if(!empty($msg)){
        echo '<div class="wrap"><div id="message" class="updated" style="margin-left:0;">';
        echo '<p>'.$msg.'</p>';
        echo '</div></div>';
      }

      if(isset($_SESSION['flash_response'] )){
          if($_SESSION['flash_response']['success']=='1'){
                echo '<div class="wrap"><div id="message" class="updated" style="margin-left:0;">';
                echo '<p>'. $_SESSION['flash_response']['msg'].'</p>';
                echo '</div></div>';
          }else{
            echo '<div class="wrap"><div id="message" class="error" style="margin-left:0;">';
            echo '<p>'. $_SESSION['flash_response']['msg'].'</p>';
            echo '</div></div>';
          }
          unset($_SESSION['flash_response']);
      }

    ?>
        <div class="wrap">
            <div class="padding-box">
            <form action="<?php echo site_url('/').'wp-admin/admin.php?page=sub-menu-template&parent_id='.$parent_result[0]->id;?>" method="post" enctype="multipart/form-data">
            <table class="form-table">
                <tbody>
                   
                    <tr valign="top">
                    <th scope="row">Parent Menu</th>
                    <td><strong><?php echo stripslashes($parent_result[0]->title); ?></strong> <span style="float:right"><a href="<?php echo site_url('/').'wp-admin/admin.php?page=menu-template';?>"> << Back to menu list</a></span>
                    </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Title</th>
                        <td><input type="text" size="50" id="title" name="title" value="<?php
                        if ($mode == 'edit') {
                            echo trim($result->title);
                        }
                        ?>"></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Domain detail</th>
                        <td><input type="text" size="50" id="domain_detail" name="domain_detail" value="<?php
                        if ($mode == 'edit') {
                            echo trim($result->domain_detail);
                        }
                        ?>"> eg: MonksAndNuns.org </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Short Detail</th>
                        <td>
                            <textarea class="textareagroup" rows="2" cols="50"  name="detail">
                            <?php
                            if ($mode == 'edit') {
                                echo $result->detail;
                            }
                            ?>
                            </textarea>
                    </td>                
                    </tr>  
                    <tr valign="top">
                        <th scope="row">Display Order</th>
                        <td><input type="text" size="50" id="menu_order" name="menu_order" value="<?php
                        if ($mode == 'edit') {
                            echo $result->menu_order;
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
                    </tr>
                </tbody>
            </table>
            <input type="hidden" name="template_id" value="<?php if ($mode == 'edit') echo $result->id; ?>" />
            <input type="hidden" name="parent_id" value="<?php echo stripslashes($parent_result[0]->id); ?>" />
            <p class="submit"><input type="submit" name="child_feature_submit" id="submit" class="button button-primary"
                    value="Save Changes"></p>
        </form>
            </div>
            <div class="padding-box">               
                <table width="100%" class="order_list wp-list-table widefat fixed striped pages">
                    <thead>
                        <tr>
                            <th width="6%">#</th>
                            <th><?php  _e('Feature Title', 'featuremenu') ?></th>
                            <th><?php  _e('Parent Menu', 'featuremenu') ?></th>  
                            <th><?php  _e('Order', 'featuremenu') ?></th>       
                            <th><?php  _e('Status', 'featuremenu') ?></th>                        
                            <th>Options</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 

                    if( $list_features ) {
                        $count = (intval($page_number) - 1 ) * $per_page + 1;
                        $class = '';
                            foreach( $list_features as $entry ) {
                              $class = ($count%2 == 1 ? 'class="alternate "' : ''); 
                            ?>
                        <tr <?php echo $class; ?>>
                            <td><?php echo $count;  ?></td>                         
                            <td><?php echo $entry->title; ?></td>
                            <td> <?php echo stripslashes($parent_result[0]->title); ?></td>
                            <td><?php echo $entry->menu_order; ?></td>
                            <td><?php  if($entry->status=='1'){ echo'Published';}else{ echo'Not Published';} ?></td>
                            <td><a href="<?php echo site_url('/').'wp-admin/admin.php?page=sub-menu-template&parent_id='.$parent_result[0]->id.'&cid='.$entry->id.'&mode=edit'; ?>">Edit</a>
                             / <a style="cursor:pointer" onclick="deleteTemplate('<?php echo $parent_result[0]->id;?>','<?php echo $entry->id;?>')">Delete</a>
                             </td>
                        </tr>
                        <?php $count++;  } 
                        } else { ?>
                            <tr><td colspan="6">In featured content inside this menu </td></tr>
                        <?php } ?>


                    </tbody>
                    <tfoot>
                        <tr>                 
                            <th width="6%">#</th>
                            <th><?php  _e('Feature Title', 'featuremenu') ?></th>
                            <th><?php  _e('Parent Menu', 'featuremenu') ?></th>  
                            <th><?php  _e('Order', 'featuremenu') ?></th>       
                            <th><?php  _e('Status', 'featuremenu') ?></th>                        
                            <th>Options</th>
                        </tr>
                    </tfoot>
                </table>
            </div>  
</div>
<script>  
function deleteTemplate(parent_id,template_id){   
    var confirm_delete = confirm("Are your sure to delete feature item?");
    if(!confirm_delete){ return false; }
    window.location="admin.php?page=sub-menu-template&parent_id="+parent_id+"&cid="+template_id+"&mode=delete";
}    
</script>