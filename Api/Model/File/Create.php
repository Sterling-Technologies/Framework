<?php //-->
/*
 * This file is part of the Eden package.
 * (c) 2014-2016 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Api\Model\File;

use Api\Model\Base;
use Api\Model\Argument;
use Api\Model\Exception;

/**
 * Model Create
 *
 * @vendor Api
 */
class Create extends Base
{
	const INVALID_PARAMETERS = 'Invalid Parameters';
	const INVALID_EMPTY = 'Cannot be empty!';
	const INVALID_SET = 'Cannot be empty, if set';
	const INVALID_FLOAT = 'Should be a valid floating point';
	const INVALID_INTEGER = 'Should be a valid integer';
	const INVALID_NUMBER = 'Should be a valid number';
	const INVALID_BOOL = 'Should either be 0 or 1';
	const INVALID_SMALL = 'Should be between 0 and 9';
	const UNKNOWN_EXTENSION = 'unknown';
	const UNKNOWN_MIME = 'application/octet-stream';
	const IMAGE_ONLY = 'File must be an image';
	
	/**
	 * Returns errors if any
	 *
	 * @param array submitted item
	 * @return array error
	 */
	public function errors(array $item = array(), array $errors = array()) 
    {
		//prepare
		$item = $this->prepare($item);
		
		//REQUIRED
		
		// file_data			Required
		// OR
		// file_link			Required
		if(empty($item['file_data'])
		&& empty($item['file_link'])) {
			$errors['file_data'] = self::INVALID_EMPTY;
			$errors['file_link'] = self::INVALID_EMPTY;
		} else if($item['imageOnly'] && !empty($item['file_data'])) {
			$data = decodeURIComponent($item['file_data']);
		
			//data:mime;base64,data
			$data = substr($data, 5);
			
			$chunks = explode(';base64,', $data);
			$mime 	= array_shift($chunks);
			
			if(strpos($mime, 'image/') !== 0) {
				$errors['file_data'] = self::IMAGE_ONLY;
			}
		} else if($item['imageOnly'] && !empty($item['file_link'])) {
			$ext = array_pop(explode('.', $item['file_link']));
		
			$mime = $this->types[$ext] || '';
			
			if(mime.indexOf('image/') !== 0) {
				$errors['file_link'] = self::IMAGE_ONLY;
			}
		}
		
		//OPTIONAL
		
		// file_flag
		if(isset($item['file_flag']) 
		&& !$this->isSmall($item['file_flag'])) {
			$errors['file_flag'] = self::INVALID_SMALL;
		}
		
		return $errors;
	}
	
	/**
	 * Processes the form
	 *
	 * @param array item
	 * @return void
	 */
	public function process(array $item = array()) 
	{
		//prevent uncatchable error
		if(count($this->errors($item))) {
			throw new Exception(self::INVALID_PARAMETERS);
		}
		
		//prepare
		$item = $this->prepare($item);
		
		$config = control()->config('s3');
		
		
		if($item['file_data']) {
			//upload
			$meta = $this->upload($config, $item['file_data']);
			
			$item['file_link'] = implode('/', array(
					$config['host'], 
					$config['bucket'], 
					$meta['name'] + $meta['extension']));
				
				$item['file_mime'] = $meta['mime'];
				
				unset($item['file_data']);
				
				return $this->save($item);
		}
		
		//parse the file_link
		$ext = array_pop(explode('.', $item['file_link']));
		
		if(!$ext) {
			$ext = self::UNKNOWN_EXTENSION;
		}
		
		$item['file_mime'] = self::UNKNOWN_MIME;
		
		if(isset($this->types[$ext])) {
			$item['file_mime'] = $this->types[$ext];
		}
		
		return $this->save($item);
	}
	
	public function save($item) 
	{
		//generate dates
		$created = date('Y-m-d H:i:s');
		$updated = date('Y-m-d H:i:s');
		
		//SET WHAT WE KNOW
		$model = control()
			->database()
			->model()
			
			// file_link			Required
			->setFileLink($item['file_link'])
			// file_mime			Required
			->setFileMime($item['file_mime'])
			// file_created
			->setFileCreated($created)
			// file_updated
			->setFileUpdated($updated);
		
		// file_path
		if(isset($item['file_path'])) {
			$model->setFilePath($item['file_path']);
		}
		
		// file_flag
		if($this->isSmall($item['file_flag'])) {
			$model->setFileFlag($item['file_flag']);
		}
		
		// file_type
		if(isset($item['file_type'])) {
			$model->setFileType($item['file_type']);
		}
		
		//what's left ?
		$model->save('file');
		
		$this->trigger('file-create', $model);
		
		return $model;
	}
	
	/**
	 * Uploads an image given raw data and returns meta data
	 *
	 * @param string raw data
	 * @return object
	 */
	public function upload($config, $data) 
	{
		//save the file
		$name	= control()->uid();
		$path 	= $this->controller.path('upload') . '/' . name;
		
		$data = urldecode($data);
		//data:mime;base64,data
		$data = substr($data, 5);
		
		$chunks = explode(';base64,', $data);
		$mime 	= array_shift($chunks);
		$ext 	= '.' . self::UNKNOWN_EXTENSION;
		
		//find out the extension
		foreach($this->types as $key => $value) {
			if($value === $mime) {
				$ext = '.' . $key;
				break;
			}
		}
		
		$data = implode(';base64,', $chunks);
		
		//TODO
		$buffer	= new Buffer(data, 'base64');
		
		//the new way
		$AWS->config->update(array( 
			'accessKeyId' => $config['token'], 
			'secretAccessKey' => $config['secret'] ));
		
		$s3 = new S3();
		
		$response = $s3->upload(array(
			'Bucket' => $config['bucket'],
			'Key' => $name . $ext,
			'Body' => $buffer,
			'ContentType' => $mime, 
			'ACL' => 'public-read'
		));
		
		return array( 'name' => $name, 'extension' => $ext, 'mime' => $mime );
	}
}