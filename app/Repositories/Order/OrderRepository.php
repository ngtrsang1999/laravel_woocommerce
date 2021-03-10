<?php

namespace App\Repositories\Order;

use App\Repositories\BaseRepository;
use App\Repositories\Subjects\SubjectRepositoryInterface;

class OrderRepository extends BaseRepository implements OrderRepositoryInterface
{
    public function getModel()
    {
        return \App\Models\Order::class;
    }
}
