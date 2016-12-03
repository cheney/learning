<?php

class PhpRedis
{
    const REDIS_NOT_FOUND = 0;
    const REDIS_STRING = 1;
    const REDIS_SET = 2;
    const REDIS_LIST = 3;
    const REDIS_ZSET = 4;
    const REDIS_HASH = 5;
    const AFTER = 'after';
    const BEFORE = 'before';

    protected $memberRedisKey;
    protected $memberRedisString;
    protected $memberRedisHash;
    protected $memberRedisList;
    protected $memberRedisSet;
    protected $memberRedisZSet;

    protected static $instance;
    private $redis = null;
    protected $inited = false;
    protected $serversConfig = false;

    public static function getInstance()
    {
        if (! self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    protected function checkInit()
    {
        if (!$this->inited) {
            $this->redis = new \Redis();
        //    $this->redis->connect($this->serversConfig['host'], $this->serversConfig['port']);
            $this->redis->connect($this->serversConfig['host'], $this->serversConfig['port'],isset($this->serversConfig['timeout'])?$this->serversConfig['timeout']:1);
            //!empty($this->serversConfig['dbindex'])&&$this->redis->select($this->serversConfig['dbindex']);
            !empty($this->serversConfig['dbPreFix']) && $this->redis->setOption(\Redis::OPT_PREFIX, $this->serversConfig['dbPreFix']);
            $this->memberRedisKey = new PhpRedisFunKeys($this->redis, $this->serversConfig);
            $this->memberRedisString = new PhpRedisFunStrings($this->redis, $this->serversConfig);
            $this->memberRedisHash = new PhpRedisFunHashes($this->redis, $this->serversConfig);
            $this->memberRedisList = new PhpRedisFunLists($this->redis, $this->serversConfig);
            $this->memberRedisSet = new PhpRedisFunSets($this->redis, $this->serversConfig);
            $this->memberRedisZSet = new PhpRedisFunSortedSets($this->redis, $this->serversConfig);
            $this->inited = true;
        }
    }
    /*
     *     array('host'=>, 'port'=>, 'dbindex'=>,'dbPreFix'=>),
     */
    public function init(array $servers)
    {
        $servers['host'] = isset($servers['host']) ? $servers['host'] : '127.0.0.1';
        $servers['port'] = isset($servers['port']) ? $servers['port'] : '6379';
        $servers['dbindex'] = isset($servers['dbindex']) ? intval($servers['dbindex']) : '';
        $servers['dbPreFix'] = isset($servers['dbPreFix']) ? $servers['dbPreFix'] : '';
        $this->serversConfig = $servers;

        return true;
    }

    public function Key()
    {
        $this->checkInit();

        return $this->memberRedisKey;
    }
    public function String()
    {
        $this->checkInit();

        return $this->memberRedisString;
    }
    public function Hash()
    {
        $this->checkInit();

        return $this->memberRedisHash;
    }
    public function Lists()
    {
        $this->checkInit();

        return $this->memberRedisList;
    }

    public function Set()
    {
        $this->checkInit();

        return $this->memberRedisSet;
    }

    public function ZSet()
    {
        $this->checkInit();

        return $this->memberRedisZSet;
    }
}

class redisBaseCall
{
    private $redisInstance;
    private $serversConfig;
    public function __construct($redisInstance, $serversConfig)
    {
        $this->redisInstance = $redisInstance;
        $this->serversConfig = $serversConfig;
    }

    /*
    *   集中调用redis方法，用于记录日志
    */
    protected function redisCall($fun, $parms)
    {
        $retrunVal = null;
        $retrunVal = call_user_func_array(array($this->redisInstance, $fun), $parms);
        return $retrunVal;
    }
}

class PhpRedisFunKeys extends redisBaseCall
{
    /**
     * @param string $key
     *
     * @return bool
     */
    public function exists($key)
    {
        return $this->redisCall('exists', func_get_args());
    }

    /**
     * @param Array $keyArr
     *
     * @return int
     */
    public function delete($keyArr)
    {
        return $this->redisCall('delete', func_get_args());
    }
    /**
     * @param string $key
     * @param int    $seconds
     *
     * @return bool
     */
    public function expire($key, $seconds)
    {
        return $this->redisCall('expire', func_get_args());
    }

    /**
     * @param string $key
     * @param int    $seconds
     *
     * @return bool
     */
    public function expireAt($key, $seconds)
    {
        return $this->redisCall('expireAt', func_get_args());
    }

    /**
     * @param string $key
     *
     * @return Array of STRING
     */
    public function getKeys($key)
    {
        return $this->redisCall('getKeys', func_get_args());
    }

    /**
     * @param string $srcKey,$dstKey
     *
     * @return bool
     */
    public function rename($srcKey, $dstKey)
    {
        return $this->redisCall('rename', func_get_args());
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function type($key)
    {
        return $this->redisCall('type', func_get_args());
    }

    /**
     * @param string $key     must be list, set or sorted set
     * @param array  $optArr(
     *                        'by' => 'some_pattern_*',
     *                        'limit' => array(0, 1),
     *                        'get' => 'some_other_pattern_*' or an array of patterns,
     *                        'sort' => 'asc' or 'desc',
     *                        'alpha' => TRUE,
     *                        'store' => 'external-key'
     *                        )
     *
     * @return mixed
     */
    public function sort($key, $optArr = array())
    {
        return $this->redisCall('sort', func_get_args());
    }
    /**
     * @param string $key
     *
     * @return int
     *             LONG: The time to live in seconds. If the key has no ttl, -1 will be returned, and -2 if the key doesn't exist.
     */
    public function ttl($key)
    {
        return $this->redisCall('ttl', func_get_args());
    }
}

class PhpRedisFunStrings extends redisBaseCall
{
    /**
     * @param string $key
     * @param string $value
     *
     * @return int
     */
    public function append($key, $value)
    {
        return $this->redisCall('append', func_get_args());
    }

    /**
     * @param string $key
     * @param string $value
     *
     * @return int
     */
    public function decrBy($key, $decrement = 1)
    {
        return $this->redisCall('decrBy', func_get_args());
    }

    /**
     * @param string $key
     * @param string $value
     *
     * @return int
     */
    public function incrBy($key, $increment = 1)
    {
        return $this->redisCall('incrBy', func_get_args());
    }

    /**
     * @param string $key
     *
     * @return String or Bool:
     */
    public function get($key)
    {
        return $this->redisCall('get', func_get_args());
    }

    /**
     * @param string $key
     *
     * @return String or Bool:
     */
    public function set($key, $value)
    {
        return $this->redisCall('set', func_get_args());
    }

    /**
     * @param Array $keyArr
     *
     * @return Array
     */
    public function mGet($keyArr)
    {
        return $this->redisCall('mGet', func_get_args());
    }

    /**
     * @param Array $keyValArr
     *
     * @return Bool
     */
    public function mSet($keyValArr)
    {
        return $this->redisCall('mset', func_get_args());
    }

    /**
     * @param Array $keyValArr
     *
     * @return Bool
     */
    public function setex($key, $value, $ttl = 604800)
    {
        return $this->redisCall('setex', array($key, $ttl, $value));
    }

    /**
     * @param string $key
     * @param int    $start
     * @param int    $end
     *
     * @return string
     */
    public function getRange($key, $start, $end)
    {
        return $this->redisCall('getRange', func_get_args());
    }

    /**
     * @param string $key
     * @param int    $start
     * @param int    $end
     *
     * @return int
     */
    public function setRange($key, $offset, $value)
    {
        return $this->redisCall('setRange', func_get_args());
    }

    /**
     * @param string $key
     *
     * @return int
     */
    public function strlen($key)
    {
        return $this->redisCall('strlen', func_get_args());
    }
}

class PhpRedisFunHashes extends redisBaseCall
{
    /**
     * @param string $key,$hashKey
     *
     * @return int
     */
    public function del($key, $hashKey)
    {
        return $this->redisCall('hDel', func_get_args());
    }

    /**
     * @param string $key,$hashKey
     *
     * @return int
     */
    public function set($key, $hashKey, $value)
    {
        return $this->redisCall('hSet', func_get_args());
    }

    /**
     * @param string $key,$hashKey
     *
     * @return int
     */
    public function exists($key, $hashKey)
    {
        return $this->redisCall('hExists', func_get_args());
    }

    /**
     * @param string $key,$hashKey
     *
     * @return bool string
     */
    public function get($key, $hashKey)
    {
        return $this->redisCall('hGet', func_get_args());
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function all($key)
    {
        return $this->redisCall('hGetAll', func_get_args());
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function incrBy($key, $hashKey, $increment = 1)
    {
        return $this->redisCall('hIncrBy', func_get_args());
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function keys($key)
    {
        return $this->redisCall('hKeys', func_get_args());
    }

    /**
     * @param string $key
     *
     * @return int
     */
    public function hashesLen($key)
    {
        return $this->redisCall('hLen', func_get_args());
    }

    /**
     * @param string $key
     * @param Array  $hashKeyArr
     *
     * @return mixed
     */
    public function mGet($key, $hashKeyArr)
    {
        return $this->redisCall('hMGet', func_get_args());
    }

    /**
     * @param string $key
     * @param Array  $hash
     *
     * @return bool
     */
    public function mSet($key, $hash)
    {
        return $this->redisCall('hMSet', func_get_args());
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function vals($key)
    {
        return $this->redisCall('hVals', func_get_args());
    }
}

class PhpRedisFunLists extends redisBaseCall
{
    /**
     * 按索引赋值.
     *
     * @param string $key
     * @param int    $index
     * @param $value
     *
     * @return bool
     */
    public function set($key, $index, $value)
    {
        return $this->redisCall('lSet', func_get_args());
    }

    /**
     * 按索引取值.
     *
     * @param string $key
     * @param int    $index
     *
     * @return bool or string
     */
    public function get($key, $index)
    {
        return $this->redisCall('lGet', func_get_args());
    }

    /**
     * Prepend one or multiple values to a list.
     *
     * @param string $key
     * @param $value
     *
     * @return int
     */
    public function push($key, $value)
    {
        return $this->redisCall('lPush', func_get_args());
    }

    /**
     * Prepend one or multiple values to a list
     * 队列不存在放回false.
     *
     * @param string $key
     * @param $value
     *
     * @return int
     */
    public function pushx($key, $value)
    {
        return $this->redisCall('lPushx', func_get_args());
    }

    /**
     * Append one or multiple values to a list.
     *
     * @param string $key
     * @param $value
     *
     * @return bool
     */
    public function rpush($key, $value)
    {
        return $this->redisCall('rPush', func_get_args());
    }

    /**
     * Append one or multiple values to a list.
     *
     * @param string $key
     * @param $value
     *
     * @return bool
     */
    public function rpushx($key, $value)
    {
        return $this->redisCall('rPushx', func_get_args());
    }

    /**
     * 在某值前后插入新值.
     *
     * @param string $key
     * @param $value
     *
     * @return bool
     */
    public function insert($key, $pointer, $value, $position = PhpRedis::AFTER)
    {
        return $this->redisCall('lInsert', array($key, $position, $pointer, $value));
    }

    /**
     * 查询列表范围.
     *
     * @param string $key
     * @param int    $begin
     * @param int    $end
     *
     * @return mixed
     */
    public function range($key, $begin, $end)
    {
        return $this->redisCall('lRange', func_get_args());
    }

    /**
     * @param string $key
     *
     * @return int
     */
    public function len($key)
    {
        return $this->redisCall('lSize', func_get_args());
    }

    /**
     * @param string $key
     *
     * @return bool or string
     */
    public function pop($key)
    {
        return $this->redisCall('lPop', func_get_args());
    }

    /**
     * @param string $key
     *
     * @return bool or string
     */
    public function lpop($key)
    {
        return $this->pop($key);
    }

    /**
     * @param string $key
     *
     * @return bool or string
     */
    public function rpop($key)
    {
        return $this->redisCall('RPop', func_get_args());
    }

    /**
     * 从头尾移除$count个$value.
     *
     * @param string $key
     * @param string $value
     * @param int    $count
     *
     * @return int
     */
    public function remove($key, $value, $count = 1)
    {
        return $this->redisCall('lRem', func_get_args());
    }

    /**
     * 改变列表的长度.
     *
     * @param string $key
     * @param int    $start
     * @param int    $stop
     *
     * @return mixed
     */
    public function trim($key, $start, $stop = -1)
    {
        return $this->redisCall('lTrim', func_get_args());
    }
}

class PhpRedisFunSets extends redisBaseCall
{
    /**
     * @param string $key
     * @param int    $value
     *
     * @return bool
     */
    public function add($key, $value)
    {
        return $this->redisCall('sAdd', func_get_args());
    }

    /**
     * 集合成员数.
     *
     * @param string $key
     *
     * @return int
     */
    public function size($key)
    {
        return $this->redisCall('sCard', func_get_args());
    }

    /**
     * @param string $key
     * @param string $value
     *
     * @return bool
     */
    public function isMember($key, $value)
    {
        return $this->redisCall('sIsMember', func_get_args());
    }

    /**
     * @param string $key
     *
     * @return array
     */
    public function members($key)
    {
        return $this->redisCall('sMembers', func_get_args());
    }

    /**
     * @param string $srcKey
     * @param string $dstKey
     * @param string $member
     *
     * @return bool
     */
    public function move($srcKey, $dstKey, $member)
    {
        return $this->redisCall('sMove', func_get_args());
    }

    /**
     * Removes and returns a random element from the set value at Key.
     *
     * @param string $key
     *
     * @return string
     */
    public function pop($key)
    {
        return $this->redisCall('sPop', func_get_args());
    }

    /**
     * @param string $key
     *
     * @return string
     */
    public function random($key)
    {
        return $this->redisCall('sRandMember', func_get_args());
    }

    /**
     * @param string $key
     * @param string $value
     *
     * @return string
     */
    public function remove($key, $value)
    {
        return $this->redisCall('sRem', func_get_args());
    }
}

class PhpRedisFunSortedSets extends redisBaseCall
{
    /**
     * @param string $key
     * @param int    $score
     * @param string $member
     *
     * @return bool
     */
    public function add($key, $score, $member)
    {
        return $this->redisCall('zAdd', func_get_args());
    }

    /**
     * 集合成员数.
     *
     * @param string $key
     *
     * @return int
     */
    public function size($key)
    {
        return $this->redisCall('zSize', func_get_args());
    }

    /**
     * @param string $key
     * @param float  $scoreStart
     * @param float  $scoreEend
     *
     * @return int
     */
    public function count($key, $scoreStart, $scoreEend)
    {
        return $this->redisCall('zCount', func_get_args());
    }

    /**
     * @param string $key
     * @param float  $scoreAdd
     * @param string $member
     *
     * @return float
     */
    public function incrBy($key, $member, $scoreAdd)
    {
        return $this->redisCall('zIncrBy', array($key, $scoreAdd, $member));
    }

    /**
     * @param string $key
     * @param float  $scoreStart
     * @param float  $scoreEend
     *
     * @return int
     */
    public function range($key, $scoreStart, $scoreEend, $start = 1, $count = 5, $withscores = false)
    {
        return $this->redisCall('zRangeByScore', array($key, $scoreStart, $scoreEend, array('withscores' => $withscores, 'limit' => array($start, $count))));
    }

    /**
     * @param string $key
     * @param float  $scoreStart
     * @param float  $scoreEend
     *
     * @return int
     */
    public function zRevRange($key, $scoreStart, $scoreEend, $start = 1, $count = 5, $withscores = false)
    {
        return $this->redisCall('zRevRangeByScore', array($key, $scoreEend, $scoreStart, array('withscores' => $withscores, 'limit' => array($start, $count))));
    }

    /**
     * @param string $key
     * @param float  $scoreAdd
     *
     * @return bool
     */
    public function delete($key, $member)
    {
        return $this->redisCall('zDelete', func_get_args());
    }

    /**
     * @param string $key
     * @param int    $start
     * @param int    $end
     *
     * @return int
     */
    public function deleteRangeByRank($key, $start, $end)
    {
        return $this->redisCall('zRemRangeByRank', func_get_args());
    }

    /**
     * @param string $key
     * @param float  $start
     * @param float  $end
     *
     * @return int
     */
    public function deleteRangeByScore($key, $start, $end)
    {
        return $this->redisCall('zDeleteRangeByScore', func_get_args());
    }

    /**
     * Determine the index of a member in a sorted set.
     *
     * @param string $key
     * @param float  $scoreAdd
     *
     * @return bool
     */
    public function rank($key, $member)
    {
        return $this->redisCall('zRank', func_get_args());
    }

    /**
     * Determine the index of a member in a sorted set.
     *
     * @param string $key
     * @param float  $scoreAdd
     *
     * @return bool
     */
    public function score($key, $member)
    {
        return $this->redisCall('zScore', func_get_args());
    }
}

