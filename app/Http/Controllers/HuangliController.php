<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use app\Huangli;

class HuangliController extends Controller
{
    public function create()
    {
        return view('createHuangli');
    }

    public function new(Request $req)
    {
        $msg = '';
        if($req->has('content')) {
            $content = $req->input('content');
            $lines = preg_split("/\r\n|\n|\r/", $content);
            $count = 0;
            foreach ($lines as $line) {
                $action = trim($line);
                $action = substr($action, 0, 20);
                $hl = Huangli::findOrNew(['action' => $action]);
                $hl->action = $action;
                //$hl->description = ;
                if($hl->save()) {
                    $count++;
                }
            }
            $msg = '录入了' . $count . '条。'
        }
        else {
            $msg = '输入为空';
        }
        return $msg;
    }
}
