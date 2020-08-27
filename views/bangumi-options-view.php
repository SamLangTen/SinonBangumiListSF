<?php require_once(ROOT_PATH."/views/view-helper.php"); ?>
<?php
    //apply settings
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if ($_POST["display_mode"]!=null) {
            update_option("sinonbangumilist_displaymode", sanitize_text_field($_POST["display_mode"]));
        }
        if ($_POST['sort_mode']!=null) {
            update_option("sinonbangumilist_sortmode", sanitize_text_field($_POST["sort_mode"]));
        }
        show_dismissible_notice(__("Settings saved.", "sinon-bangumi-list"), "success");
    }

    //load settings
    $display_mode = get_option("sinonbangumilist_displaymode");
    if ($display_mode==null) {
        $display_mode="list";
    }
    $sort_mode = get_option("sinonbangumilist_sortmode");
    if ($sort_mode==null) {
        $sort_mode="update_time";
    }
    
?>
<div class="wrap">
    <h1><?php _e("Sinon Bangumi List Options","sinon-bangumi-list"); ?></h1>
    <form action="" method="POST">
        <table class="form-table">
            <input name="action" value="do_edit" type="hidden"/>
            <input name="bangumi_id" value="<?php echo($bangumi['id']); ?>" type="hidden"/>
            <tbody>
                <!--Display Mode-->
                <tr>
                    <th scope="row"><label for="display_mode"><?php _e("Image URL", "sinon-bangumi-list"); ?></label></th>
                    <td>
                        <fieldset>
                            <label>
                                <input type="radio" name="display_mode" value="comment" <?php echo($display_mode=="comment"?"checked='checked'":""); ?> />
                                <span><?php _e("Comment Mode", "sinon-bangumi-list"); ?></span>
                            </label>
                            <br/>
                            <label>
                                <input type="radio" name="display_mode" value="list"<?php echo($display_mode=="list"?"checked='checked'":""); ?>/>
                                <span><?php _e("List Mode", "sinon-bangumi-list"); ?></span>
                            </label>
                        </fieldset>
                    </td>
                </tr>    
                <!--Sort Mode-->
                <tr>
                    <th scope="row"><label for="sort_mode"><?php _e("Sort Mode", "sinon-bangumi-list"); ?></label></th>
                    <td>
                        <fieldset>
                            <label>
                                <input type="radio" name="sort_mode" value="update_time" <?php echo($sort_mode=="update_time"?"checked='checked'":""); ?> />
                                <span><?php _e("Sort by Update Time", "sinon-bangumi-list"); ?></span>
                            </label>
                            <br/>
                            <label>
                                <input type="radio" name="sort_mode" value="id"<?php echo($sort_mode=="id"?"checked='checked'":""); ?>/>
                                <span><?php _e("Sort by Id", "sinon-bangumi-list"); ?></span>
                            </label>
                        </fieldset>
                    </td>
                </tr>                                                                     
            </tbody>
        </table>
        <?php submit_button(); ?>
    </form>
</div>