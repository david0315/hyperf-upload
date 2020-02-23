<?php
declare(strict_types=1);
namespace Wll\HyperfUpload\Service;
use Hyperf\HttpMessage\Upload\UploadedFile;
use Qiniu\Storage\UploadManager;
use Qiniu\Storage\BucketManager;
use Qiniu\Auth;
use Qiniu\Config;

//七牛oss上传
class QiniuFileStoreService implements FileStoreInterface
{
    private $config;
    private $client = null;	
   
    public function __construct()
    {
        $this->config = config('filestore.qiniu');
        if( $this->client == null)  $this->client = new Auth($this->config['access_key'], $this->config['secret_key']);//授权key	
    }	
	
	//上传保存
    public function store($file)
    {
        //作用：（1）判断一个对象是否是某个类的实例，（2）判断一个对象是否实现了某个接口。
		if(! $file instanceof  UploadedFile)  throw new \RuntimeException('文件必须是 Hyperf\HttpMessage\Upload\UploadedFile');
				
		//获得上传token
		$token = $this->client->uploadToken($this->config['bucket']);  
		
		//获取将要上传的：文件名+扩展名
        $key = $this->savePath($file);
		
		//获取上传对象
		$uploadManager = new UploadManager();
		
		//上传
        list($ret, $err) = $uploadManager->putFile($token,$key,$file->getPathname());//putFile($token, $key, $filePath);
			
		//成功时返回:文件名+扩展名
		if (!$err) return $ret['key'];		
    }


    //获取可以访问的url
    public function url($path)
    {
        return $this->config['domain'] . $path;
    }	
	
	//删除
	public function delete($keys)
    {        
		//初始化 BucketManager 对象并进行文件的删除。
		$config = new Config();				
		$bucketManager = new BucketManager($this->client, $config);
		
		//删除
		if(is_array($keys)){//如果是数组			
			$ops = $bucketManager->buildBatchDelete($this->config['bucket'], $keys);// 调用 BucketManager 的 buildBatchDelete 方法进行文件的删除。
			list($ret, $err) = $bucketManager->batch($ops);			
			if (!$err) return true;				
		}elseif(is_string($keys)){//如果是字符串			
			list($ret, $err) = $bucketManager->delete($this->config['bucket'], $keys);// 调用 BucketManager 的 buildBatchDelete 方法进行文件的删除。
			if (!$err) return true;		
		}
		
		//默认返回
		return false;		
    }

	
    public function getClient()
    {
        return $this->client;
    }	
	
	//获取文件名+扩展名
    private function savePath($file)
    {	
		//md5_file 计算文件的 MD5 散列,作用是可检测是否已被更改
        $filename = md5_file($file->getPathname()).'.'.$file->getExtension();
        return  $this->config['save_path'].$filename;
        
    }	

}
?>