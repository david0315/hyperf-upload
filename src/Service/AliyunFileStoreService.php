<?php
declare(strict_types=1);
namespace Wll\HyperfUpload\Service;
use Hyperf\HttpMessage\Upload\UploadedFile;
use OSS\OssClient;
use OSS\Core\OssException;

//阿里oss上传
class AliyunFileStoreService implements FileStoreInterface
{
    private $config;
    private $client = null;
    public function __construct()
    {
        $this->config = config('filestore.aliyun');
        if( $this->client == null)  $this->initClient();
    }
	
	//初始化
    public function initClient()
    {
        try {
            $this->client = new OssClient($this->config['access_id'], $this->config['access_key'], $this->config['endpoint']);
            $this->client->setTimeout( $this->config['socket_timeout']);
            $this->client->setConnectTimeout( $this->config['connection_timeout']);
        } catch (OssException $e) {
            throw new \RuntimeException('链接失败: '.$e->getMessage());
        }
    }

    public function store($file)
    {
        //作用：（1）判断一个对象是否是某个类的实例，（2）判断一个对象是否实现了某个接口。
		if(! $file instanceof  UploadedFile)  throw new \RuntimeException('文件必须是 Hyperf\HttpMessage\Upload\UploadedFile');
		
		//获取将要上传的：文件名+扩展名
        $saveFilePath = $this->savePath($file);
		
		//检查文件是否存在
        if( $this->fileExists($saveFilePath)) return $saveFilePath;       
		
		//上传	
		try{
			$this->client->uploadFile($this->config['bucket'],$saveFilePath,$file->getPathname());			
			return $saveFilePath;//返回			
		} catch(OssException $e) {				
			return;
		}					
    }
	
	//获取可以访问的url
    public function url($path)
    {
        return $this->config['url'] . $path;
    }
	
	//删除
	public function delete($keys)
    {
		//删除
		if(is_array($keys)){//如果是数组			
			try{			
				return $this->client->deleteObjects($this->config['bucket'], $keys);				
			} catch(OssException $e) {				
				return;
			}			
		}elseif(is_string($keys)){//如果是字符串			
			try{			
				return $this->client->deleteObject($this->config['bucket'], $keys);				
			} catch(OssException $e) {				
				return fales;
			}	
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
	
	//验证文件是否存在
	private function fileExists($file)
    {
        return $this->client->doesObjectExist($this->config['bucket'],$file)
            ? true : false;
    }
	
}

?>