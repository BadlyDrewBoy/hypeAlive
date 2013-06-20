<?php

$guids = hj_framework_process_file_upload('attachments');

if (elgg_is_xhr()) {
	print json_encode($guids);
}

forward();