<?php
/*
Plugin Name: Sinon的追番列表(Sam Fork版)
Plugin URI: https://github.com/SamLangTen/SinonBangumiList
Description: 使用短代码[bangumi id="all"]在页面上生成追番列表，[bangumi id="id"]在任意页面嵌入单个番剧信息，在“后台”菜单中配置追番列表。
Version: 1.2.7
Author: SamLangTen
Author URI: https://github.com/SamLangTen/SinonBangumiList
*/
define("ROOT_PATH", __DIR__);
require_once(ROOT_PATH."/view-controller.php");

/*load text domain form internationalization*/
load_plugin_textdomain( "sinon-bangumi-list", false, ROOT_PATH."/lang/" );

/*setup hook into 'admin_menu' to insert option page*/
add_action("admin_menu", "admin_menu_handler");

/* setup hook into shortcode to replace [bangumi] */
add_shortcode("bangumi", 'view_controller::bangumi_render');

function admin_menu_handler()
{
    add_menu_page(__("Sinon Bangumi", "sinon-bangumi-list"), __("Sinon Bangumi", "sinon-bangumi-list"), "administrator", "sinon_bangumi_options", "view_controller::bangumi_options");
    add_submenu_page("sinon_bangumi_options", __("Bangumi List", "sinon-bangumi-list"), __("Bangumi List", "sinon-bangumi-list"), "administrator", "sinon_bangumi_list", "view_controller::bangumi_list");
    add_submenu_page("sinon_bangumi_options", __("Add new bangumi", "sinon-bangumi-list"), __("Add new bangumi", "sinon-bangumi-list"), "administrator", "sinon_bangumi_new", "view_controller::bangumi_new");
    add_menu_page(__("Edit bangumi", "sinon-bangumi-list"), __("Edit bangumi", "sinon-bangumi-list"), 'administrator', 'sinon_bangumi_edit', 'view_controller::bangumi_edit');
    remove_menu_page("sinon_bangumi_edit");
}
