<?php

class file {
	protected $id;
	protected $file;
	protected $type;
	protected $module;
	protected $id_ass;
	protected $date;
	protected $date_update;


	public function __construct() {}

	public function setId ($i) {
		$this->id = $i;
	}

	public function setFile ($f) {
		$this->file = $f;
	}

	public function setType ($t) {
		switch ($t) {
			case 'img':
				$this->type = "img";
				break;
			case 'doc':
				$this->type = "doc";
				break;
			default:
				$this->type = "img";
				break;
		}
	}

	public function setModule ($m) {
		$this->module = $m;
	}

	public function setIdAss ($ia) {
		$this->id_ass = $ia;
	}

	public function setDescription ($d) {
		$this->description = $d;
	}

	public function setDate($d = null) {
		$this->date = ($d !== null) ? $d : date("Y-m-d H:i:s", time());
	}

	public function setDateUpdate($d = null) {
		$this->date_update = ($d !== null) ? $d : date("Y-m-d H:i:s", time());
	}

	public function insert () {
		global $cfg, $mysqli;

		$query = sprintf(
			"INSERT INTO %s_files (file, type, module, id_ass, date, date_update) VALUES ('%s', '%s', '%s', '%s', '%s', '%s')",
			$cfg->db->prefix,
			$this->file,
			$this->type,
			$this->module,
			$this->id_ass,
			$this->date,
			$this->date_update
		);

		$toReturn = $mysqli->query($query);

		$this->id = $mysqli->insert_id;

		return $toReturn;
	}

	public function update () {
		global $cfg, $mysqli;

		$query = sprintf(
			"UPDATE %s_users SET file = '%s', type = '%s', module = '%s', id_ass = '%s', date = '%s', date_update = '%s' WHERE id = '%s'",
			$cfg->db->prefix,
			$this->file,
			$this->type,
			$this->module,
			$this->id_ass,
			$this->date,
			$this->date_update,
			$this->id
		);

		return $mysqli->query($query);
	}

	public function delete () {
		global $cfg, $mysqli, $authData;

		$file = new file();
		$file->setId($this->id);
		$file = $file->returnOneFile();

		$trash = new trash();
		$trash->setCode(json_encode($file));
		$trash->setDate();
		$trash->setModule($cfg->mdl->folder);
		$trash->setUser($authData["id"]);
		$trash->insert();

		unset($user);

		$query = sprintf(
			"DELETE FROM %s_users WHERE id = '%s'",
			$cfg->db->prefix,
			$this->id
		);

		return $mysqli->query($query);
	}

	public function returnFiles () {
		global $cfg, $mysqli;

		$query = sprintf(
			"SELECT * FROM %s_files WHERE %s",
			$cfg->db->prefix,
			(!empty($this->id_ass)) ? "id_ass = {$this->id_ass}" : null,
			(!empty($this->id_ass)) ? " AND " : null.
			(!empty($this->module)) ? "module = {$this->module}" : null
		);

		$source = $mysqli->query($query);

		if ($source->num_rows > 1) {
			while ($data = $source->fetch_object()) {
				if (!isset($toReturn)) {
					$toReturn = [];
				}
				array_push($toReturn, $data);
			}
		} else {
			return $source->fetch_object();
		}

		return FALSE;
	}

	public function returnFilesByModule () {}
}
