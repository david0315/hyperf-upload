<?php
declare(strict_types=1);
namespace Wll\HyperfUpload\Service;

interface FileStoreInterface
{
    //初始化配置
    public function __construct();
	
	/*
     * 根据文件的md5保存
     *@return string 保存文件的路径
    */
    public function store($file);
	
	/*
     * 域名 + 文件保存路径
     * @return string 返回文件的完整路径
    */
    public function url($path);	
		
	/*
     * 删除
     * @return bool
    */
	public function delete($path);   	
    

}