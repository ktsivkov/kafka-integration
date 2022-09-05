<?php

namespace Ktsivkov\KafkaIntegration\Factory;

use RdKafka\Conf;
use RdKafka\Producer;

interface KafkaProducerFactoryInterface
{
    public function getProducer(Conf $config): Producer;
}
