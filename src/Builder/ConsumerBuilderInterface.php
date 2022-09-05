<?php
declare(strict_types=1);

namespace Ktsivkov\KafkaIntegration\Builder;

use Ktsivkov\KafkaIntegration\KafkaMessageHandlerInterface;

interface ConsumerBuilderInterface
{
    public function consumeMessage(array $topics, KafkaMessageHandlerInterface $messageHandler): ConsumerBuilder;

    public function setTimeoutMs(int $timeoutMs): ConsumerBuilder;
}
