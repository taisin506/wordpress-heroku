<?php

add_action('madara_chapter_reading_actions_list_items', 'madara_chapter_reading_actions_add_darkmode_button');

function madara_chapter_reading_actions_add_darkmode_button(){
	?>
	<li><a href="#" class="wp-manga-action-button" data-action="toggle-contrast" title="<?php esc_html_e('Toggle Dark/Light Mode', 'madara');?>"><i class="icon ion-contrast"></i></a></li>
	<?php
}