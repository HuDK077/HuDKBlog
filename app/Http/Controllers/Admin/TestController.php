<?php
/**
 * Created by PhpStorm.
 * User: cracker
 * Date: 2020/4/19
 * Time: 14:15
 * ProjectName: sport
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\AdminUser;
use App\Models\Admin\AdminWidget;
use Illuminate\Http\Request;

class TestController extends Controller
{

    public function test(Request $request)
    {

//        $user = AdminUser::find(1)->with('roles.widgets', "widgets")->first()->toArray();
//        foreach ($user['roles'] as &$role) {
//            if ($role['widgets']) {
//                $user['widgets'] = array_merge($user["widgets"], $role["widgets"]);
//            }
//        }
//        $widgets = array_keys(array_flip(array_column($user['widgets'],'uri')));
//        return $widgets;


        $user = AdminUser::find(1)->with('roles.widgets', 'roles.menus', "widgets", 'menus')->first()->toArray();
        foreach ($user['roles'] as &$role) {
            if ($role['menus']) {
                $user['menus'] = array_merge($user["menus"], $role["menus"]);
            }
            if ($role['widgets']) {
                $user['widgets'] = array_merge($user["widgets"], $role["widgets"]);
            }
            unset($role['widgets']);
            unset($role['menus']);
        }
        $user['menus'] = assoc_unique($user['menus'], 'id');
        $user['widgets'] = assoc_unique($user['widgets'], 'id');
        $widgets = array_column($user['widgets'],'slug');
        $date = array_column($user['menus'] , 'sort');
        array_multisort($date,SORT_DESC,$user['menus']);
        $menus = list_to_tree($user['menus']);
        $roles = $user['roles'];
        unset($user['widgets']);
        unset($user['menus']);
        unset($user['roles']);

        $data = [];
        $option = $request->option;
        if (is_array($option) && $option){
            foreach($option as $item){
                $data[$item] = ${$item};
            }
        }else{
            $data = ['member' => $user,'roles' => $roles,'menus' => $menus,'widgets' => $widgets];
        }


        return $data;
    }

    /**
     * websocket 推送消息到固定ID
     * @param Request $request
     * @author: cracker
     * @DATE: 2020/5/14
     * @TIME: 12:49
     * @NAME: push
     */
    public function push(Request $request)
    {
        $fd = $request->uid; // Find fd by userId from a map [userId=>fd].
        /**@var \Swoole\WebSocket\Server $swoole */
        $swoole = app('swoole');
        $success = $swoole->push($fd, $request->message);
        var_dump($success);
    }
}
