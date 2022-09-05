<?php

namespace Ktsivkov\KafkaIntegration\Builder;

interface ConsumerBuilderInterface
{
    public function consumeMessage(array $topics, callable $callable): \Ktsivkov\KafkaIntegration\Builder\ConsumerBuilder;

    public function setTimeoutMs(int $timeoutMs): \Ktsivkov\KafkaIntegration\Builder\ConsumerBuilder;
}
