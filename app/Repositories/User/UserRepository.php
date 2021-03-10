<?php

namespace App\Repositories\User;

use App\Repositories\BaseRepository;
use App\Repositories\Subjects\SubjectRepositoryInterface;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function getModel()
    {
        return \App\Models\User::class;
    }
}
