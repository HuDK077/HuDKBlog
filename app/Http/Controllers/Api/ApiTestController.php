<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApiTestController extends Controller
{
    public function test(Request $request){
//        return env("APP_URL");
        return ucfirst('weChat');
        return checkMiddleware('api');
    }
    //
    public function push(){
        $fd = 1;
        $swoole = app('swoole');
        $success = $swoole->push($fd, 'Push data to fd#1 in Controller');
        var_dump($success);
    }
}
