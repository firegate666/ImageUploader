<?php
declare(strict_types=1);

namespace firegate666\ImageUploader;

use RuntimeException;

class FileUploader {

	/** @var array */
	protected array $uploaded_files;

	/**
	 * @param array $uploaded_files input from $_FILES
	 */
	public function __construct(array $uploaded_files) {
		$this->uploaded_files = $uploaded_files;
	}

	/**
	 * @param string $input_field_name
	 * @param string $destination
	 * @return bool
	 */
	public function moveUploadedFile(string $input_field_name, string $destination): bool {
		$tmp_name = $this->getTmpName($input_field_name);
		return $this->moveUploadedFile($tmp_name, $destination);
	}

	/**
	 * @param string $input_field_name
	 * @throws RuntimeException
	 * @return array ["name" => string, "type" => string, "tmp_name" => string, "error" => int, "size" => int]
	 */
	public function getUploadedFileData(string $input_field_name): array {
		$this->validate($input_field_name);

		$uploaded_file_data = $this->uploaded_files[$input_field_name];
		return $uploaded_file_data;
	}

	/**
	 * @param string $input_field_name
	 * @return mixed
	 */
	public function getUploadedFileSize(string $input_field_name): int {
		$uploaded_file_data = $this->getUploadedFileData($input_field_name);
		return $uploaded_file_data['size'];
	}

	/**
	 * @param string $input_field_name
	 * @return mixed
	 */
	protected function getTmpName(string $input_field_name): string {
		$uploaded_file_data = $this->getUploadedFileData($input_field_name);
		return $uploaded_file_data['tmp_name'];
	}

	/**
	 * @param string $input_field_name
	 * @throws RuntimeException
	 * @return void
	 */
	public function validate(string $input_field_name): void {
		if (!array_key_exists($input_field_name, $this->uploaded_files)) {
			throw new RuntimeException("No file was uploaded", UPLOAD_ERR_NO_FILE);
		}

		$uploaded_file_data = $this->uploaded_files[$input_field_name];

		if ($uploaded_file_data['error'] == UPLOAD_ERR_NO_FILE) {
			throw new RuntimeException("No file was uploaded", UPLOAD_ERR_NO_FILE);
		}

		if (empty($uploaded_file_data['tmp_name']) || empty($uploaded_file_data['size'])) {
			throw new RuntimeException('upload failed');
		}

		if (!file_exists($uploaded_file_data['tmp_name'])) {
			throw new RuntimeException('uploaded file corrupted');
		}

		if ($uploaded_file_data['error'] != 0) {
			switch ($this->uploaded_files['error']) {
				case UPLOAD_ERR_INI_SIZE:
					$message = "The uploaded file exceeds the upload_max_filesize directive in php.ini";
					break;
				case UPLOAD_ERR_FORM_SIZE:
					$message = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form";
					break;
				case UPLOAD_ERR_PARTIAL:
					$message = "The uploaded file was only partially uploaded";
					break;
				case UPLOAD_ERR_NO_TMP_DIR:
					$message = "Missing a temporary folder";
					break;
				case UPLOAD_ERR_CANT_WRITE:
					$message = "Failed to write file to disk";
					break;
				case UPLOAD_ERR_EXTENSION:
					$message = "File upload stopped by extension";
					break;

				default:
					$message = "Unknown upload error";
					break;
			}

			throw new RuntimeException($message, $uploaded_file_data['error']);
		}
	}
}
