<?php

namespace Ktsivkov\KafkaIntegration;

use Ktsivkov\KafkaIntegration\Exception\KafkaException;
use Ktsivkov\KafkaIntegration\Exception\KafkaTimeoutException;
use RdKafka\Message;

abstract class AbstractKafkaMessageHandler implements KafkaMessageHandlerInterface
{
    public function handle(Message $message): void
    {
        if ($message->err === RD_KAFKA_RESP_ERR_NO_ERROR) {
            $this->onHandle($message);
            return;
        }
        if ($message->err === RD_KAFKA_RESP_ERR__PARTITION_EOF) {
            return;
        }
        if ($message->err === RD_KAFKA_RESP_ERR__TIMED_OUT) {
            throw new KafkaTimeoutException($message->errstr());
        }
        throw new KafkaException($message->errstr());
    }

    protected abstract function onHandle(Message $message): void;
}
