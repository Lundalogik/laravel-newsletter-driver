# Lime Newsletter mail driver for Laravel


![Packagist Version](https://img.shields.io/packagist/v/Lundalogik/laravel-newsletter-driver)
![Packagist License](https://img.shields.io/packagist/l/Lundalogik/laravel-newsletter-driver)
![Packagist Downloads](https://img.shields.io/packagist/dm/Lundalogik/laravel-newsletter-driver)

[Lime Technologies](https://www.lime-technologies.com/)

## What is Lime Newsletter?
Lime Newsletter is a Lime CRM add-on for creating and sending email campaigns and newsletters. Benefits include: personalization with CRM data, email templates, dynamic content, and subscriber management.

[Lime Newsletter](https://www.lime-technologies.com/en/add-on/lime-newsletter/)

## Installation

### Supported Laravel versions

| Laravel version | Release version |
|-----------------|-----------------|
| 7.x, 8.x,       | 1.x             |
| 9.x, 10.x       | 2.x             |

Add the following to your ``composer.json``

```
composer require lundalogik/laravel-newsletter-driver
```

## Configuration

Change default mail driver and add new variables to your **.env** file:

```.env
MAIL_MAILER=newsletter 

LIME_NEWSLETTER_API_KEY    = <YOUR_NEWSLETTER_API_KEY>
LIME_NEWSLETTER_USER_EMAIL = <YOUR_NEWSLETTER_USER_EMAIL>
LIME_NEWSLETTER_ACCOUNT    = <YOUR_NEWSLETTER_ACCOUNT_NAME>
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

You also need to specify new available mail driver in **config/mail.php**:

```php
'mailers' => [
    ... 

    'newsletter' => [
        'transport' => 'newsletter',
    ],
],
```
