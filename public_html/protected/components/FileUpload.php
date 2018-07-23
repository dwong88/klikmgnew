<?php
class FileUpload
{
	const PROPERTY_PHOTO_PATH = 1;
	const PROPERTY_PHOTO_THUMBS_PATH = 2;
	const ROOM_PHOTO_PATH = 3;
	const ROOM_PHOTO_THUMBS_PATH = 4;
  const HOTEL_CSV_PATH = 5;
	const ROOMCATEG_CSV_PATH = 6;

	/***
	 * Untuk dapatin file path untuk upload file. "C:\xampp\htdocs\del\upload\foto\file.ext"
	 * @param String $fileName filename dan extention dari file yang mau di upload
	 * @param String $constPath variable constant yang ada diatas
	 * @return String a Complete upload path for file upload.
	 */
	public static function getFilePath($fileName, $constPath)
	{
		$pathFile = '';
		switch($constPath) {
			case FileUpload::PROPERTY_PHOTO_PATH: // Untuk file Client_PO pada menu SO. (tdpso)
				$pathFile = Yii::app()->basePath.'/../upload/property_photo/'.$fileName;
				break;
			case FileUpload::PROPERTY_PHOTO_THUMBS_PATH: // Untuk file Client_PO pada menu SO. (tdpso)
				$pathFile = Yii::app()->basePath.'/../upload/property_photo/thumbs/'.$fileName;
				break;
			case FileUpload::ROOM_PHOTO_PATH: // Untuk file Client_PO pada menu SO. (tdpso)
				$pathFile = Yii::app()->basePath.'/../upload/room_photo/'.$fileName;
				break;
			case FileUpload::ROOM_PHOTO_THUMBS_PATH: // Untuk file Client_PO pada menu SO. (tdpso)
				$pathFile = Yii::app()->basePath.'/../upload/room_photo/thumbs/'.$fileName;
				break;
      case FileUpload::HOTEL_CSV_PATH: // Untuk file Client_PO pada menu SO. (tdpso)
				$pathFile = Yii::app()->basePath.'/../upload/syncdatamg/'.$fileName;
				break;
			case FileUpload::ROOMCATEG_CSV_PATH: // Untuk file Client_PO pada menu SO. (tdpso)
				$pathFile = Yii::app()->basePath.'/../upload/syncdatamg/roomcateg/'.$fileName;
			break;
	}
		return $pathFile;
	}

	/**
	 *
	 * Digunakan untuk memanggil ksh url dari target download.
	 * @param String $id
	 * @param Number $constPath
	 */
	public static function getHttpPath($id, $constPath)
	{
		$pathFile = '';
		switch($constPath) {
			case FileUpload::PROPERTY_PHOTO_PATH: // Untuk foto biodata
				$pathFile = CHtml::normalizeUrl(array('/download','id'=>$id,'const'=>$constPath));
				//Yii::app()->baseUrl.'/upload/client_po/'.$fileName;
				break;
			case FileUpload::PROPERTY_PHOTO_THUMBS_PATH: // Untuk foto biodata
				$pathFile = CHtml::normalizeUrl(array('/download','id'=>$id,'const'=>$constPath));
				//Yii::app()->baseUrl.'/upload/client_po/'.$fileName;
				break;
			case FileUpload::ROOM_PHOTO_THUMBS_PATH: // Untuk foto biodata
				$pathFile = CHtml::normalizeUrl(array('/download','id'=>$id,'const'=>$constPath));
				//Yii::app()->baseUrl.'/upload/client_po/'.$fileName;
				break;
			case FileUpload::ROOM_PHOTO_THUMBS_PATH: // Untuk foto biodata
				$pathFile = CHtml::normalizeUrl(array('/download','id'=>$id,'const'=>$constPath));
				//Yii::app()->baseUrl.'/upload/client_po/'.$fileName;
				break;
      case FileUpload::HOTEL_CSV_PATH: // Untuk foto biodata
				$pathFile = CHtml::normalizeUrl(array('/download','id'=>$id,'const'=>$constPath));
				//Yii::app()->baseUrl.'/upload/client_po/'.$fileName;
				break;
}

		return $pathFile;
	}
}
