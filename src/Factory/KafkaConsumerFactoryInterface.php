<?php

namespace Ktsivkov\KafkaIntegration\Factory;

use RdKafka\Conf;
use RdKafka\KafkaConsumer;

interface KafkaConsumerFactoryInterface
{
    public function getConsumer(Conf $config): KafkaConsumer;
}
