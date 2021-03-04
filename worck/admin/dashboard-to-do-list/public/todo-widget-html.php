<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if ( !function_exists( 'ardtdw_widget_html' ) ) {
function ardtdw_widget_html() {
$ardtdw_TextArea = stripslashes(get_option('ardtdw-textarea'));
$ardtdw_Position = esc_html(get_option('ardtdw-position'));

echo '<div id="ardtdw-sitewidget" class="ardtdw-sitewidget ardtdw-'.$ardtdw_Position.'">';
echo '<div class="ardtdw-sitewidget-inner">';
echo '<div class="ardtdw-sitewidget-head">';
echo '<p>'. __('To-Do List','dashboard-to-do-list') . '</p>';
echo '<a href="' . site_url() . '/wp-admin" target="_blank" title="'. __('Add Job','dashboard-to-do-list') . '">+</a>';
echo '</div>';
echo '<div class="ardtdw-sitewidget-list">';
echo '<ul><li>' . str_replace(PHP_EOL,"</li><li>", stripslashes($ardtdw_TextArea)) . '</li></ul>';
echo '</div>';
echo '</div>';
echo '</div>';
}
}
?>
