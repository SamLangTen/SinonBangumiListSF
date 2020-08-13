
<?php require_once(ROOT_PATH."/functions/bangumi.php") ?>
<?php
$css_url = esc_url(plugins_url('../css/style-embed.css', __FILE__));
wp_enqueue_style('Sinon_Bangumi_Item', $css_url);

/**
* Render bangumi item in embed mode
*
*/
function render_bangumi_item_embed($bangumi_id)
{
    $bangumi = bangumi::get_bangumi_by_id($bangumi_id);
    ?>
    <a href="<?php echo(esc_url($bangumi['url'])); ?>" target="_blank" class="sinon-bangumi-item" title="<?php echo(esc_attr($bangumi['title'])); ?>">
        <img src="<?php echo(esc_url($bangumi['img'])); ?>"><div class="textbox">
        <?php echo(esc_attr($bangumi['name_cn'])); ?>
        <br>
        <?php echo(esc_attr($bangumi['name'])); ?>
        <br>首播日期：<?php echo(esc_attr($bangumi['date'])); ?><br>
        <div class="sinon-progress-background">
            <div class="sinon-progress-text">
                <?php
    $percent = 100;
    if ($bangumi['status']==0) {
        echo(__("Watched:", "sinon-bangumi-list")."0/".esc_attr($bangumi['count']));
        $percent = 0;
    } elseif ($bangumi['status']==2) {
        echo(__("Watched", "sinon-bangumi-list"));
        $percent = 100;
    } else {
        $label_progress = esc_attr($bangumi['times'] != null && $bangumi['times'] > 1 ? ($bangumi['times'].__(" times:", "sinon-bangumi-list")) : __("Watched:", "sinon-bangumi-list"));
        $label_progress = $label_progress.esc_attr($bangumi['progress']).'/'. esc_attr($bangumi['count']);
        echo($label_progress);
        $percent=(float) $bangumi['progress'] / $bangumi['count'] * 100;
    } ?>
            </div>
            <div class="sinon-progress-foreground" style="width:<?php echo(esc_attr($percent)); ?>%;"></div></div>
        </div>
    </a>
    <?php
}
