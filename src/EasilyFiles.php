<?php
/**
 * Class EasilyFiles
 * @package Easily
 * @author Andrew Esteves <easily@andrewesteves.com.br>
 */

namespace Easily;

class EasilyFiles
{
	/**
	 * Upload
	 *
	 * @param array file
	 */
	public function upload($file)
	{
		$filename = explode('.', $file['name']);
		$ext = end($filename);
		$filename = md5(uniqid("")) . '.' . $ext;
		$filepath = APP_DIR . 'public'. DS . 'files' . DS . $filename;
		$path = APP_DIR . 'public'. DS . 'files' . DS;
		
		if(move_uploaded_file($file['tmp_name'], $filepath)) {
			return [
				'image' => [
					'path' => $filepath,
					'name' => $filename,
					'link' => APP_URL . 'public/files/' . $filename
				]
			];
		}else{
			return false;
		}
	}

	/**
	 * Resize method
	 *
	 * @param array file
	 * @param int maxWidth
	 * @param int maxHeight
	 * @param int quality
	 * @param string avatar
	 */
	public function resize($file, $maxWidth, $maxHeight, $quality = 60, $avatar = 'thumb')
	{
		$filename = explode('.', $file['name']);
		$ext = end($filename);
		$filename = md5(uniqid("")) . '.' . $ext;
		$filepath = APP_DIR . 'public'. DS . 'files' . DS . $filename;
		$path = APP_DIR . 'public'. DS . 'files' . DS;

		if(isset($file)) {
			if(move_uploaded_file($file['tmp_name'], $filepath)) {
				list($width, $height) = getimagesize($filepath);
				
				$scale = $width / $height;

				if($maxWidth / $maxHeight > $scale) {
					$maxWidth  = $maxHeight * $scale;
				}else{
					$maxHeight = $maxWidth / $scale;
				}

				$img = "";
				switch ($ext) {
					case 'gif':
						$img = imagecreatefromgif($filepath);
						break;
					case 'png':
						$img = imagecreatefrompng($filepath);
						break;
					default:
						$img = imagecreatefromjpeg($filepath);
						break;
				}

				$newImage = $path . $avatar . '_' . $filename;

				$fileSource = imagecreatetruecolor($maxWidth, $maxHeight);

				imagecopyresampled($fileSource, $img, 0, 0, 0, 0, $maxWidth, $maxHeight, $width, $height);
				imagejpeg($fileSource, $newImage, 80);

				return [
					'image' => [
						'path' => $filepath,
						'name' => $filename,
						'link' => APP_URL . 'public/files/' . $filename
					]
				];
			}
		}
	}
}