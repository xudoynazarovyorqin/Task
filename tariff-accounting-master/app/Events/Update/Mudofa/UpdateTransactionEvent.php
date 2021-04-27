<?php


namespace App\Events\Update\Mudofa;


use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UpdateTransactionEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $id;
    private $payment_system;
    private $system_transaction_id;
    private $amount;
    private $currency_code;
    private $state;
    private $updated_time;
    private $comment;
    private $detail;
    private $transactionable_id;
    private $transactionable_type;

    public function __construct()
    {
        $this->id = 0;
        $this->payment_system = '';
        $this->system_transaction_id = '';
        $this->amount = 0.0;
        $this->currency_code = null;
        $this->state = null;
        $this->updated_time = null;
        $this->comment = null;
        $this->detail = null;
        $this->transactionable_id = null;
        $this->transactionable_type = null;
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
     * @return string
     */
    public function getPaymentSystem(): string
    {
        return $this->payment_system;
    }

    /**
     * @param string $payment_system
     */
    public function setPaymentSystem(string $payment_system): void
    {
        $this->payment_system = $payment_system;
    }

    /**
     * @return string
     */
    public function getSystemTransactionId(): string
    {
        return $this->system_transaction_id;
    }

    /**
     * @param string $system_transaction_id
     */
    public function setSystemTransactionId(string $system_transaction_id): void
    {
        $this->system_transaction_id = $system_transaction_id;
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
     * @return null
     */
    public function getCurrencyCode()
    {
        return $this->currency_code;
    }

    /**
     * @param null $currency_code
     */
    public function setCurrencyCode($currency_code): void
    {
        $this->currency_code = $currency_code;
    }

    /**
     * @return null
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param null $state
     */
    public function setState($state): void
    {
        $this->state = $state;
    }

    /**
     * @return null
     */
    public function getUpdatedTime()
    {
        return $this->updated_time;
    }

    /**
     * @param null $updated_time
     */
    public function setUpdatedTime($updated_time): void
    {
        $this->updated_time = $updated_time;
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
     * @return null
     */
    public function getDetail()
    {
        return $this->detail;
    }

    /**
     * @param null $detail
     */
    public function setDetail($detail): void
    {
        $this->detail = $detail;
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
