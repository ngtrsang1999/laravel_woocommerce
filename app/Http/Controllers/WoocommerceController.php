<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginWooCommerceRequest;
use App\Jobs\SyncOrdersWooCommerce;
use App\Models\Store;
use App\Repositories\Order\OrderRepositoryInterface;
use App\Repositories\Store\StoreRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Automattic\WooCommerce\Client;

class WoocommerceController extends Controller
{
    protected $storeRepository;
    protected $orderRepository;
    public function __construct(StoreRepositoryInterface $storeRepository, OrderRepositoryInterface $orderRepository){
        $this->storeRepository = $storeRepository;
        $this->orderRepository = $orderRepository;
    }

    public function getWooCommerce($store){
        $store->data = json_decode($store->data, true);
        if($store->status == 1){
            $woocommerce = new Client(
                $store->url,
                $store->data['consumer_key'],
                $store->data['consumer_secret'],
                [
                    'wp_api' => true,
                    'version' => 'wc/v3'
                ]
            );
            return $woocommerce;
        }
        return false;
    }
    public function wooAuthorize(LoginWooCommerceRequest $request)
    {
        $user = Auth::user();
        $data = $request->all();
        $endpoint = '/wc-auth/v1/authorize';
        $store = $this->storeRepository->newModel()->where(['url'=> $data['url'], 'user_id' => $user->id])->first();
        if(!$store){
            $store = new Store();
            $request->merge([
                'data' => json_encode($data),
                'status' => '0',
                'user_id' => $user->id,
                ]);
                $this->storeRepository->create($request->all());
                $store = $this->storeRepository->newModel()->where(['url'=> $data['url'], 'user_id' => $user->id])->first();
        }
        $params = [
            'app_name' => 'laravel_wc',
            'scope' => 'read_write',
            'user_id' => $user->id,
            'return_url' => route('woocommerce.return'),
            'callback_url' => route('woocommerce.callback', $store->id),
        ];
        $query_string = http_build_query( $params );
        $urlToWooCommerce = $store->url . $endpoint . '?' . $query_string;
        return redirect()->away($urlToWooCommerce);
    }

    public function wooReturn(Request $request){
        if(!$request->has('success') || !$request->get('success')){
            return redirect()->route('woocommerce.index')->with('warning', 'connection failed');
        }else{
            return redirect()->route('woocommerce.index')->with('success', 'successful connection');
        }
    }

    public function wooCallback($store_id){
        $store = $this->storeRepository->find($store_id);
        $data = file_get_contents('php://input');
        $store->data = $data;
        $store->status = '1';
        $store->save();
    }

    public function wooSync($store_id){
        $data = [];
        $user = Auth::user();
        $store = $this->storeRepository->find($store_id);
        if($store){
            if($store->user_id != $user->id){
                $data['status'] = 0;
                $data['msg'] = 'You cannot sync this store';
            }else{
                $woocommerce = $this->getWooCommerce($store);
                if($woocommerce != false){
                    //Sync store
                    $orders = $woocommerce->get('orders', ['status' => 'processing']);
                    $syncOrdersWoocommerceJob = new SyncOrdersWooCommerce($store_id, $user, $orders, $this->orderRepository);
                    dispatch($syncOrdersWoocommerceJob);
                    $now = date("Y/m/d H:i:s");
                    $store->sync_at = $now;
                    $store->save();
                    $data['status'] = 1;
                    $data['msg'] = 'OK';
                    $data['data'] = $store;
                }else{
                    $data['status'] = 0;
                    $data['msg'] = 'You need to reconnect the store to sync';
                }
            }
        }else{
            $data['status'] = 0;
            $data['msg'] = 'This store cannot be found';
        }
        return json_encode($data);
    }


    public function createWebhook(){
        $store_id = 4;
        $store = $this->storeRepository->find($store_id);
        if($store){
            $woocommerce = $this->getWooCommerce($store);
            if($woocommerce != false){
                //Sync store
                $data = [
                    'name' => 'Order deleted',
                    'topic' => 'order.deleted',
                    'delivery_url' => 'https://webhook.site/8e7a160a-330d-44eb-a1b2-0a867ad8be2e'
                ];
                $newWebhook = $woocommerce->post('webhooks', $data);
                // $newWebhook = $woocommerce->get('orders');
                echo json_encode($newWebhook);
            }else{
                echo 'error';
            }
        }else{
            echo 'error';
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $stores = $user->stores;
        $stores = collect($stores)->sortByDesc('updated_at');
        $count  = 1;
        return view('user.woocommerce.connect', compact('user', 'stores', 'count'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
