<?php

$cfg->mod = new stdClass();
$cfg->mod->name = "Example";
$cfg->mod->folder = "mod-example";
$cfg->mod->path = "{$cfg->system->path_bo}/modules/{$cfg->mod->folder}/";
$cfg->mod->version = "0.0.1";
$cfg->mod->developer = "Carlos Santos";
$cfg->mod->contact = "carlos@nexus.pt";
$cfg->mod->install = TRUE;
$cfg->mod->dbTables = ["example"];


if (functions::dbTableExists($cfg->mod->dbTables) == FALSE) {
	$a = "install";
}

/* action controller */
if ($a == null && $a != "install") {
	// if action doesn't exist, system sent you to module homepage
	include sprintf("modules/%s/actions/home.php", $cfg->mod->folder);
} else {
	$pg_file = sprintf("modules/%s/actions/%s.php", $cfg->mod->folder, $a);
	if (file_exists($pg_file)) {
		// if exist an action response
		include $pg_file;
	} else {
		// if doesn't exist an action response, system sent you to 404
		header(
			sprintf(
				"Location: %s/0/%s/404/",
				$cfg->system->path_bo, $lg_s
			)
		);
	}
}
