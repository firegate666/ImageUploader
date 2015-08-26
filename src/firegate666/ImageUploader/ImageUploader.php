<?php

namespace firegate666\ImageUploader;

use RuntimeException;

/**
 * @link https://gist.github.com/jasny/3938108
 */
class ImageUploader extends FileUploader {

	/**
	 * @param string $input_field_name
	 * @return string
	 */
	public function getMimeType($input_field_name) {
		return $this->getImageData($input_field_name)['mime'];
	}

	/**
	 * @param string $input_field_name
	 * @return int
	 */
	public function getWidth($input_field_name) {
		return $this->getImageData($input_field_name)['width'];
	}

	/**
	 * @param string $input_field_name
	 * @return int
	 */
	public function getHeight($input_field_name) {
		return $this->getImageData($input_field_name)['height'];
	}

	/**
	 * @param string $input_field_name
	 * @throws RuntimeException
	 * @return string
	 */
	public function getBase64EncodedImageData($input_field_name) {
		$image_data = $this->getImageData($input_field_name);

		// base64 encode the binary data, then break it into chunks according to RFC 2045 semantics
		$base64 = chunk_split(base64_encode($image_data['content']));
		return 'data:' . $image_data['mime'] . ';base64,' . "\n" . $base64;
	}

	/**
	 * @param string $input_field_name
	 * @throws RuntimeException
	 * @return array ["filename" => string, "content" => string, "width" => int, "height" => int]
	 */
	protected function getImageData($input_field_name) {
		$filename = $this->getTmpName($input_field_name);
		$size_data = getimagesize($filename);

		if ($size_data === false) {
			throw new RuntimeException('Invalid image uploaded');
		}

		return array_merge(
			[
				'filename' => $this->getTmpName($input_field_name),
				'content' => file_get_contents($filename),
				'width' => $size_data[0],
				'height' => $size_data[1]
			],
			$size_data
		);
	}
}
