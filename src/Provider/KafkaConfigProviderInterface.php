<?php

namespace Ktsivkov\KafkaIntegration\Provider;

use RdKafka\Conf;

interface KafkaConfigProviderInterface
{
    public function getConfig(): Conf;
}
