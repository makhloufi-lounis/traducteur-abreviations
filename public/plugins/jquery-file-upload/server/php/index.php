<?php
/*
 * jQuery File Upload Plugin PHP Example 5.14 https://github.com/blueimp/jQuery-File-Upload Copyright 2010, Sebastian Tschan https://blueimp.net Licensed under the MIT license: http://www.opensource.org/licenses/MIT
 */
error_reporting ( E_ALL | E_STRICT );
require ('UploadHandler.php');
function getConfig() {
	return include '../../../../../config/autoload/doctrine.orm.local.php';
}
if ($_SERVER ['REQUEST_METHOD'] === 'POST') {
	$uniqId = $_REQUEST ['uniqId'];
	$upload_url = '/images/uploads/' . $uniqId . '/';
	$upload_dir = dirname ( $_SERVER ['DOCUMENT_ROOT'] ) . '/public/images/uploads/' . $uniqId . '/';
} else if ($_SERVER ['REQUEST_METHOD'] === 'GET') {
	$otherDir = trim ( $_GET ['otherDir'] );
	$upload_url = $otherDir;
	$upload_dir = dirname ( $_SERVER ['DOCUMENT_ROOT'] ) . '/public/' . $otherDir;
} else if ($_SERVER ['REQUEST_METHOD'] === 'DELETE') {
	$otherDir = trim ( $_REQUEST ['_url'] );
	$otherDir = substr ( $otherDir, 0, strrpos ( $otherDir, "/" ) ) . '/';
	$upload_url = $otherDir;
	$upload_dir = dirname ( $_SERVER ['DOCUMENT_ROOT'] ) . '/public/' . $otherDir;
}

$config = getConfig ();

$options = array (
		'upload_dir' => $upload_dir,
		'upload_url' => $upload_url,
		'delete_type' => 'DELETE',
		'db_host' => $config ['doctrine'] ['connection'] ['orm_default'] ['params'] ['host'],
		'db_user' => $config ['doctrine'] ['connection'] ['orm_default'] ['params'] ['user'],
		'db_pass' => $config ['doctrine'] ['connection'] ['orm_default'] ['params'] ['password'],
		'db_name' => $config ['doctrine'] ['connection'] ['orm_default'] ['params'] ['dbname'],
		'db_table' => 'photos' 
);
class CustomUploadHandler extends UploadHandler {
	protected function initialize() {
		$this->db = new mysqli ( $this->options ['db_host'], $this->options ['db_user'], $this->options ['db_pass'], $this->options ['db_name'] );
		parent::initialize ();
		$this->db->close ();
	}
	protected function handle_form_data($file, $index) {
		$uniqId = @$_REQUEST ['uniqId'];
		$file->uniqId = $uniqId;
	}
	protected function handle_file_upload($uploaded_file, $name, $size, $type, $error, $index = null, $content_range = null) {
		$file = parent::handle_file_upload ( $uploaded_file, $name, $size, $type, $error, $index, $content_range );
		if (empty ( $file->error )) {
			$sql = 'INSERT INTO `' . $this->options ['db_table'] . '` (`photo_name`,`uniq_id`,`photo_type`,`photo_size`)' . ' VALUES (?,?,?,?)';
			$query = $this->db->prepare ( $sql );
			$query->bind_param ( 'ssss', $file->name, $file->uniqId, $file->type, $file->size);
			$query->execute ();
			$file->id = $this->db->insert_id;
		}
		return $file;
	}
	protected function set_additional_file_properties($file) {
		parent::set_additional_file_properties ( $file );
		if ($_SERVER ['REQUEST_METHOD'] === 'GET') {
			$sql = 'SELECT `id`,`photo_role` FROM `' . $this->options ['db_table'] . '` WHERE `photo_name`=?';
			$query = $this->db->prepare ( $sql );
			$query->bind_param ( 's', $file->name );
			$query->execute ();
			$query->bind_result ( $id, $role );
			while ( $query->fetch () ) {
				$file->id = $id;
				$file->role = $role;
			}
		}
	}
	public function delete($print_response = true) {
		$otherDir = trim ( $_REQUEST ['_url'] );
		$array = explode ( '/', $otherDir );
		$uniqId = $array [3];
		$response = parent::delete ( false );
		foreach ( $response as $name => $deleted ) {
			if ($deleted) {
				$sql = 'DELETE FROM `' . $this->options ['db_table'] . '` WHERE `photo_name`=? AND `uniq_id`=?';
				$query = $this->db->prepare ( $sql );
				$query->bind_param ( 'ss', $name, $uniqId );
				$query->execute ();
			}
		}
		return $this->generate_response ( $response, $print_response );
	}
}

$upload_handler = new CustomUploadHandler ( $options );
