<?php

namespace App\Jobs;

use App\Repositories\Order\OrderRepositoryInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncOrdersWooCommerce implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $orderRepository;
    protected $user;
    protected $orders;
    protected $store_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($store_id, $user, $orders, OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository= $orderRepository;
        $this->user= $user;
        $this->orders= $orders;
        $this->store_id= $store_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $newOrder = [];
        $newOrder['store'] = $this->store_id;
        $newOrder['user_id'] = $this->user->id;
        foreach($this->orders as $order){
            $isserOrder = $this->orderRepository->newModel()->where(['store'=> $this->store_id, 'user_id' =>$this->user->id, 'origin_id' => $order->id])->first();
            if(!$isserOrder){
                $shipping = $order->shipping;
                $newOrder['origin_id'] = $order->id;
                $newOrder['shipping_name'] = $shipping->first_name.' '.$shipping->last_name;
                $newOrder['shipping_phone'] = $order->billing->phone;
                $newOrder['shipping_address'] = $shipping->address_1;
                $newOrder['shipping_state'] = $shipping->state;
                $newOrder['shipping_zip'] = $shipping->postcode;
                $newOrder['shipping_country'] = $shipping->country;
                $newOrder['note'] = $order->customer_note;
                $this->orderRepository->create($newOrder);
            }
        }
    }
}
