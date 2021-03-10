<?php

namespace App\Repositories\Store;

use App\Repositories\BaseRepository;
use App\Repositories\Subjects\SubjectRepositoryInterface;

class StoreRepository extends BaseRepository implements StoreRepositoryInterface
{
    public function getModel()
    {
        return \App\Models\Store::class;
    }
}
