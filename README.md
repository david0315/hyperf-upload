# hyperf-upload
hyperf-upload 是hyperf框架文件上传包,可本地传，可oss上传，显示,删除。 目前支持阿里oss 与 七牛oss

## 基本使用

1、 下载包
```bash
composer require wll/hyperf-upload

//如果要用到oss上传可选择安装
composer require qiniu/php-sdk
composer require aliyuncs/oss-sdk-php

```

2、 发布配置生成文件:config/autoload/filestore.php
```bash
bin/hyperf.php vendor:publish hyperf-upload
```

3、 开始使用 - 授权控制器中写
```bash
config/autoload/dependencies.php
修改绑定
'dependencies' => [
	Wll\HyperfUpload\Service\FileStoreInterface::class =>  Wll\HyperfUpload\Service\LocalFileStoreService::class,//默认使用本地上传
	//Wll\HyperfUpload\Service\FileStoreInterface::class => Wll\HyperfUpload\Service\AliyunFileStoreService::class,//阿里oss 
	//Wll\HyperfUpload\Service\FileStoreInterface::class => Wll\HyperfUpload\Service\QiniuFileStoreService::class,//七牛oss 	
],
```

4、 开始使用 - 授权控制器中写
```bash

use Hyperf\Utils\ApplicationContext;
use Wll\HyperfUpload\Service\FileStoreInterface;

 public function index(){	
 
   $container = ApplicationContext::getContainer();
   $upload=$container->get(FileStoreInterface::class); //获得上传对象
   
	//返回文件名+扩展名
	$path = $upload->store($request->file('file'));
	
	print_r($path);
	
	//获取可以访问的url
	echo "http的访问url".$upload->url($path);  
	
	
	//删除 
	$a=$upload->delete(['images/43704c244ee5fc5dcb83402f88f33144.jpg']);
	
	if($a){
		echo 'dd';
	}else{
		echo 'ccc';
	}			
   
   	
}

```
