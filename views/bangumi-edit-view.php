<?php
    require_once(ROOT_PATH."/functions/bangumi.php");
    require_once(ROOT_PATH."/views/view-helper.php");
    if ($_GET['bangumi_id']==null) {
        return;
    }
    if ($_GET['action']=='delete') {
        $bangumi = bangumi::get_bangumi_by_id($_GET['bangumi_id']);
        render_delete_box($bangumi);
    } else {
        $bangumi = bangumi::get_bangumi_by_id($_GET['bangumi_id']);
        include_once(ROOT_PATH."/views/bangumi-edit-component.php");
    }

    if ($_POST['action']=='apply_delete') {
        $ids = $_POST['bangumi_id'];
        if ($ids != null) {
            foreach ($ids as $id) {
                bangumi::delete_bangumi_from_id($id);
            }
        }
        redirect_to_admin_url("admin.php?page=sinon_bangumi_list", null);
    }

    function render_delete_box($bangumi)
    {
        ?>
        <form action="" method="POST">
            <div class="wrap">
            <h1><?php _e("Delete bangumi", "sinon-bangumi-list") ?></h1>
            <p><?php _e("You are going to delete this bangumi", "sinon-bangumi-list") ?></p>	
            <ul>
                <li><input type="hidden" name="bangumi_id[]" value="<?php echo($bangumi['id']); ?>">ID <?php echo($bangumi['id']); ?>：<?php echo($bangumi['name_cn']); ?></li>
            </ul>
                <input type="hidden" name="action" value="apply_delete">
                    <p class="submit">
                        <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e("Delete", "sinon-bangumi-list") ?>"/>
                    </p>
            </div>
        </form>
        <?php
    }
