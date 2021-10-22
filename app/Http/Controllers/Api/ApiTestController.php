<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\CloseOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use App\Services\WebSocketService;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;

class ApiTestController extends Controller
{
    public function my_scandir()
    {
        Log::info('strat time');
        CloseOrder::dispatch('延时事件发生',30);
        return 111;
        $data = [
            'user:1' => "user1",
            'user:2' => "user2",
            'user:3' => "user3",
        ];

        // key=>value操作
        // 存入Redis 获取
//        Redis::set('111','11111');
//        Redis::get('111');
        // 数组存入Redis 获取
//        Redis::mset($data);
//        Redis::mget(array_keys($data));
        // 存入有时效性的记录
//        Redis::setex('ohhh',2,'我要过期了');
        // add操作 存在则不添加
//        Redis::setnx('foo',12);
//        Redis::setnx('foo',34);
        // set重新设置返回之前value
//        Redis::getset('foo',34);
        // 检查key是否存在
//        Redis::exists('foo');
        // 删除可删除多个key
//        Redis::del('foo');
        // 指定key追加字符返回长度
//        Redis::append('foo','123');




        // 队列添加，队列不存在会新建
//        Redis::rpush('list1','1');
//        Redis::rpush('list1','2');
//        Redis::rpush('list2','3');
//        Redis::rpush('list2','4');
        // 支队存在的队列添加，不存在返回0
//        Redis::rpushx('list1','value5');
        // 返回队列长队
//        Redis::llen('list1');
        // 返回队列多少到多少之间的元素
//        Redis::lrange('list1',2,5);
        // 返回指定位置元素
//        Redis::lindex('list1', 2);
        // 修改队列指定元素
//        Redis::lset('list1', 4,'value1231');
//        Redis::lrem('list1', 4,'1231');
        // 类似栈结构地弹出(并删除)最左或最右的一个元素
//        Redis::lpop('list1') ;
//        Redis::rpop('list1') ;
        // 保留左边起若干元素，其余删除
//        Redis::ltrim('list1', 0, 1);
        // list1 最后一个元素到 list2 第一个元素 （也适用于同一个队列操作：吧队列最后一个元素插入第一个插队）
//        Redis::rpoplpush('list1','list2');
//        Redis::rpoplpush('list2','list2');
        // 指定元素插队
//        Redis::linsert('list2','before',2,6); // 之前
//        Redis::linsert('list2','after',7,8);// 之后
        // list2为空则一直等待，知道不为空时10秒过后弹出第一个元素
//        Redis::blpop('list2',10);



        // set操作
        // 无序add
//        Redis::sadd('set1', 'ab');
//        Redis::sadd('set1', 'cd');
//        Redis::sadd('set1', 'ef');
        // 删除指定元素
//        Redis::srem('set1', 'ef');
        // 弹出首元素
//        Redis::spop('set1');
        // 移动set1指定元素到set2
//        Redis::sadd('set2',123);
//        Redis::smove('set1','set2','ab');
        // 统计set元素数量
//        Redis::scard('set2');
        // 判断指定set是否存在指定元素
//        Redis::sismember('set2',123);
        // 获取指定set所有元素
//        Redis::smembers('set2');
        // 返回两个元素的交集/并集/补集 sinter/sunion/sdiff
//        Redis::sinter('set1','set2');
//        Redis::sunion('set1','set2');
//        Redis::sdiff('set1','set2');
        // set sinterstore/sunionstore/sdiffstore 交集/并集/补集 copy到第三个表中
//        Redis::set('foo',0);
//        Redis::sdiffstore('foo', 'set1');
//        Redis::sdiffstore('foo', 'set1', 'set2');
        // 随机返回一个set元素
//        Redis::srandmember('set1');
        // 有序set添加
//        Redis::zadd('zset',1,'ab');
//        Redis::zadd('zset',2,'cd');
//        Redis::zadd('zset',3,'ef');
//        Redis::zadd('zset',4,'gh');
        // 移除指定元素
//        Redis::zrem('zset','ef');
        // 按位置顺序返回指定区间元素
//        Redis::zrange('zset',0,0);// 正排
//        Redis::zrevrange('zset',2,9); // 倒排
        // zrangebyscore/zrevrangebyscore 按顺序/降序返回表中指定索引区间的元素
//        Redis::zrangebyscore('zset',2,9); // 正序
//        Redis::zrevrangebyscore('zset',9,2); //倒序
        return Redis::zrangebyscore('zset1', 2, 9, array('withscores'=>true, 'limit'=>array(1, 2)));
    }
}
