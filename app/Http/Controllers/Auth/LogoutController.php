<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    protected $redirectTo = '/login';
    public function __construct()
    {
    }

    public function index(){
        if(Auth::check()){
            Auth::logout();
        }
        return redirect($this->redirectTo);
    }
}
