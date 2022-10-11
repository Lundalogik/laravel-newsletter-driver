# Lime Newsletter mail driver for Laravel

## Installation

Add the following to your ``composer.json``

```
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/Lundalogik/laravel-newsletter-driver.git"
    }
]
```

Then run 
```
composer require lundalogik/laravel-newsletter-driver:dev-main
```

## Configuration

Change default mail driver and add new variables to your **.env** file:

```php
MAIL_MAILER=newsletter #MAIL_DRIVER for Laravel < 7.x

LIME_NEWSLETTER_API_KEY=YOUR_NEWSLETTER_API_KEY
LIME_NEWSLETTER_USER_EMAIL=YOUR_NEWSLETTER_USER_EMAIL
LIME_NEWSLETTER_ACCOUNT=YOUR_NEWSLETTER_ACCOUNT_NAME
```

Add section to the **config/services.php** file:

```php
'newsletter' => [
    'api_key'    => env('LIME_NEWSLETTER_API_KEY'),
    'user_email' => env('LIME_NEWSLETTER_USER_EMAIL'),
    'account'    => env('LIME_NEWSLETTER_ACCOUNT'),
    'base_url'   => env('LIME_NEWSLETTER_BASE_URL', 'https://qa.bwz.se/bedrock/'),
],
```


For Laravel 7+ you also need to specify new available mail driver in **config/mail.php**:

```php
'mailers' => [
    ... 

    'newsletter' => [
        'transport' => 'newsletter',
    ],
],
```
