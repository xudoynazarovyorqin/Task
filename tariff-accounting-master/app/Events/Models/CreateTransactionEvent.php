<?php

namespace App\Events\Models;

use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class CreateTransactionEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $transactionable_id;
    private $transactionable_type;
    private $contractable_id;
    private $contractable_type;
    private $payment_type_id;
    private $amount;
    private $rate;
    private $currency_id;
    private $debit;
    private $comment;
    private $relatedItems;
    private $datetime;
    private $score_id;

    public function __construct()
    {
        $this->datetime = '';
        $this->transactionable_id = null;
        $this->transactionable_type = null;
        $this->contractable_id = null;
        $this->contractable_type = null;
        $this->payment_type_id = null;
        $this->amount = 0.0;
        $this->rate = 0.0;
        $this->currency_id = null;
        $this->score_id = null;
        $this->debit = 0;
        $this->comment = null;
        $this->relatedItems = [];
    }

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

    /**
     * @return null
     */
    public function getTransactionableId()
    {
        return $this->transactionable_id;
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
     * @param null $transactionable_id
     */
    public function setTransactionableId($transactionable_id): void
    {
        $this->transactionable_id = $transactionable_id;
    }

    /**
     * @return null
     */
    public function getTransactionableType()
    {
        return $this->transactionable_type;
    }

    /**
     * @param null $transactionable_type
     */
    public function setTransactionableType($transactionable_type): void
    {
        $this->transactionable_type = $transactionable_type;
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
    public function getContractableType()
    {
        return $this->contractable_type;
    }

    /**
     * @param null $contractable_type
     */
    public function setContractableType($contractable_type): void
    {
        $this->contractable_type = $contractable_type;
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
     * @return int
     */
    public function getDebit(): int
    {
        return $this->debit;
    }

    /**
     * @param int $debit
     */
    public function setDebit(int $debit): void
    {
        $this->debit = $debit;
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

}
