<?php

namespace Ktsivkov\KafkaIntegration\Factory;

use RdKafka\Conf;

class KafkaConfigFactory implements KafkaConfigFactoryInterface
{
    private Conf $config;

    public function __construct(private readonly array $kafkaBrokers, private readonly string $kafkaGroupId)
    {
        $this->config = $this->getConfigInstance();
    }

    public function getConfig(): Conf
    {
        return $this->config;
    }

    private function getConfigInstance(): Conf
    {
        $config = new Conf();
        $config->set('metadata.broker.list', $this->getBrokersAsString());
        $config->set('group.id', $this->kafkaGroupId);
        $config->set('auto.offset.reset', 'earliest');
        $config->set('enable.partition.eof', 'true');
        $config->set('enable.idempotence', 'true');
        return $config;
    }

    private function getBrokersAsString(): string
    {
        return implode(',', $this->kafkaBrokers);
    }
}
