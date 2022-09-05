<?php

namespace Ktsivkov\KafkaIntegration;

use RdKafka\Message;

interface KafkaMessageHandlerInterface
{
    public function handle(Message $message): void;
}
