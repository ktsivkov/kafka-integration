<?php

namespace Ktsivkov\KafkaIntegration\Builder;

use Ktsivkov\KafkaIntegration\Exception\KafkaMessageFlushException;

interface ProducerBuilderInterface
{
    /**
     * @throws KafkaMessageFlushException
     */
    public function produceMessage(string $message, string $topic, int $msgFlags = 0,): \Ktsivkov\KafkaIntegration\Builder\ProducerBuilder;

    public function setFlushTimeoutMs(int $flushTimeoutMs): \Ktsivkov\KafkaIntegration\Builder\ProducerBuilder;

    public function setFlushRetries(int $flushRetries): \Ktsivkov\KafkaIntegration\Builder\ProducerBuilder;

    public function setPollTimeoutMs(int $pollTimeoutMs): \Ktsivkov\KafkaIntegration\Builder\ProducerBuilder;

    public function setPartition(int $partition): \Ktsivkov\KafkaIntegration\Builder\ProducerBuilder;
}
