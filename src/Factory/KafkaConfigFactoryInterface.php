<?php

namespace Ktsivkov\KafkaIntegration\Factory;

use RdKafka\Conf;

interface KafkaConfigFactoryInterface
{
    public function getConfig(): Conf;
}
