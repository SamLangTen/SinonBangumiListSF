<?php

class view_controller
{
    public static function bangumi_list()
    {
        include_once(ROOT_PATH."/views/bangumi-list-view.php");
    }

    public static function bangumi_options()
    {
        include_once(ROOT_PATH."/views/bangumi-options-view.php");
    }

    public static function bangumi_new()
    {
        include_once(ROOT_PATH."/views/bangumi-new-view.php");
    }

    public static function bangumi_edit()
    {
        include_once(ROOT_PATH."/views/bangumi-edit-view.php");
    }

    public static function bangumi_render($atts = array(), $content = null, $tag = '')
    {
        // normalize attribute keys, lowercase
        $atts = array_change_key_case((array) $atts, CASE_LOWER);
        $wporg_atts = shortcode_atts(
            array(
                'id' => 'all',
            ),
            $atts,
            $tag
        );
        // check id
        ob_start();
        if ($atts['id']=='all') {
            include_once(ROOT_PATH."/views/bangumi-generator-view.php");
        } else {
            $bangumi_id = (int)$atts['id'];
            include_once(ROOT_PATH."/views/bangumi-generator-embed-view.php");
            render_bangumi_item_embed($bangumi_id);
        }
        $render = ob_get_clean();
        return $render;
    }
}
