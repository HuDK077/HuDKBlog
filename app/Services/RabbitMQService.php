<?php
/**
 * 说明
 * @author dkhu
 * @date 2023/5/18 10:00
 */

namespace App\Services;

use Log;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * 方法说明
 * @author dkhu
 * @date 2023/5/18 10:00
 */
class RabbitMQService
{
    private static function getConnect()
    {
        $config = [
            'host' => env('RABBITMQ_HOST', '127.0.0.1'),
            'port' => env('RABBITMQ_PORT', 5672),
            'user' => env('RABBITMQ_USER', 'hueck'),
            'password' => env('RABBITMQ_PASSWORD', 'hueck'),
            'vhost' => env('RABBITMQ_VHOST', 'hueck'),
        ];
        return new AMQPStreamConnection($config['host'], $config['port'], $config['user'], $config['password'], $config['vhost']);
    }

    /**
     * 数据插入到mq队列中（生产者）
     * @param $queue .队列名称
     * @param $messageBody .消息体
     * @param string $exchange .交换机名称
     * @param string $routingKey .设置路由
     * @throws \Exception
     */
    public static function push($queue, string $exchange, string $routingKey, $messageBody)
    {
        Log::info("生产者开始-$exchange-$queue-$routingKey");
        //获取连接
        $connection = self::getConnect();
        //构建通道（mq的数据存储与获取是通过通道进行数据传输的）
        $channel = $connection->channel();
        //声明一个队列
        $channel->queue_declare($queue, false, true, false, false);
        //指定交换机，若是路由的名称不匹配不会把数据放入队列中
        $channel->exchange_declare($exchange, 'direct', false, true, false);
        //队列和交换器绑定/绑定队列和类型
        $channel->queue_bind($queue, $exchange, $routingKey);
        $config = [
            'content_type' => 'text/plain',
            'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT
        ];

        //实例化消息推送类
        $message = new AMQPMessage($messageBody, $config);
        //消息推送到路由名称为$exchange的队列当中
        $channel->basic_publish($message, $exchange, $routingKey);
        //监听写入
        $channel->wait_for_pending_acks();
        Log::info("生产者已操作");
        //关闭消息推送资源
        $channel->close();
        //关闭mq资源
        $connection->close();
    }

    /**
     * 消费者：取出消息进行消费，并返回
     * @param $queue
     * @param $callback
     * @return bool
     * @throws \Exception
     */
    public static function pop($queue, $callback)
    {
        $connection = self::getConnect();
        //构建消息通道
        $channel = $connection->channel();
        //从队列中取出消息，并且消费
        $message = $channel->basic_get($queue);
        if (!$message) return false;
        //消息主题返回给回调函数
        $res = $callback($message->body);
        if ($res) {
            Log::info("ack验证");
            //ack验证，如果消费失败了，从新获取一次数据再次消费
            $channel->basic_ack($message->getDeliveryTag());
        }
        Log::info("ack消费完成");
        $channel->close();
        $connection->close();
        return true;
    }
}
