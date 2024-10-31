<?php
/*
Plugin Name: Question Answer
Plugin URI: http://www.webschreinerei.de/wordpress/
Description: Lets you add a Site with a question depending on the answer you get the next question. See an example at <a href="http://www.datenrettung-ohne-backup.de/rescue-tool/">Datenrettung-ohne-Backup.de</a>.
Version: 1.3
Author: Dennis Suchomsky
Author URI: http://www.datenrettung-ohne-backup.de
*/


/*  Copyright 2008  Dennis Suchomsky  (email : solarwasser@googlemail.com )

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


$question_answer_domain = 'question_answer';
$question_answer_is_setup = 0;
$question_answer_db_version = 1.1;
$question_table_name = $wpdb->prefix . "question_answer_questions";
$answer_table_name = $wpdb->prefix . "question_answer_answers";
  
$wpdb->show_errors();

register_activation_hook(__FILE__,'question_answer_table_setup');

question_answer_setup();
// Hook for adding admin menus
add_action('admin_menu', 'question_answer_add_pages');
add_filter('the_content', 'question_answer_userpage');

function question_answer_setup(){
   global $question_answer_domain, $question_answer_is_setup;

   if($question_answer_is_setup) {
      return;
   } 

   load_plugin_textdomain($question_answer_domain, 'wp-content/plugins/question_answer/languages');
}

function question_answer_add_pages(){
	// Add a new submenu under Manage:
    add_management_page('QA', 'QA', 8, 'qamanage', 'question_answer_manage_page');

}

function question_answer_manage_page(){
	global $wpdb, $question_answer_domain;
	require_once('question_answer_admin.inc.php');
}

function question_answer_userpage($content){
	global $wpdb;
	if(preg_match("/(.*)<([0-9]*)qaplugin>(.*)/", $content, $hits)){
		require_once('question_answer_user.inc.php');
		return $content;
	}else {
		return $content;
	}
}

function question_answer_table_setup(){
	global $wpdb, $question_answer_db_version, $question_table_name, $answer_table_name;

   if($wpdb->get_var("SHOW TABLES LIKE '$question_table_name'") != $question_table_name && $wpdb->get_var("SHOW TABLES LIKE '$answer_table_name'") != $answer_table_name) {
	   	$sql = "CREATE TABLE " . $question_table_name . " (
		  id mediumint(9) NOT NULL AUTO_INCREMENT,
		  time bigint(11) DEFAULT '0' NOT NULL,
		  title tinytext NOT NULL,
		  question text NOT NULL,
		  content text NOT NULL,
		  UNIQUE KEY id (id)
		);";
	   	
	   	$sql2 = "CREATE TABLE " . $answer_table_name . " (
		  id mediumint(9) NOT NULL AUTO_INCREMENT,
		  time bigint(11) DEFAULT '0' NOT NULL,
		  answer tinytext NOT NULL,
		  question_id mediumint(9) NOT NULL,
		  question_to_id mediumint(9) NOT NULL,
		  UNIQUE KEY id (id)
		);";
	
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
		dbDelta($sql2);
		add_option("question_answer_db_version", $question_answer_db_version);
   }
}
?>