<?php
namespace app\index\controller;
use  app\common\lib\ali\Sms;
use app\common\lib\Util;
class Index
{
    public function index()
    {
        // print_r($_GET);
        echo 111;
        // $phone = intval($_GET['phone_num']);
        // $code = intval($_GET['code']);
    }

    function test(){
        echo time();
    }

    public function sms(){
        try{

            return Util::show(config('code.success'),'erroe',3);
            Sms::sendSms(18659104326,123456);
            echo 1111;
            // echo APP_PATH . '../..extend/ali/vendor/autoload.php';
        }catch (\Exception $e){

        }
    }
}
