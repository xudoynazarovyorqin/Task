<?php

namespace App\Events\Output\After;

use App\OutputProduct;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class AfterOutputProductDeletedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var OutputProduct
     */
    private $output_product;

    public function __construct(OutputProduct $output_product)
    {
        $this->output_product = $output_product;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }

    /**
     * @return OutputProduct
     */
    public function getOutputProduct(): OutputProduct
    {
        return $this->output_product;
    }

    /**
     * @param OutputProduct $output_product
     */
    public function setOutputProduct(OutputProduct $output_product): void
    {
        $this->output_product = $output_product;
    }
}
