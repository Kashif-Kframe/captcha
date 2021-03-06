<?php

namespace Kashif\Captcha;
use Illuminate\Support\Facades\Session;
use Kashif\Captcha\CaptchaBuilder;

trait CaptchaRender
{
    public static function getCaptcha(){

        $builder = new CaptchaBuilder();
        $builder->build();

        Session::put('k-captcha', $builder->getPhrase());

        $img_path = asset('kashif/captcha/image/refresh.png');
        $reload_path = url('k-captcha/reload');

        return '<div class="k-captcha-script"><img style="padding-left: 20px;" id="k-captcha-image" class="k-captcha-image" src="' . $builder->inline() . '"><span><img class="k-captcha-reload" style="width: 40px; height: 30px; cursor: pointer;padding-bottom: 5px;" src="'.$img_path.'" onclick="reloadCaptcha();"></span>
          </div>
          <script type="text/javascript">
          function reloadCaptcha() {
              let req = new XMLHttpRequest();
              req.responseType = "json";
              req.open("GET", "'.$reload_path.'", true);
              req.onload  = function() {
                 document.getElementById("k-captcha-image").src = req.response;
              };
              req.send(null);    
          }</script>';
    }

    public static function verifyCaptcha($captcha)
    {
        if (Session::has('k-captcha')) {

            $generatedCaptcha = Session::get('k-captcha');
            if (strtolower($generatedCaptcha) == strtolower($captcha)) {
                Session::forget('k-captcha');
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

}