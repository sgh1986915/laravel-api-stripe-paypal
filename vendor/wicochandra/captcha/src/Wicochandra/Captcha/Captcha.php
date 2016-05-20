<?php
namespace Wicochandra\Captcha;

use SimpleCaptcha;

class Captcha extends SimpleCaptcha {

    public function __construct($config = array()) {
        parent::__construct($config);
        foreach ($config as $key => $value) {
            $this->$key = $value;
        }
    }

    public function getCurrentSession() {
        return \Session::get($this->session_var);
    }

    public function isValid($value) {
        return $this->getCurrentSession() === $value;
    }

    public function url() {
        return route('captcha', array(mt_rand()));
    }

    public function CreateImage() {
        $ini = microtime(true);

        /** Initialization */
        $this->ImageAllocate();

        /** Text insertion */
        $text = $this->GetCaptchaText();
        $fontcfg  = $this->fonts[array_rand($this->fonts)];
        $this->WriteText($text, $fontcfg);

        \Session::put($this->session_var, $text);

        /** Transformations */
        if (!empty($this->lineWidth)) {
            $this->WriteLine();
        }
        $this->WaveImage();
        if ($this->blur && function_exists('imagefilter')) {
            imagefilter($this->im, IMG_FILTER_GAUSSIAN_BLUR);
        }
        $this->ReduceImage();


        if ($this->debug) {
            imagestring($this->im, 1, 1, $this->height-8,
                "$text {$fontcfg['font']} ".round((microtime(true)-$ini)*1000)."ms",
                $this->GdFgColor
            );
        }


        /** Output */
        $data = $this->WriteImage();
        $this->Cleanup();

        $headers = [];
        if ($this->imageFormat == 'png') {
            $headers['content-type'] = 'image/png';
        } else {
            $headers['content-type'] = 'image/jpeg';
        }
        return \Response::make($data, 200, $headers);
    }

    /**
     * File generation
     */
    protected function WriteImage() {
        ob_start();
        if ($this->imageFormat == 'png' && function_exists('imagepng')) {
            imagepng($this->im);
        } else {
            imagejpeg($this->im, null, 80);
        }
        return ob_get_clean();
    }

    /**
     * Text insertion
     */
    protected function WriteText($text, $fontcfg = array()) {
        if (empty($fontcfg)) {
            // Select the font configuration
            $fontcfg  = $this->fonts[array_rand($this->fonts)];
        }

        // Full path of font file
        $fontfile = public_path('packages/wicochandra/captcha/fonts/'.$fontcfg['font']);


        /** Increase font-size for shortest words: 9% for each glyp missing */
        $lettersMissing = $this->maxWordLength-strlen($text);
        $fontSizefactor = 1+($lettersMissing*0.09);

        // Text generation (char by char)
        $x      = 20*$this->scale;
        $y      = round(($this->height*27/40)*$this->scale);
        $length = strlen($text);
        for ($i=0; $i<$length; $i++) {
            $degree   = rand($this->maxRotation*-1, $this->maxRotation);
            $fontsize = rand($fontcfg['minSize'], $fontcfg['maxSize'])*$this->scale*$fontSizefactor;
            $letter   = substr($text, $i, 1);

            if ($this->shadowColor) {
                $coords = imagettftext($this->im, $fontsize, $degree,
                    $x+$this->scale, $y+$this->scale,
                    $this->GdShadowColor, $fontfile, $letter);
            }
            $coords = imagettftext($this->im, $fontsize, $degree,
                $x, $y,
                $this->GdFgColor, $fontfile, $letter);
            $x += ($coords[2]-$x) + ($fontcfg['spacing']*$this->scale);
        }

        $this->textFinalX = $x;
    }

}
