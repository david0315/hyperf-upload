<?php

declare(strict_types=1);

namespace Wll\Hash;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
            ],
            'commands' => [
            ],
            'listeners' => [],
            // 合并到  config/autoload/annotations.php 文件
            'annotations' => [
                'scan' => [
                    'paths' => [
                        __DIR__,
                    ],
                ],
            ],
            'publish' => [
                [
                    'id' => 'file-upload',
                    'description' => 'hyperf-file-upload',
                    'source' => __DIR__ . '/../publish/filestore.php',
                    'destination' => BASE_PATH . '/config/autoload/filestore.php',
                ],
            ],
        ];
    }
}
