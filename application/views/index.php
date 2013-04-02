<?php 
	foreach($content as $post) {
		echo '<table class="default" width="90%">';
		echo '<tr>';
		echo '<th>'.$post->title.'</th>';
		echo '</tr>';
		echo '<tr>';
		echo '<td>';
		echo $post->text;
		echo '<hr>';
		echo '<div class="article-footer">Posted by <a>'.$post->author.'</a>	<div class="date">'.date('jS \of F Y', $post->date).'</div></div>';
		echo '</td>';
		echo '</tr>';
		echo '</table>';
	}
?>