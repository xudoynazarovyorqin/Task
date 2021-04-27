<?php

namespace App\Events\Update;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class UpdateTransactionEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $transactionable_id;
    private $contractable_id;
    private $payment_type_id;
    private $amount;
    private $rate;
    private $currency_id;
    private $comment;
    private $relatedItems;
    private $datetime;
    private $id;
    private $score_id;

    public function __construct()
    {
        $this->id = 0;
        $this->datetime = '';
        $this->transactionable_id = null;
        $this->contractable_id = null;
        $this->payment_type_id = null;
        $this->score_id = null;
        $this->amount = 0.0;
        $this->rate = 0.0;
        $this->currency_id = null;
        $this->comment = null;
        $this->relatedItems = [];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }

    /**
     * @return null
     */
    public function getScoreId()
    {
        return $this->score_id;
    }

    /**
     * @param null $score_id
     */
    public function setScoreId($score_id): void
    {
        $this->score_id = $score_id;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return null
     */
    public function getTransactionableId()
    {
        return $this->transactionable_id;
    }

    /**
     * @param null $transactionable_id
     */
    public function setTransactionableId($transactionable_id): void
    {
        $this->transactionable_id = $transactionable_id;
    }

    /**
     * @return null
     */
    public function getContractableId()
    {
        return $this->contractable_id;
    }

    /**
     * @param null $contractable_id
     */
    public function setContractableId($contractable_id): void
    {
        $this->contractable_id = $contractable_id;
    }

    /**
     * @return null
     */
    public function getPaymentTypeId()
    {
        return $this->payment_type_id;
    }

    /**
     * @param null $payment_type_id
     */
    public function setPaymentTypeId($payment_type_id): void
    {
        $this->payment_type_id = $payment_type_id;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     */
    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
    }

    /**
     * @return float
     */
    public function getRate(): float
    {
        return $this->rate;
    }

    /**
     * @param float $rate
     */
    public function setRate(float $rate): void
    {
        $this->rate = $rate;
    }

    /**
     * @return null
     */
    public function getCurrencyId()
    {
        return $this->currency_id;
    }

    /**
     * @param null $currency_id
     */
    public function setCurrencyId($currency_id): void
    {
        $this->currency_id = $currency_id;
    }

    /**
     * @return null
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param null $comment
     */
    public function setComment($comment): void
    {
        $this->comment = $comment;
    }

    /**
     * @return array
     */
    public function getRelatedItems(): array
    {
        return $this->relatedItems;
    }

    /**
     * @param array $relatedItems
     */
    public function setRelatedItems(array $relatedItems): void
    {
        $this->relatedItems = $relatedItems;
    }

    /**
     * @return string
     */
    public function getDatetime(): string
    {
        return $this->datetime;
    }

    /**
     * @param string $datetime
     */
    public function setDatetime(string $datetime): void
    {
        $this->datetime = $datetime;
    }

}
