<?php

	/*print_r($_FILES);*/
	$count_file = 0;
	foreach ($_FILES["uploadit"]['tmp_name'] as $tmp_name) {
		move_uploaded_file($tmp_name, 'xhr_upload/'.$_FILES["uploadit"]["name"][$count_file]);
		$count_file++;
	}
	