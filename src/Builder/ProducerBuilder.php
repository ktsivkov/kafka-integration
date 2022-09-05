<?php
declare(strict_types=1);

namespace Ktsivkov\KafkaIntegration\Builder;

use Ktsivkov\KafkaIntegration\Exception\KafkaMessageFlushException;
use Ktsivkov\KafkaIntegration\Factory\KafkaConfigFactoryInterface;
use Ktsivkov\KafkaIntegration\Factory\KafkaProducerFactoryInterface;
use RdKafka\Producer;
use RdKafka\ProducerTopic;
use RdKafka\TopicConf;

final class ProducerBuilder implements ProducerBuilderInterface
{
    private readonly Producer $producer;
    /** @var ProducerTopic[] */
    private array $topics = [];

    /**
     * Defaults
     */
    private int $flushTimeoutMs = 500;
    private int $flushRetries = 3;
    private int $pollTimeoutMs = 0;
    private int $partition = RD_KAFKA_PARTITION_UA;

    public function __construct(
        private readonly KafkaConfigFactoryInterface   $kafkaConfigFactory,
        private readonly KafkaProducerFactoryInterface $kafkaProducerFactory,
    )
    {
        $this->producer = $this->kafkaProducerFactory->getProducer($this->kafkaConfigFactory->getConfig());
    }

    /**
     * @throws KafkaMessageFlushException
     */
    public function produceMessage(
        string  $topic,
        string  $message,
        int     $messageFlags = 0,
        ?string $messageKey = null,
        ?string $messageOpaque = null,
    ): self
    {
        $producerTopic = $this->getTopic($topic);
        $producerTopic->produce($this->partition, $messageFlags, $message, $messageKey, $messageOpaque);
        $this->producer->poll($this->pollTimeoutMs);
        $this->flush();
        return $this;
    }

    private function getTopic(string $topic): ProducerTopic
    {
        if (!array_key_exists($topic, $this->topics)) {
            $this->topics[$topic] = $this->producer->newTopic($topic);
        }
        return $this->topics[$topic];
    }

    private function flush(): void
    {
        for ($retries = 0; $retries < $this->flushRetries; $retries++) {
            $result = $this->producer->flush($this->flushTimeoutMs);
            if (RD_KAFKA_RESP_ERR_NO_ERROR === $result) {
                return;
            }
        }
        throw new KafkaMessageFlushException('Was unable to flush, messages might be lost!');
    }

    public function setFlushTimeoutMs(int $flushTimeoutMs): self
    {
        $this->flushTimeoutMs = $flushTimeoutMs;
        return $this;
    }

    public function setFlushRetries(int $flushRetries): self
    {
        $this->flushRetries = $flushRetries;
        return $this;
    }

    public function setPollTimeoutMs(int $pollTimeoutMs): self
    {
        $this->pollTimeoutMs = $pollTimeoutMs;
        return $this;
    }

    public function setPartition(int $partition): self
    {
        $this->partition = $partition;
        return $this;
    }
}
