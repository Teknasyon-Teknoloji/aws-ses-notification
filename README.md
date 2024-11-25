# AWS SES Notification [![Build Status](https://travis-ci.org/Teknasyon-Teknoloji/aws-ses-notification.svg?branch=master)](https://travis-ci.org/Teknasyon-Teknoloji/aws-ses-notification)
Handle AWS Ses notifications e.g. bounced, complaint or delivery.
Update: Handle AWS Event Publishing Notification e.g. Bounce, Click, Complaint, DeliveryDelay, Open, Reject ...

## Requirements
* PHP 8.0+
* aws/aws-sdk-php
* aws/aws-php-sns-message-validator

## Usage

- Create your Handler class that implements \Teknasyon\AwsSesNotification\IHandler
- Set AWS Ses notification url at AWS Panel
- Add notification handle to your dispatcher
``` php
<?php

use Teknasyon\AwsSesNotification;
use Aws\Sns\MessageValidator;

try {
\Teknasyon\AwsSesNotification\Dispatcher::handle($myhandlerObj, new MessageValidator());
} catch (\Exception $e) {
    //Error handling
}

// ...
```
## Security
You should protect your notification url e.g. token check from GET param, IP restriction if possible
## Installation
You can use Composer to install :

``` shell
composer require teknasyon-teknoloji/aws-ses-notifications
```

Or you can add to composer.json file :

``` shell
"teknasyon-teknoloji/aws-ses-notification": "dev-master"
```

## TODO
* HANDLER EXAMPLES

## Contributing
You can contribute by forking the repo and creating pull requests. You can also create issues or feature requests.

## Disclaimer
Your AWS SES and SNS usage my be charged. Please check AWS pricing pages.

## License
This project is licensed under the MIT license. `LICENSE` file can be found in this repository.
