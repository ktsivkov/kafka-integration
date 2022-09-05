<?php

namespace Ktsivkov\KafkaIntegration\Builder;

use Ktsivkov\KafkaIntegration\Exception\KafkaMessageFlushException;

interface ProducerBuilderInterface
{
    /**
     * @throws KafkaMessageFlushException
     */
    public function produceMessage(string $topic, string $message, int $messageFlags = 0, ?string $messageKey = null, ?string $messageOpaque = null): ProducerBuilder;

    public function setFlushTimeoutMs(int $flushTimeoutMs): ProducerBuilder;

    public function setFlushRetries(int $flushRetries): ProducerBuilder;

    public function setPollTimeoutMs(int $pollTimeoutMs): ProducerBuilder;

    public function setPartition(int $partition): ProducerBuilder;
}
