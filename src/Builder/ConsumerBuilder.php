<?php
declare(strict_types=1);

namespace Ktsivkov\KafkaIntegration\Builder;

use Ktsivkov\KafkaIntegration\Exception\KafkaConsumeException;
use Ktsivkov\KafkaIntegration\Exception\KafkaException;
use Ktsivkov\KafkaIntegration\Exception\KafkaSubscribeException;
use Ktsivkov\KafkaIntegration\Exception\KafkaTimeoutException;
use Ktsivkov\KafkaIntegration\Factory\KafkaConfigFactoryInterface;
use Ktsivkov\KafkaIntegration\Factory\KafkaConsumerFactoryInterface;
use Ktsivkov\KafkaIntegration\KafkaMessageHandlerInterface;
use RdKafka\Exception;
use RdKafka\KafkaConsumer;

final class ConsumerBuilder implements ConsumerBuilderInterface
{
    private readonly KafkaConsumer $consumer;
    private int $timeoutMs = 1000;

    public function __construct(
        private readonly KafkaConfigFactoryInterface   $kafkaConfigFactory,
        private readonly KafkaConsumerFactoryInterface $kafkaConsumerFactory,
    )
    {
        $this->consumer = $this->kafkaConsumerFactory->getConsumer($this->kafkaConfigFactory->getConfig());
    }

    /**
     * @throws KafkaConsumeException | KafkaSubscribeException | KafkaTimeoutException | KafkaException
     */
    public function consumeMessage(array $topics, KafkaMessageHandlerInterface $messageHandler): self
    {
        $this->subscribe($topics);
        try {
            $handledMessage = $this->consumer->consume($this->timeoutMs);
        } catch (Exception $exception) {
            throw new KafkaConsumeException($exception->getMessage(), $exception->getCode(), $exception);
        }
        $messageHandler->handle($handledMessage);
        return $this;
    }

    private function subscribe(array $topics): void
    {
        $topicsToSubscribe = array_diff($topics, $this->consumer->getSubscription());
        if (!$topicsToSubscribe) {
            return;
        }
        try {
            $this->consumer->subscribe($topicsToSubscribe);
        } catch (\Exception $exception) {
            throw new KafkaSubscribeException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }

    public function setTimeoutMs(int $timeoutMs): self
    {
        $this->timeoutMs = $timeoutMs;
        return $this;
    }
}
