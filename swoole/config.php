<?php

return [

  'mysql' => [
    'host'     => '127.0.0.1',
    'port'     => 3306,
    'user'     => '',
    'password' => '',
    'database' => '',
  ],

  'redis' => [
    'REDIS_HOST' => '127.0.0.1',
    'REDIS_AUTH' => '',
    'REDIS_PORT' => '6379',
    'REDIS_SELECT' => 
  ],

  'websocket' => [
    'host' => '0.0.0.0',
    'post' => 9500,
    'option' => [
      'reactor_num'   => 16,
      'worker_num' => 16,
      // 'daemonize'  => true,
      'max_request' => 1000,
      'dispatch_mode' => 2,
      'debug_mode' => 1,
      'log_file' => __DIR__ . '/error.log',
      //心跳检测
      'heartbeat_check_interval' => 60,
      'heartbeat_idle_time' => 600,
      //wss证书
      'ssl_cert_file' => '/www/server/panel/vhost/cert/kyhospital.sdwanyue.com/fullchain.pem',
      'ssl_key_file' => '/www/server/panel/vhost/cert/kyhospital.sdwanyue.com/privkey.pem',
    ]
  ]

];
