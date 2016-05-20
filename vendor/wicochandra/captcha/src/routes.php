<?php
$this->app->router->get('captcha/image', ['as' => 'captcha', function () {
    return \Captcha::CreateImage();
}]);
