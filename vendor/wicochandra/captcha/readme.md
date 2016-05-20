# Captcha

Simple image captcha based on [cool-php-captcha v0.3.1][1].

[![Latest Stable Version](https://poser.pugx.org/wicochandra/captcha/v/stable.png)](https://packagist.org/packages/wicochandra/captcha)
[![Total Downloads](https://poser.pugx.org/wicochandra/captcha/downloads.png)](https://packagist.org/packages/wicochandra/captcha)

## Update 1.1.0

Added `Captcha::isValid($value)` for checking captcha session.

## Installation

Firstly, you need to add the package to the `require` attribute of your `composer.json` file:

```json
{
    "require" : {
        "wicochandra/captcha": "1.*"
    }
}

```

Now, run `composer update` from command line to install the package.

Then, update your `app/config/app.php` by adding new value to the `providers` and `alias` key:

```php
    'providers' => array (

        //...

        'Wicochandra\Captcha\CaptchaServiceProvider'
    ),

    //...

    'aliases' => array (

        //...

        'Captcha'         => 'Wicochandra\Captcha\Facade\Captcha',
    ),
```

Lastly, you need to publish the package's asset. It contains font collection which is used to generate captcha:

```bash
    php artisan asset:publish wicochandra/captcha
```

If you want to custom the image of captcha, you must publish the package's configuration:

```bash
    php artisan config:publish wicochandra/captcha
```

## Usage

There are two main usage of the package.

1. **Image link**, you can use the following directive to generate the captcha link.
```php
    //Will return http://[web url]/captcha/image
    Captcha::url();
```
1. **Validator**, you can use `captcha` validator or `Captcha::isValid($value)` to validate whether the input is match with the captcha image or not.
```php
   $rules = array(
        '[input name]' => 'captcha'
    );

    Captcha::isValid('captcah-input');  //return true if valid. Otherwise return false
```
**Note: You have to define validation error message for `captcha` by yourself on `app/lang/{locale}/validation.php`**

[1]: https://code.google.com/p/cool-php-captcha   "cool-php-captcha"
