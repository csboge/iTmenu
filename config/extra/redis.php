<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

return [
   
        // Optional. Default = '127.0.0.1:6379'. You can use 'unix:///tmp/redis.sock'
        'server' => '127.0.0.1:6379',

        // Optional. Default = 1
        'timeout' => 2,

        // Optional. Specify version to avoid some unexpected errors.
        'version' => '2.8.24',

        // Optional. Use it only if Redis server requires password (AUTH)
        //'password' => '',

        // Optional. Use it, if you want to select not default db (db != 0) on connect
        'database' => 0,

        // Optional. Array with configs for RedisCluster support
        'cluster' => [
            'enabled' => false,

            // Optional. Default = []. Map of cluster slots and servers
            // array(max_slot => server [, ...])
            // Examples for Cluster with 3 Nodes:
            'clusters' => [
                5460  => '1127.0.0.1:6379', // slots from 0 to 5460
                10922 => '127.0.0.1:6379', // slots from 5461 to 10922
                16383 => '127.0.0.1:6379', // slots from 10923 to 16383
            ],

            // Optional. Default = false.
            // Use the param to update cluster slot map below on init RedisClient.
            // RedisClient will execute command CLUSTER SLOTS to get map.
            'init_on_start' => false,

            // Optional. Default = false.
            // If Redis returns error -MOVED then RedisClient will execute
            // command CLUSTER SLOTS to update cluster slot map
            'init_on_error_moved' => true,

            // Optional. Defatult = 0.25 sec. It is timeout before next attempt on TRYAGAIN error.
            'timeout_on_error_tryagain' => 0.25, // sec
        ]
];
