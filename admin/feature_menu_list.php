<?php
global $wpdb;

$msg = '';

$mode = isset($_REQUEST['mode'])?$_REQUEST['mode']:'';

if ($mode == 'delete') {
    $id = $_REQUEST['cid'];
    if(!empty($id)){
        $response_child = $wpdb->delete( $wpdb->prefix . 'featured_menu_child', array( 'parent_id' => $id ) );
        $response = $wpdb->delete( $wpdb->prefix . 'featured_menu', array( 'id' => $id ) );
        if($response){
            $res['success'] = "1";
            $res['msg'] = "Menu Item Deleted With All Sub Featured Items Successfully";
            $_SESSION['flash_response'] = $res;
        }
    }       
}


$menu_lists = 'SELECT * from '.$wpdb->prefix . 'featured_menu where 1=1 order by menu_order asc';
$result = $wpdb->get_results( $menu_lists );

?>
<div id="wpbody" role="main">
    <div id="wrap">
        <div class="wrap">
           <h2><?php _e('Featured Menu', 'featuremenu'); ?></h2>
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
                <table width="100%" class="order_list wp-list-table widefat fixed striped pages">
                    <thead>
                        <tr>
                            <th width="6%">#</th>
                            <th><?php  _e('Menu Title', 'featuremenu') ?></th>
                            <th><?php  _e('Menu Order', 'featuremenu') ?></th>   
                            <th><?php  _e('Add/Edit Sub content', 'featuremenu') ?></th>       
                            <th><?php  _e('Status', 'featuremenu') ?></th>                        
                            <th>Options</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 

                    if( $result ) {
                        $count = (intval($page_number) - 1 ) * $per_page + 1;
                        $class = '';
                            foreach( $result as $entry ) {
                              $class = ($count%2 == 1 ? 'class="alternate "' : ''); 

                            ?>
                        <tr <?php echo $class; ?>>
                            <td><?php echo $count;  ?></td>                         
                            <td><?php echo $entry->title; ?></td>
                            <td><?php echo $entry->menu_order; ?></td>
                            <td><a href="<?php echo site_url('/').'wp-admin/admin.php?page=sub-menu-template&parent_id='.$entry->id; ?>"> [ Add/Edit ]</a></td>
                            <td><?php  if($entry->status=='1'){ echo'Published';}else{ echo'Not Published';} ?></td>
                            <td><a href="<?php echo site_url('/').'wp-admin/admin.php?page=add-menu-template&cid='.$entry->id.'&mode=edit'; ?>">Edit</a>
                             / <a style="cursor:pointer" onclick="deleteTemplate('<?php echo $entry->id;?>')">Delete</a>
                             </td>
                        </tr>
                        <?php $count++;  } 
                        } else { ?>
                        <tr <?php echo $class; ?>><td colspan="6">No menu list found <a href="<?php echo site_url('/wp-admin/admin.php?page=add-menu-template');?>">Create Menu Now</a></td></tr>
                        <?php } ?>


                    </tbody>
                    <tfoot>
                        <tr>                 
                        <th width="6%">#</th>
                            <th><?php  _e('Menu Title', 'featuremenu') ?></th>
                            <th><?php  _e('Menu Order', 'featuremenu') ?></th>   
                            <th><?php  _e('Add/Edit Sub content', 'featuremenu') ?></th>       
                            <th><?php  _e('Status', 'featuremenu') ?></th>                        
                            <th>Options</th>
                        </tr>
                    </tfoot>
                </table>
            </div>

</div>
<script>  
function deleteTemplate(template_id){   
    var confirm_delete = confirm("Are your sure to delete this menu? \nIt also delete all featured content inside this menu!");
    if(!confirm_delete){ return false; }
    window.location="admin.php?page=menu-template&cid="+template_id+"&mode=delete";
}    
</script>