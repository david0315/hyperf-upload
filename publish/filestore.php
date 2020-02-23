<?php

declare(strict_types=1);

return [

    'local' => [
        'save_path' => BASE_PATH.'/public/images/',
        'url'   => 'http://192.168.1.107:9501/images/' //修改成自己的地址
    ],

    'aliyun' => [			
		'access_id'  => env('OSS_ACCESS_ID','修改成自己的key'),
		'access_key' => env('OSS_ACCESS_KEY','修改成自己的acdess'),
		'bucket'     => env('OSS_BUCKET','修改成自己的bucket'),
		'endpoint'   => env('OSS_ENDPOINT','oss-cn-shenzhen.aliyuncs.com'),//your endpoint 地域节点		
		'socket_timeout' => '5184000', // 设置Socket层传输数据的超时时间
        'connection_timeout' => '10', //建立链接的超时时间		
        'save_path' => 'images/',  //存储目录
        'url'       =>  'https://diyyq.oss-cn-shenzhen.aliyuncs.com/'	 //修改成自己的地址	
    ],
	
	'qiniu' => [	      
	   'access_key' => env('QINIU_ACCESS_KEY', '修改成自己的key-jvEd9'),
	   'secret_key' => env('QINIU_SECRET_KEY', '修改成自己的acdess'),
	   'bucket'     => env('QINIU_BUCKET', '修改成自己的bucket'),
	   'save_path' 	=> 'images/',  //存储目录
	   'domain'     => env('QINIU_DOMAIN', 'http://images.diyyq.com/'), //修改成自己的地址
    ]
	
];