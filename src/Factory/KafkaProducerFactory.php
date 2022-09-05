<?php

namespace Ktsivkov\KafkaIntegration\Factory;

use RdKafka\Conf;
use RdKafka\Producer;

class KafkaProducerFactory implements KafkaProducerFactoryInterface
{
    public function __construct()
    {
    }

    public function getProducer(Conf $config): Producer
    {
        return new Producer($config);
    }
}
