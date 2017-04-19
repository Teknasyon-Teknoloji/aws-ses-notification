# AWS SES Notification
Handle AWS Ses notifications e.g. bounced, complaint or delivery.

## Requirements
* PHP 7.0+
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

## Installation
You can use Composer to install :

``` shell
composer require Teknasyon-teknoloji/aws-ses-notifications
```

## TODO
* HANDLER EXAMPLES

## Contributing
You can contribute by forking the repo and creating pull requests. You can also create issues or feature requests.

## Disclaimer
Your AWS SES and SNS usage my be charged. Please check AWS pricing pages.

## License
This project is licensed under the MIT license. `LICENSE` file can be found in this repository.
