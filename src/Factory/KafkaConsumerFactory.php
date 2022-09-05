<?php

namespace Ktsivkov\KafkaIntegration\Factory;

use RdKafka\Conf;
use RdKafka\KafkaConsumer;

class KafkaConsumerFactory implements KafkaConsumerFactoryInterface
{
    public function __construct()
    {
    }

    public function getConsumer(Conf $config): KafkaConsumer
    {
        return new KafkaConsumer($config);
    }
}
