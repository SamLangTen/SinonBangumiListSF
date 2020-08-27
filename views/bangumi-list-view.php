<?php require_once(ROOT_PATH."/functions/bangumi.php"); ?>
<?php require_once(ROOT_PATH."/views/view-helper.php");?>
<?php
    if ($_SERVER['REQUEST_METHOD']=="POST") {
        if (! isset($_POST['list_action'])|| ! wp_verify_nonce($_POST['list_action'], 'sbl_list_update_status_action')) {
            print 'Sorry, your nonce did not verify.';
            exit;
        }
        //Update Status
        $id = (int)$_POST['id'];
        if ($_POST['action']=="update_status") {
            //Update
            if ((int)$_POST['bg_status']==1) {
                //if in Watching, update progress and times
                if ($_POST['progress']!=null && is_numeric($_POST['progress'])) {
                    $progress = (int)sanitize_text_field($_POST['progress']);
                } else {
                    show_dismissible_notice(__("Invalid progress set", "sinon-bangumi-list"), "error");
                }

                if ($_POST['times']!=null&& is_numeric($_POST['times'])) {
                    $times = (int)sanitize_text_field($_POST['times']);
                } else {
                    show_dismissible_notice(__("Invalid times set", "sinon-bangumi-list"), "error");
                }
            }
            $status = (int)sanitize_text_field($_POST['bg_status']);
            $result = bangumi:: update_bangumi_status($id, $status, $times, $progress);
            if ($result==true) {
                show_dismissible_notice(__("Bangumi status updated", "sinon-bangumi-list"), "success");
            } else {
                show_dismissible_notice(__("Failed to update status, maybe progress is larger than episode count.", "sinon-bangumi-list"), "error");
            }
        }
    }
    /* Construct status filter */
    $sf_count_all = 0;
    $sf_count_finished = 0;
    $sf_count_ready = 0;
    $sf_count_watching = 0;
    $all_bangumi = bangumi::get_all_bangumi();
    foreach ($all_bangumi as $b) {
        if ($b['status']==0) {
            $sf_count_ready++;
        }
        if ($b['status']==1) {
            $sf_count_watching++;
        }
        if ($b['status']==2) {
            $sf_count_finished++;
        }
        $sf_count_all++;
    }
    
    $filter = -1;
    if ($_GET['filter']=="all") {
        $filter = -1;
    } elseif ($_GET['filter']=="ready") {
        $filter = 0;
    } elseif ($_GET['filter']=="watching") {
        $filter = 1;
    } elseif ($_GET['filter']=="finished") {
        $filter = 2;
    }

?>
<div class="wrap">
    <!-- Page heading -->
    <h1 class="wp-heading-inline"><?php _e("Bangumi List", "sinon-bangumi-list") ?></h1>
    <a class="page-title-action" href="<?php echo admin_url("admin.php?page=sinon_bangumi_new"); ?>"><?php _e("Add new bangumi", "sinon-bangumi-list") ;?></a>
    <hr class="wp-header-end">
    <!-- Status filter -->
    <ul class="subsubsub">
        <li><a href="<?php echo admin_url("admin.php?page=sinon_bangumi_list&filter=all"); ?>" <?php echo($filter==-1?"class='current'":""); ?> aria-current="page"><?php _e("All", "sinon-bangumi-list"); ?><span class="count">（<?php echo($sf_count_all); ?>）</span></a> |</li>
        <li><a href="<?php echo admin_url("admin.php?page=sinon_bangumi_list&filter=ready"); ?>" <?php echo($filter==0?"class='current'":""); ?> aria-current="page"><?php _e("Ready to Watch", "sinon-bangumi-list"); ?><span class="count">（<?php echo($sf_count_ready); ?>）</span></a> |</li>
        <li><a href="<?php echo admin_url("admin.php?page=sinon_bangumi_list&filter=watching"); ?>" <?php echo($filter==1?"class='current'":""); ?> aria-current="page"><?php _e("In Watching", "sinon-bangumi-list"); ?><span class="count">（<?php echo($sf_count_watching); ?>）</span></a> |</li>
        <li><a href="<?php echo admin_url("admin.php?page=sinon_bangumi_list&filter=finished"); ?>" <?php echo($filter==2?"class='current'":""); ?> aria-current="page"><?php _e("Watched", "sinon-bangumi-list"); ?><span class="count">（<?php echo($sf_count_finished); ?>）</span></a></li>
    </ul>
    <!-- Table -->
    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>              
                <th scope="col" class="manage-column column-primary"><?php _e("Bangumi Name", "sinon-bangumi-list"); ?></th>
                <th scope="col"><?php _e("Status", "sinon-bangumi-list"); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
                $all_bangumi = bangumi::get_all_bangumi();
                $real_output = 0;
                if ($all_bangumi != null) {
                    foreach ($all_bangumi as $bangumi) {
                        if ($filter!=-1&&$filter!=$bangumi['status']) {
                            continue;
                        }
                        $real_output++; ?>
            <tr>
                <th>
                    <strong><a class="row-title"><?php echo($bangumi["name_cn"]) ?></a></strong>
                    <div class="row-actions">
                            <span class="edit">
                                <a href="<?php echo(admin_url()."admin.php?page=sinon_bangumi_edit&bangumi_id=".$bangumi["id"]); ?>"><?php _e("Edit", "sinon-bangumi-list"); ?></a> | 
                            </span>
                            <span class="delete">
                                <a href="<?php echo(admin_url()."admin.php?page=sinon_bangumi_edit&bangumi_id=".$bangumi["id"]."&action=delete"); ?>"><?php _e("Delete", "sinon-bangumi-list"); ?></a>
                            </span>
                    </div>
                </th>
                <td>
                    <form action="" method="POST">
                        <?php wp_nonce_field("sbl_list_update_status_action", "list_action"); ?>
                        <input type="hidden" value="update_status" name="action"/>
                        <input type="hidden" value="<?php echo($bangumi["id"]) ?>" name="id"/>
                        <select name="bg_status">
                            <option value=0 <?php echo($bangumi['status']==0?"selected":""); ?>><?php echo(_e("Ready to Watch", "sinon-bangumi-list")); ?></option>
                            <option value=1 <?php echo($bangumi['status']==1?"selected":""); ?>><?php echo(_e("In Watching", "sinon-bangumi-list")); ?></option>
                            <option value=2 <?php echo($bangumi['status']==2?"selected":""); ?>><?php echo(_e("Watched", "sinon-bangumi-list")); ?></option>
                        </select>
                        <?php
                            if ($bangumi['status']==1) {
                                ?>
                        <label for="progress"><?php echo(_e("Progress:", "sinon-bangumi-list")); ?></label>
                        <input type="text" value="<?php echo($bangumi["progress"]) ?>" name="progress" style="width: 40px;text-align: center;"/>
                        <label for="times"><?php echo(_e("Times:", "sinon-bangumi-list")); ?></label>
                        <input type="text" value="<?php echo($bangumi["times"]) ?>" name="times" style="width: 40px;text-align: center;"/>
                                <?php
                            } ?>
                        <input type="submit" value="<?php echo(_e("Update Status", "sinon-bangumi-list")); ?>" class="button button-primary">
                    </form>
                </td>
            </tr>
            <?php
                    }
                }

                if ($real_output==0) {
                    echo("<tr><th span=\"4\">".__("No Bangumi", "sinon-bangumi-list")."</th></tr>");
                }
            ?>
        </tbody>
        <tfoot>
            <tr>              
                <th scope="col" class="manage-column column-primary"><?php _e("Bangumi Name", "sinon-bangumi-list"); ?></th>
                <th scope="col"><?php _e("Status", "sinon-bangumi-list"); ?></th>
            </tr>
        </tfoot>
    </table>
</div>