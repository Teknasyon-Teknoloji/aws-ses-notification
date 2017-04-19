<?php

use PHPUnit\Framework\TestCase;

class DispatcherTest extends TestCase
{
    public function setUp()
    {
        stream_wrapper_unregister("php");
        stream_wrapper_register("php", '\MockPhpStream');
    }

    public function tearDown()
    {
        stream_wrapper_restore("php");
    }

    public function testEmptySnsMessageHandle()
    {
        file_put_contents('php://input', '');
        $this->expectException(Exception::class);
        \Teknasyon\AwsSesNotification\Dispatcher::handle(new MyHandler(), new \Aws\Sns\MessageValidator());
    }

    public function testWrongSnsMessageHandle()
    {
        file_put_contents(
            'php://input',
            '{
                  "Type" : "Notification",
                  "MessageId" : "da41e39f-ea4d-435a-b922-c6aae3915ebe",
                  "Message" : "test message"
                }'
        );
        $this->expectException(Exception::class);
        \Teknasyon\AwsSesNotification\Dispatcher::handle(new MyHandler(), new \Aws\Sns\MessageValidator());
    }

    public function testWrongSesMessageHandle()
    {
        file_put_contents(
            'php://input',
            '{
                    "Timestamp" : "2012-04-25T21:49:25.719Z",
                      "SignatureVersion" : "1",
                      "Signature" : "true",
                      "SigningCertURL" : "dummy.pem",
                    "TopicArn":"arn",
                  "Type" : "Notification",
                  "MessageId" : "msg1",
                  "Message" : {
       "zzzzzzzznotificationType":"Bounce",
       "bounce":{
          "bounceType":"Permanent",
          "reportingMTA":"dns; email.example.com",
          "bouncedRecipients":[
             {
                "emailAddress":"jane@example.com",
                "status":"5.1.1",
                "action":"failed",
                "diagnosticCode":"smtp; 550 5.1.1 <jane@example.com>... User"
             }
          ],
          "bounceSubType":"General",
          "timestamp":"2016-01-27T14:59:38.237Z",
          "feedbackId":"00000138111222aa-33322211-cccc-cccc-cccc-ddddaaaa068a-000000",
          "remoteMtaIp":"127.0.2.0"
       },
       "mail":{
          "timestamp":"2016-01-27T14:59:38.237Z",
          "source":"john@example.com",
          "sourceArn": "arn:aws:ses:us-west-2:888888888888:identity/example.com",
          "sourceIp": "127.0.3.0",
          "sendingAccountId":"123456789012",
          "messageId":"00000138111222aa-33322211-cccc-cccc-cccc-ddddaaaa0680-000000",
          "destination":[
            "jane@example.com",
            "mary@example.com",
            "richard@example.com"],
          "headersTruncated":false,
          "headers":[ 
           { 
             "name":"From",
             "value":"\"John Doe\" <john@example.com>"
           },
           { 
             "name":"To",
             "value":"\"Jane Doe\" <jane@example.com>, \"Mary Doe\" <mary@example.com>, \"Richard Doe\" <richard@example.com>"
           },
           { 
             "name":"Message-ID",
             "value":"custom-message-ID"
           },
           { 
             "name":"Subject",
             "value":"Hello"
           },
           { 
             "name":"Content-Type",
             "value":"text/plain; charset=\"UTF-8\""
           },
           { 
             "name":"Content-Transfer-Encoding",
             "value":"base64"
           },
           { 
             "name":"Date",
             "value":"Wed, 27 Jan 2016 14:05:45 +0000"
           }
          ],
          "commonHeaders":{ 
             "from":[ 
                "John Doe <john@example.com>"
             ],
             "date":"Wed, 27 Jan 2016 14:05:45 +0000",
             "to":[ 
                "Jane Doe <jane@example.com>, Mary Doe <mary@example.com>, Richard Doe <richard@example.com>"
             ],
             "messageId":"custom-message-ID",
             "subject":"Hello"
           }
        }
    }
                }'
        );
        $validatorStub = $this->createMock('\Aws\Sns\MessageValidator');
        $validatorStub->expects($this->any())
            ->method('validate')
            ->willReturn(true);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('SES "notificationType" not found! SNS MsgId: msg1');
        \Teknasyon\AwsSesNotification\Dispatcher::handle(new MyHandler(), $validatorStub);
    }

    public function testWrongBounceSesMessageHandle()
    {
        file_put_contents(
            'php://input',
            '{
                    "Timestamp" : "2012-04-25T21:49:25.719Z",
                      "SignatureVersion" : "1",
                      "Signature" : "true",
                      "SigningCertURL" : "dummy.pem",
                    "TopicArn":"arn",
                  "Type" : "Notification",
                  "MessageId" : "msg1",
                  "Message" : {
       "notificationType":"Bounce",
       "xxxxxxbounce":{
          "bounceType":"Permanent",
          "reportingMTA":"dns; email.example.com",
          "bouncedRecipients":[
             {
                "emailAddress":"jane@example.com",
                "status":"5.1.1",
                "action":"failed",
                "diagnosticCode":"smtp; 550 5.1.1 <jane@example.com>... User"
             }
          ],
          "bounceSubType":"General",
          "timestamp":"2016-01-27T14:59:38.237Z",
          "feedbackId":"00000138111222aa-33322211-cccc-cccc-cccc-ddddaaaa068a-000000",
          "remoteMtaIp":"127.0.2.0"
       },
       "mail":{
          "timestamp":"2016-01-27T14:59:38.237Z",
          "source":"john@example.com",
          "sourceArn": "arn:aws:ses:us-west-2:888888888888:identity/example.com",
          "sourceIp": "127.0.3.0",
          "sendingAccountId":"123456789012",
          "messageId":"00000138111222aa-33322211-cccc-cccc-cccc-ddddaaaa0680-000000",
          "destination":[
            "jane@example.com",
            "mary@example.com",
            "richard@example.com"],
          "headersTruncated":false,
          "headers":[ 
           { 
             "name":"From",
             "value":"\"John Doe\" <john@example.com>"
           },
           { 
             "name":"To",
             "value":"\"Jane Doe\" <jane@example.com>, \"Mary Doe\" <mary@example.com>, \"Richard Doe\" <richard@example.com>"
           },
           { 
             "name":"Message-ID",
             "value":"custom-message-ID"
           },
           { 
             "name":"Subject",
             "value":"Hello"
           },
           { 
             "name":"Content-Type",
             "value":"text/plain; charset=\"UTF-8\""
           },
           { 
             "name":"Content-Transfer-Encoding",
             "value":"base64"
           },
           { 
             "name":"Date",
             "value":"Wed, 27 Jan 2016 14:05:45 +0000"
           }
          ],
          "commonHeaders":{ 
             "from":[ 
                "John Doe <john@example.com>"
             ],
             "date":"Wed, 27 Jan 2016 14:05:45 +0000",
             "to":[ 
                "Jane Doe <jane@example.com>, Mary Doe <mary@example.com>, Richard Doe <richard@example.com>"
             ],
             "messageId":"custom-message-ID",
             "subject":"Hello"
           }
        }
    }
                }'
        );
        $validatorStub = $this->createMock('\Aws\Sns\MessageValidator');
        $validatorStub->expects($this->any())
            ->method('validate')
            ->willReturn(true);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('SES "notificationType" not defined! SES Type: Bounce, SNS MsgId: msg1');
        \Teknasyon\AwsSesNotification\Dispatcher::handle(new MyHandler(), $validatorStub);
    }

    public function testWrongComplaintSesMessageHandle()
    {
        file_put_contents(
            'php://input',
            '{
                    "Timestamp" : "2012-04-25T21:49:25.719Z",
                      "SignatureVersion" : "1",
                      "Signature" : "true",
                      "SigningCertURL" : "dummy.pem",
                    "TopicArn":"arn",
                  "Type" : "Notification",
                  "MessageId" : "msg1",
                  "Message" : {
       "notificationType":"Complaint",
       "xxxcomplaint":{
          "bounceType":"Permanent",
          "reportingMTA":"dns; email.example.com",
          "bouncedRecipients":[
             {
                "emailAddress":"jane@example.com",
                "status":"5.1.1",
                "action":"failed",
                "diagnosticCode":"smtp; 550 5.1.1 <jane@example.com>... User"
             }
          ],
          "bounceSubType":"General",
          "timestamp":"2016-01-27T14:59:38.237Z",
          "feedbackId":"00000138111222aa-33322211-cccc-cccc-cccc-ddddaaaa068a-000000",
          "remoteMtaIp":"127.0.2.0"
       },
       "mail":{
          "timestamp":"2016-01-27T14:59:38.237Z",
          "source":"john@example.com",
          "sourceArn": "arn:aws:ses:us-west-2:888888888888:identity/example.com",
          "sourceIp": "127.0.3.0",
          "sendingAccountId":"123456789012",
          "messageId":"00000138111222aa-33322211-cccc-cccc-cccc-ddddaaaa0680-000000",
          "destination":[
            "jane@example.com",
            "mary@example.com",
            "richard@example.com"],
          "headersTruncated":false,
          "headers":[ 
           { 
             "name":"From",
             "value":"\"John Doe\" <john@example.com>"
           },
           { 
             "name":"To",
             "value":"\"Jane Doe\" <jane@example.com>, \"Mary Doe\" <mary@example.com>, \"Richard Doe\" <richard@example.com>"
           },
           { 
             "name":"Message-ID",
             "value":"custom-message-ID"
           },
           { 
             "name":"Subject",
             "value":"Hello"
           },
           { 
             "name":"Content-Type",
             "value":"text/plain; charset=\"UTF-8\""
           },
           { 
             "name":"Content-Transfer-Encoding",
             "value":"base64"
           },
           { 
             "name":"Date",
             "value":"Wed, 27 Jan 2016 14:05:45 +0000"
           }
          ],
          "commonHeaders":{ 
             "from":[ 
                "John Doe <john@example.com>"
             ],
             "date":"Wed, 27 Jan 2016 14:05:45 +0000",
             "to":[ 
                "Jane Doe <jane@example.com>, Mary Doe <mary@example.com>, Richard Doe <richard@example.com>"
             ],
             "messageId":"custom-message-ID",
             "subject":"Hello"
           }
        }
    }
                }'
        );
        $validatorStub = $this->createMock('\Aws\Sns\MessageValidator');
        $validatorStub->expects($this->any())
            ->method('validate')
            ->willReturn(true);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('SES "notificationType" not defined! SES Type: Complaint, SNS MsgId: msg1');
        \Teknasyon\AwsSesNotification\Dispatcher::handle(new MyHandler(), $validatorStub);
    }

    public function testHandle()
    {
        file_put_contents(
            'php://input',
            '{
                    "Timestamp" : "2012-04-25T21:49:25.719Z",
                      "SignatureVersion" : "1",
                      "Signature" : "true",
                      "SigningCertURL" : "dummy.pem",
                    "TopicArn":"arn",
                  "Type" : "Notification",
                  "MessageId" : "msg1",
                  "Message" : {
       "notificationType":"Bounce",
       "bounce":{
          "bounceType":"Permanent",
          "reportingMTA":"dns; email.example.com",
          "bouncedRecipients":[
             {
                "emailAddress":"jane@example.com",
                "status":"5.1.1",
                "action":"failed",
                "diagnosticCode":"smtp; 550 5.1.1 <jane@example.com>... User"
             }
          ],
          "bounceSubType":"General",
          "timestamp":"2016-01-27T14:59:38.237Z",
          "feedbackId":"00000138111222aa-33322211-cccc-cccc-cccc-ddddaaaa068a-000000",
          "remoteMtaIp":"127.0.2.0"
       },
       "mail":{
          "timestamp":"2016-01-27T14:59:38.237Z",
          "source":"john@example.com",
          "sourceArn": "arn:aws:ses:us-west-2:888888888888:identity/example.com",
          "sourceIp": "127.0.3.0",
          "sendingAccountId":"123456789012",
          "messageId":"00000138111222aa-33322211-cccc-cccc-cccc-ddddaaaa0680-000000",
          "destination":[
            "jane@example.com",
            "mary@example.com",
            "richard@example.com"],
          "headersTruncated":false,
          "headers":[ 
           { 
             "name":"From",
             "value":"\"John Doe\" <john@example.com>"
           },
           { 
             "name":"To",
             "value":"\"Jane Doe\" <jane@example.com>, \"Mary Doe\" <mary@example.com>, \"Richard Doe\" <richard@example.com>"
           },
           { 
             "name":"Message-ID",
             "value":"custom-message-ID"
           },
           { 
             "name":"Subject",
             "value":"Hello"
           },
           { 
             "name":"Content-Type",
             "value":"text/plain; charset=\"UTF-8\""
           },
           { 
             "name":"Content-Transfer-Encoding",
             "value":"base64"
           },
           { 
             "name":"Date",
             "value":"Wed, 27 Jan 2016 14:05:45 +0000"
           }
          ],
          "commonHeaders":{ 
             "from":[ 
                "John Doe <john@example.com>"
             ],
             "date":"Wed, 27 Jan 2016 14:05:45 +0000",
             "to":[ 
                "Jane Doe <jane@example.com>, Mary Doe <mary@example.com>, Richard Doe <richard@example.com>"
             ],
             "messageId":"custom-message-ID",
             "subject":"Hello"
           }
        }
    }
                }'
        );
        $validatorStub = $this->createMock('\Aws\Sns\MessageValidator');
        $validatorStub->expects($this->any())
            ->method('validate')
            ->willReturn(true);

        $this->assertEquals(
            'processed',
                \Teknasyon\AwsSesNotification\Dispatcher::handle(new MyHandler(), $validatorStub),
            'Dispatcher handle failed!'
        );
    }
}
