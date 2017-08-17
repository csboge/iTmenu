<?php
namespace app\core\traits;

use RedisClient\RedisClient;
use RedisClient\Client\Version\RedisClient2x6;
use RedisClient\ClientFactory;

use Elasticsearch\ClientBuilder;


//服务工厂
trait ProviderFactory
{

    // Redis 实例 
    public function redisFactory($database = 0, $conf = [])
    {
        $conf['database'] = intval($database);
        $config           = array_merge(config('redis'), $conf);

        // Example 1. Create new Instance for Redis version 2.8.x with config via factory
        $Redis = ClientFactory::create($config);

        return $Redis;
    }


    // ES 实例
    public function ESFactory()
    {
        $hosts  = config('elasticsearch.server');
        $es     = ClientBuilder::create()->setHosts($hosts)->build();

        return $es;
    }


    public function ESFactoryParams()
    {
        $params = array();  
        $params['index'] = 'paper_index'; 

        $params['body']['query']['match_phrase']['title'] = '2014年';
    }
}