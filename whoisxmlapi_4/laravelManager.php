<?php
/**
 * Class laravelManager
 *
 * @author jacopo beschi jacopo@jacopobeschi.com
 *
 * remember to use Session::save() to persist the data
 */
use Illuminate\Session\SessionManager;
use Illuminate\Encryption\Encrypter;

class laravelSessionManager
{
    public $laravel;

    protected $cookie_name;

    protected $app_key;

    public function __construct($laravel)
    {
        $this->laravel = $laravel;
        $this->app_key = $this->laravel['config']['app.key'];
        $cookie_name = $this->laravel['config']['session.cookie'];
        $this->cookie_name = isset($_COOKIE[$cookie_name]) ? $_COOKIE[$cookie_name] : false;
    }

    public function startSession()
    {
        $encrypter = $this->createEncrypter();

        $manager = new SessionManager($this->laravel);
        $session = $manager->driver();

        $this->updateSessionId($encrypter, $session);

        $session->start();

        $session->save();

        $this->bindNewSession($session);
    }

/**
 * @return Encrypter
 */
private function createEncrypter()
{
    $encrypter = new Encrypter($this->app_key);
    return $encrypter;
}
/**
 * @param $encrypter
 * @param $session
 */
private function updateSessionId($encrypter, $session)
{
    if($this->cookie_name)
    {
        $sessionId = $encrypter->decrypt($this->cookie_name);
        $session->setId($sessionId);
    }
}

/**
 * @param $session
 */
private function bindNewSession($session)
{
    App::instance('session', $session);
    App::instance('session.store', $session);
}

}