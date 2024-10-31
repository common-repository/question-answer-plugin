<?php
/*  Copyright 2008  Dennis Suchomsky  (email : dennis@webschreinerei.de)

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

	if ($_POST['question_id'] === '0') {
		$wpdb->query("INSERT INTO `".$wpdb->prefix."question_answer_questions` (`question`, `title`, `content`)VALUES('".$wpdb->escape($_POST['question'])."', '".$wpdb->escape($_POST['title'])."', '".$wpdb->escape($_POST['content'])."')");
		?>
		<div class="updated"><p><strong><?php _e('New question added', $question_answer_domain ); ?></strong></p></div>
		<?php
	}
	
	if ($_POST['question_id'] > 0) {
		$wpdb->query("UPDATE `".$wpdb->prefix."question_answer_questions` SET question='".$wpdb->escape($_POST['question'])."', title='".$wpdb->escape($_POST['title'])."', content='".$wpdb->escape($_POST['content'])."' WHERE id='".$wpdb->escape($_POST['question_id'])."' ");
		?>
		<div class="updated"><p><strong><?php _e('Question updated', $question_answer_domain ); ?></strong></p></div>
		<?php
	}
	
	if ($_GET['delete_question'] > 0) {
		$wpdb->query("DELETE FROM ".$wpdb->prefix."question_answer_questions WHERE id=".$wpdb->escape($_GET['delete_question']));
		$wpdb->query("DELETE FROM ".$wpdb->prefix."question_answer_answers WHERE question_id=".$wpdb->escape($_GET['delete_question']));
		?><div class="updated"><p><strong><?php _e('Question deleted', $question_answer_domain ); ?></strong></p></div><?php
	}
	
	
	if ($_POST['answer_id'] === '0') {
		$wpdb->query("INSERT INTO `".$wpdb->prefix."question_answer_answers` (`answer`, `question_to_id`, `question_id`)VALUES('".$wpdb->escape($_POST['answer'])."', '".$wpdb->escape($_POST['question_to_id'])."', '".$wpdb->escape($_POST['answer_question_id'])."') ");
		?>
		<div class="updated"><p><strong><?php _e('New answer added', $question_answer_domain ); ?></strong></p></div>
		<?php
	}
	
	if ($_GET['delete_answer'] > 0) {
		$wpdb->query("DELETE FROM ".$wpdb->prefix."question_answer_answers WHERE id=".$wpdb->escape($_GET['delete_answer']));
		?><div class="updated"><p><strong><?php _e('Answer deleted', $question_answer_domain ); ?></strong></p></div><?php
	}
	
	if ($_POST['answer_id'] > 0) {
		$wpdb->query("UPDATE `".$wpdb->prefix."question_answer_answers` SET answer='".$wpdb->escape($_POST['answer'])."', question_to_id='".$wpdb->escape($_POST['question_to_id'])."' WHERE id='".$wpdb->escape($_POST['answer_id'])."' ");
		?>
		<div class="updated"><p><strong><?php _e('Answer updated', $question_answer_domain ); ?></strong></p></div>
		<?php
	}
?>

<?php

    // Now display the options editing screen
    echo '<div class="wrap">';
    // header
    echo "<h2>" . __( 'Question answer options', $question_answer_domain ) . "</h2>";
?>
<form method="post" action="<?php echo str_replace( '%7E', '~', preg_replace('/\?.*/','', $_SERVER['REQUEST_URI'])); ?>?page=qamanage" enctype="multipart/form-data">
	<b><?php _e('Add Question', $question_answer_domain ); ?></b><br /><input type="text" name="question" size="70" value=""><br />
	<?php _e('Title', $question_answer_domain ); ?>:<br /><input type="text" name="title" size="70" value=""><br />
	<?php _e('Content', $question_answer_domain ); ?>:<br /><textarea name="content" rows="3" cols="70"></textarea><br />
	<input type="submit" value="Add">
	<input type="hidden" name="question_id" value="0">
	</form>
<h3><?php _e('Questions', $question_answer_domain ); ?></h3>
<?php
$question_res = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."question_answer_questions WHERE 1 order by id ASC");
$question_res_answers = $question_res;

foreach ($question_res as $question) {
	?>
	<form method="post" action="<?php echo str_replace( '%7E', '~', preg_replace('/\?.*/','', $_SERVER['REQUEST_URI'])); ?>?page=qamanage" enctype="multipart/form-data">
	<b><?php echo $question->id ?>.&nbsp;</b><br /><input type="text" name="question" size="70" value="<?php echo $question->question ?>"><br />
	<?php _e('Title', $question_answer_domain ); ?>:<br /><input type="text" name="title" size="70" value="<?php echo $question->title ?>"><br />
	<?php _e('Content', $question_answer_domain ); ?>:<br /><textarea name="content" rows="3" cols="70"><?php echo $question->content ?></textarea><br />
	<input type="submit" value="Update"><a href="<?php echo str_replace( '%7E', '~', preg_replace('/\?.*/','',$_SERVER['REQUEST_URI'])); ?>?page=qamanage&amp;delete_question=<?php echo $question->id ?>">Delete</a><br /><br />
	<input type="hidden" name="question_id" value="<?php echo $question->id ?>">
	</form>
	<form method="post" action="<?php echo str_replace( '%7E', '~', preg_replace('/\?.*/','', $_SERVER['REQUEST_URI'])); ?>?page=qamanage" enctype="multipart/form-data">
	<?php _e('Add answer', $question_answer_domain ); ?>:<br /><input type="text" name="answer" size="50" value=""> <?php _e('Leads to', $question_answer_domain ); ?>:
	<select name="question_to_id">
			<?php foreach ($question_res_answers as $question_answer){ ?>
			<option value="<?php echo $question_answer->id; ?>"><?php echo $question_answer->id; ?>. <? echo $question_answer->question; ?></option>
			<?php } ?>
	</select>
	<input type="submit" value="Add"><br />
	<input type="hidden" name="answer_id" value="0">
	<input type="hidden" name="answer_question_id" value="<?php echo $question->id ?>">
	</form>
	
	<?php
	$answer_res = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."question_answer_answers WHERE question_id='".$wpdb->escape($question->id)."' order by id ASC");
	foreach ($answer_res as $answer){
		?>
		<form method="post" action="<?php echo str_replace( '%7E', '~', preg_replace('/\?.*/','', $_SERVER['REQUEST_URI'])); ?>?page=qamanage" enctype="multipart/form-data">
		<input type="text" name="answer" size="50" value="<?php echo $answer->answer ?>">
		<?php _e('Leads to', $question_answer_domain ); ?>:
		
		<select name="question_to_id">
			<?php foreach ($question_res_answers as $question_answer){ ?>
			<option <?php if ($question_answer->id == $answer->question_to_id) {echo "selected";} ?> value="<?php echo $question_answer->id; ?>" ><?php echo $question_answer->id; ?>. <?php echo $question_answer->question; ?></option>
			<?php } ?>
		</select>
		<input type="submit" value="Update"><a href="<?php echo str_replace( '%7E', '~', preg_replace('/\?.*/','', $_SERVER['REQUEST_URI'])); ?>?page=qamanage&amp;delete_answer=<?php echo $answer->id ?>">Delete</a><br />
		<input type="hidden" name="answer_id" value="<?php echo $answer->id ?>">
		</form>
		<?php
	}
	echo "<br /><br />";
}
?>
</div>