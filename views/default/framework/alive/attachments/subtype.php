<?php

$entity = elgg_extract('entity', $vars);

echo elgg_echo("item:object:{$entity->getSubtype()}");