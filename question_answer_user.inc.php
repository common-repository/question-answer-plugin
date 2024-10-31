<?php
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

ob_start();
if ($_GET['question_id'] > 0) {
	$hits[2] = $_GET['question_id'];
}

$question_res = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."question_answer_questions WHERE id=".$wpdb->escape($hits[2])." ");
echo ($question_res[0]->question != '') ?  "<h2>".$question_res[0]->question."</h2><br /><br />" :  "";
echo ($question_res[0]->title != '') ? "<h3>".$question_res[0]->title."</h3><br /><br />" : "";

$answer_res = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."question_answer_answers WHERE question_id='".$wpdb->escape($hits[2])."' order by answer ASC");
	foreach ($answer_res as $answer){
		echo "<a href=\"".str_replace( '%7E', '~', preg_replace('/\?.*/','', $_SERVER['REQUEST_URI']))."?question_id=".$answer->question_to_id."\"><h3>".$answer->answer."</h3></a><br />";
	}
	

echo $question_res[0]->content;
$content = ob_get_clean();
?>