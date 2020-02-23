<?php
declare(strict_types=1);
namespace Wll\HyperfUpload\Service;
use Hyperf\HttpMessage\Upload\UploadedFile;

class LocalFileStoreService implements FileStoreInterface
{
    private $config;
	
    public function __construct()
    {
        $this->config = config('filestore.local');
    }
		
	//上传保存
    public function store($file)
    {
		//作用：（1）判断一个对象是否是某个类的实例，（2）判断一个对象是否实现了某个接口。
        if(! $file instanceof  UploadedFile)  throw new \RuntimeException('文件必须是 Hyperf\HttpMessage\Upload\UploadedFile');
		
		//获取绝对路径+文件名+扩展名
        $saveFilePath = $this->savePath($file);
		
		//检查文件是否存在
        if($this->fileExists($saveFilePath))  return $saveFilePath;

		//检查文件夾是否存在-不存在以配置文件中的目录创建文件夹
        if(! $this->folderExists($this->config['save_path']))  $this->mkdir($this->config['save_path']); 
				
		//保存文件
		$file->moveTo($saveFilePath);		
		
		//只返回文件名+扩展名
        return str_replace($this->config['save_path'],'',$saveFilePath);
    }

	//获取可以访问的url
    public function url($path)
    {
        return $this->config['url'] . $path;
    }	
	
	//删除
	public function delete($file)
    {
		if(is_array($file)){//如果是数组
			$isOk=0;
			foreach($file as $val){				
				$savePath=$this->config['save_path'].$val;
				if($this->fileExists($savePath)) unlink($savePath) ? $isOk=1 : '';//删除
			}
			return $isOk ? true : false;
		}elseif(is_string($file)){//如果是字符串
			$savePath=$this->config['save_path'].$file;
			if($this->fileExists($savePath)) return unlink($savePath);//删除	
			return false;		
		}
		
		//默认返回
		return false;	    
    }
	
	//获取绝对路径+文件名+扩展名
    private function savePath($file)
    {	
		//md5_file 计算文件的 MD5 散列,作用是可检测是否已被更改
        $filename = md5_file($file->getPathname()).'.'.$file->getExtension();
        return  $this->config['save_path'].$filename;
    }
			
	//创建文件夹
    private function mkdir($folder)
    {
        mkdir($folder,0777,true);
    }
	
	//检查文件夾是否存在
    private function folderExists($folder)
    {
        return is_dir($folder) ? true : false;
    }
	
	//检查文件是否存在
    private function fileExists($file)
    {
        return file_exists($file) ? true : false;
    }	


}