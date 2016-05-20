<?php

class CaptchaTest extends \TestCase {

    public function testCaptchaUrl()
    {
        $response = $this->route('GET', 'captcha');
        $this->assertResponseOk();
        $this->assertEquals('image/jpeg', $response->headers->get('content-type'));
    }

}
