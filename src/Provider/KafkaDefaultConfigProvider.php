<?php
declare(strict_types=1);

namespace Ktsivkov\KafkaIntegration\Provider;

use RdKafka\Conf;

final class KafkaDefaultConfigProvider implements KafkaConfigProviderInterface
{
    private Conf $config;

    public function __construct(private readonly array $kafkaBrokers, private readonly string $kafkaGroupId)
    {
        $this->config = $this->getConfigInstance();
    }

    private function getConfigInstance(): Conf
    {
        $config = new Conf();
        foreach ($this->getConfigParameters() as $key => $value) {
            $config->set($key, $value);
        }
        $config->set('log_level', (string) LOG_DEBUG);
        $config->set('debug', 'all');
        return $config;
    }

    private function getConfigParameters(): array
    {
        return [
            'metadata.broker.list' => $this->getKafkaBrokersAsString(),
            'group.id' => $this->kafkaGroupId,
            'auto.offset.reset' => 'earliest',
            'enable.partition.eof' => 'true',
            'enable.idempotence' => 'true',
            'allow.auto.create.topics' => 'true',
        ];
    }

    private function getKafkaBrokersAsString(): string
    {
        return implode(',', $this->kafkaBrokers);
    }

    public function getConfig(): Conf
    {
        return $this->config;
    }
}
