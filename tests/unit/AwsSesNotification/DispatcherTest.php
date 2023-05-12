<?php

use PHPUnit\Framework\TestCase;

class DispatcherTest extends TestCase
{
    public function setUp(): void
    {
        stream_wrapper_unregister("php");
        stream_wrapper_register("php", '\MockPhpStream');
    }

    public function tearDown(): void
    {
        stream_wrapper_restore("php");
    }

    public function testEmptySnsMessageHandle()
    {
        file_put_contents('php://input', ' ');
        $this->expectException(\Exception::class);
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
    "Type": "Notification",
    "MessageId": "b07218ef-d597-5f55-b1ac-4978bcf20073",
    "TopicArn": "arn:aws:sns:eu-west-1:990978750721:getcontact-ses-callback",
    "Message": "{\"zzzzzzzzznotificationType\":\"Bounce\",\"bounce\":{\"bounceType\":\"Permanent\",\"bounceSubType\":\"General\",\"bouncedRecipients\":[{\"emailAddress\":\"bounce@simulator.amazonses.com\",\"action\":\"failed\",\"status\":\"5.1.1\",\"diagnosticCode\":\"smtp; 550 5.1.1 user unknown\"}],\"timestamp\":\"2017-05-18T09:19:33.912Z\",\"feedbackId\":\"0102015c1adaebe6-5b7e06a9-770e-4998-be49-c08dd8dc2c7e-000000\",\"remoteMtaIp\":\"205.251.242.49\",\"reportingMTA\":\"dsn; a4-10.smtp-out.eu-west-1.amazonses.com\"},\"mail\":{\"timestamp\":\"2017-05-18T09:19:32.000Z\",\"source\":\"GetContact <notification@getcontact.com>\",\"sourceArn\":\"arn:aws:ses:eu-west-1:990978750721:identity\/notification@getcontact.com\",\"sourceIp\":\"31.145.77.18\",\"sendingAccountId\":\"990978750721\",\"messageId\":\"0102015c1adae824-024ea4c1-197e-47ef-897f-5e7352c9bc36-000000\",\"destination\":[\"bounce@simulator.amazonses.com\"],\"headersTruncated\":false,\"headers\":[{\"name\":\"From\",\"value\":\"GetContact <notification@getcontact.com>\"},{\"name\":\"To\",\"value\":\"bounce@simulator.amazonses.com\"},{\"name\":\"Subject\",\"value\":\"test email\"},{\"name\":\"MIME-Version\",\"value\":\"1.0\"},{\"name\":\"Content-Type\",\"value\":\"text\/html; charset=UTF-8\"},{\"name\":\"Content-Transfer-Encoding\",\"value\":\"7bit\"}],\"commonHeaders\":{\"from\":[\"GetContact <notification@getcontact.com>\"],\"to\":[\"bounce@simulator.amazonses.com\"],\"subject\":\"test email\"}}}",
    "Timestamp": "2017-05-18T09:19:33.938Z",
    "SignatureVersion": "1",
    "Signature": "ByjCovt38P55pwj97GaqF8BAnfTtjeeSN4MoMfSUXb6NPcgTJe+OwmKaXlo\/OAiSxld5FubnTHGcecOcA5cbyzK6uqVjO8Gb0zNZG7QIou8HDRMLoTple0v2OecYc\/KXw1Em0rs2p\/X0sFgZSDjdDuh3kuEd2ipVCHBX3Zek54nHe\/BBqQQiS1I40MT\/EF4PPJZMrkN9DJd5J\/kuXbgSQ4Lka2gqVneaxVMlYBkMQp\/yRf0pahpzor3DQjyo85GlQ4cifo7bZ0ZryIuh2R+wb8B4mZzb05GwksibZKpIOCOS04HebpBnewCAqYYs8W3D4NSkkVYxBFjNy0pQV38IsA==",
    "SigningCertURL": "https:\/\/sns.eu-west-1.amazonaws.com\/SimpleNotificationService-b95095beb82e8f6a046b3aafc7f4149a.pem",
    "UnsubscribeURL": "https:\/\/sns.eu-west-1.amazonaws.com\/?Action=Unsubscribe&SubscriptionArn=arn:aws:sns:eu-west-1:990978750721:getcontact-ses-callback:29e2c7cb-7263-406a-95a9-11ffe41295a8"
}'
        );
        $validatorStub = $this->createMock('\Aws\Sns\MessageValidator');
        $validatorStub->expects($this->any())
            ->method('validate')
            ->willReturn(true);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('SES "notificationType" not found! SNS MsgId: b07218ef-d597-5f55-b1ac-4978bcf20073');
        \Teknasyon\AwsSesNotification\Dispatcher::handle(new MyHandler(), $validatorStub);
    }

    public function testWrongBounceSesMessageHandle()
    {
        file_put_contents(
            'php://input',
            '{
    "Type": "Notification",
    "MessageId": "b07218ef-d597-5f55-b1ac-4978bcf20073",
    "TopicArn": "arn:aws:sns:eu-west-1:990978750721:getcontact-ses-callback",
    "Message": "{\"notificationType\":\"Bounce\",\"xxxxxxbounce\":{\"bounceType\":\"Permanent\",\"bounceSubType\":\"General\",\"bouncedRecipients\":[{\"emailAddress\":\"bounce@simulator.amazonses.com\",\"action\":\"failed\",\"status\":\"5.1.1\",\"diagnosticCode\":\"smtp; 550 5.1.1 user unknown\"}],\"timestamp\":\"2017-05-18T09:19:33.912Z\",\"feedbackId\":\"0102015c1adaebe6-5b7e06a9-770e-4998-be49-c08dd8dc2c7e-000000\",\"remoteMtaIp\":\"205.251.242.49\",\"reportingMTA\":\"dsn; a4-10.smtp-out.eu-west-1.amazonses.com\"},\"mail\":{\"timestamp\":\"2017-05-18T09:19:32.000Z\",\"source\":\"GetContact <notification@getcontact.com>\",\"sourceArn\":\"arn:aws:ses:eu-west-1:990978750721:identity\/notification@getcontact.com\",\"sourceIp\":\"31.145.77.18\",\"sendingAccountId\":\"990978750721\",\"messageId\":\"0102015c1adae824-024ea4c1-197e-47ef-897f-5e7352c9bc36-000000\",\"destination\":[\"bounce@simulator.amazonses.com\"],\"headersTruncated\":false,\"headers\":[{\"name\":\"From\",\"value\":\"GetContact <notification@getcontact.com>\"},{\"name\":\"To\",\"value\":\"bounce@simulator.amazonses.com\"},{\"name\":\"Subject\",\"value\":\"test email\"},{\"name\":\"MIME-Version\",\"value\":\"1.0\"},{\"name\":\"Content-Type\",\"value\":\"text\/html; charset=UTF-8\"},{\"name\":\"Content-Transfer-Encoding\",\"value\":\"7bit\"}],\"commonHeaders\":{\"from\":[\"GetContact <notification@getcontact.com>\"],\"to\":[\"bounce@simulator.amazonses.com\"],\"subject\":\"test email\"}}}",
    "Timestamp": "2017-05-18T09:19:33.938Z",
    "SignatureVersion": "1",
    "Signature": "ByjCovt38P55pwj97GaqF8BAnfTtjeeSN4MoMfSUXb6NPcgTJe+OwmKaXlo\/OAiSxld5FubnTHGcecOcA5cbyzK6uqVjO8Gb0zNZG7QIou8HDRMLoTple0v2OecYc\/KXw1Em0rs2p\/X0sFgZSDjdDuh3kuEd2ipVCHBX3Zek54nHe\/BBqQQiS1I40MT\/EF4PPJZMrkN9DJd5J\/kuXbgSQ4Lka2gqVneaxVMlYBkMQp\/yRf0pahpzor3DQjyo85GlQ4cifo7bZ0ZryIuh2R+wb8B4mZzb05GwksibZKpIOCOS04HebpBnewCAqYYs8W3D4NSkkVYxBFjNy0pQV38IsA==",
    "SigningCertURL": "https:\/\/sns.eu-west-1.amazonaws.com\/SimpleNotificationService-b95095beb82e8f6a046b3aafc7f4149a.pem",
    "UnsubscribeURL": "https:\/\/sns.eu-west-1.amazonaws.com\/?Action=Unsubscribe&SubscriptionArn=arn:aws:sns:eu-west-1:990978750721:getcontact-ses-callback:29e2c7cb-7263-406a-95a9-11ffe41295a8"
}'
        );
        $validatorStub = $this->createMock('\Aws\Sns\MessageValidator');
        $validatorStub->expects($this->any())
            ->method('validate')
            ->willReturn(true);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('SES "notificationType" not defined! SES Type: Bounce, SNS MsgId: b07218ef-d597-5f55-b1ac-4978bcf20073');
        \Teknasyon\AwsSesNotification\Dispatcher::handle(new MyHandler(), $validatorStub);
    }

    public function testWrongComplaintSesMessageHandle()
    {
        file_put_contents(
            'php://input',
            '{
    "Type": "Notification",
    "MessageId": "b07218ef-d597-5f55-b1ac-4978bcf20073",
    "TopicArn": "arn:aws:sns:eu-west-1:990978750721:getcontact-ses-callback",
    "Message": "{\"notificationType\":\"Complaint\",\"xxxxcomplaint\":{\"bounceType\":\"Permanent\",\"bounceSubType\":\"General\",\"bouncedRecipients\":[{\"emailAddress\":\"bounce@simulator.amazonses.com\",\"action\":\"failed\",\"status\":\"5.1.1\",\"diagnosticCode\":\"smtp; 550 5.1.1 user unknown\"}],\"timestamp\":\"2017-05-18T09:19:33.912Z\",\"feedbackId\":\"0102015c1adaebe6-5b7e06a9-770e-4998-be49-c08dd8dc2c7e-000000\",\"remoteMtaIp\":\"205.251.242.49\",\"reportingMTA\":\"dsn; a4-10.smtp-out.eu-west-1.amazonses.com\"},\"mail\":{\"timestamp\":\"2017-05-18T09:19:32.000Z\",\"source\":\"GetContact <notification@getcontact.com>\",\"sourceArn\":\"arn:aws:ses:eu-west-1:990978750721:identity\/notification@getcontact.com\",\"sourceIp\":\"31.145.77.18\",\"sendingAccountId\":\"990978750721\",\"messageId\":\"0102015c1adae824-024ea4c1-197e-47ef-897f-5e7352c9bc36-000000\",\"destination\":[\"bounce@simulator.amazonses.com\"],\"headersTruncated\":false,\"headers\":[{\"name\":\"From\",\"value\":\"GetContact <notification@getcontact.com>\"},{\"name\":\"To\",\"value\":\"bounce@simulator.amazonses.com\"},{\"name\":\"Subject\",\"value\":\"test email\"},{\"name\":\"MIME-Version\",\"value\":\"1.0\"},{\"name\":\"Content-Type\",\"value\":\"text\/html; charset=UTF-8\"},{\"name\":\"Content-Transfer-Encoding\",\"value\":\"7bit\"}],\"commonHeaders\":{\"from\":[\"GetContact <notification@getcontact.com>\"],\"to\":[\"bounce@simulator.amazonses.com\"],\"subject\":\"test email\"}}}",
    "Timestamp": "2017-05-18T09:19:33.938Z",
    "SignatureVersion": "1",
    "Signature": "ByjCovt38P55pwj97GaqF8BAnfTtjeeSN4MoMfSUXb6NPcgTJe+OwmKaXlo\/OAiSxld5FubnTHGcecOcA5cbyzK6uqVjO8Gb0zNZG7QIou8HDRMLoTple0v2OecYc\/KXw1Em0rs2p\/X0sFgZSDjdDuh3kuEd2ipVCHBX3Zek54nHe\/BBqQQiS1I40MT\/EF4PPJZMrkN9DJd5J\/kuXbgSQ4Lka2gqVneaxVMlYBkMQp\/yRf0pahpzor3DQjyo85GlQ4cifo7bZ0ZryIuh2R+wb8B4mZzb05GwksibZKpIOCOS04HebpBnewCAqYYs8W3D4NSkkVYxBFjNy0pQV38IsA==",
    "SigningCertURL": "https:\/\/sns.eu-west-1.amazonaws.com\/SimpleNotificationService-b95095beb82e8f6a046b3aafc7f4149a.pem",
    "UnsubscribeURL": "https:\/\/sns.eu-west-1.amazonaws.com\/?Action=Unsubscribe&SubscriptionArn=arn:aws:sns:eu-west-1:990978750721:getcontact-ses-callback:29e2c7cb-7263-406a-95a9-11ffe41295a8"
}'
        );
        $validatorStub = $this->createMock('\Aws\Sns\MessageValidator');
        $validatorStub->expects($this->any())
            ->method('validate')
            ->willReturn(true);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('SES "notificationType" not defined! SES Type: Complaint, SNS MsgId: b07218ef-d597-5f55-b1ac-4978bcf20073');
        \Teknasyon\AwsSesNotification\Dispatcher::handle(new MyHandler(), $validatorStub);
    }

    public function testHandle()
    {
        file_put_contents(
            'php://input',
            '{
    "Type": "Notification",
    "MessageId": "b07218ef-d597-5f55-b1ac-4978bcf20073",
    "TopicArn": "arn:aws:sns:eu-west-1:990978750721:getcontact-ses-callback",
    "Message": "{\"notificationType\":\"Bounce\",\"bounce\":{\"bounceType\":\"Permanent\",\"bounceSubType\":\"General\",\"bouncedRecipients\":[{\"emailAddress\":\"bounce@simulator.amazonses.com\",\"action\":\"failed\",\"status\":\"5.1.1\",\"diagnosticCode\":\"smtp; 550 5.1.1 user unknown\"}],\"timestamp\":\"2017-05-18T09:19:33.912Z\",\"feedbackId\":\"0102015c1adaebe6-5b7e06a9-770e-4998-be49-c08dd8dc2c7e-000000\",\"remoteMtaIp\":\"205.251.242.49\",\"reportingMTA\":\"dsn; a4-10.smtp-out.eu-west-1.amazonses.com\"},\"mail\":{\"timestamp\":\"2017-05-18T09:19:32.000Z\",\"source\":\"GetContact <notification@getcontact.com>\",\"sourceArn\":\"arn:aws:ses:eu-west-1:990978750721:identity\/notification@getcontact.com\",\"sourceIp\":\"31.145.77.18\",\"sendingAccountId\":\"990978750721\",\"messageId\":\"0102015c1adae824-024ea4c1-197e-47ef-897f-5e7352c9bc36-000000\",\"destination\":[\"bounce@simulator.amazonses.com\"],\"headersTruncated\":false,\"headers\":[{\"name\":\"From\",\"value\":\"GetContact <notification@getcontact.com>\"},{\"name\":\"To\",\"value\":\"bounce@simulator.amazonses.com\"},{\"name\":\"Subject\",\"value\":\"test email\"},{\"name\":\"MIME-Version\",\"value\":\"1.0\"},{\"name\":\"Content-Type\",\"value\":\"text\/html; charset=UTF-8\"},{\"name\":\"Content-Transfer-Encoding\",\"value\":\"7bit\"}],\"commonHeaders\":{\"from\":[\"GetContact <notification@getcontact.com>\"],\"to\":[\"bounce@simulator.amazonses.com\"],\"subject\":\"test email\"}}}",
    "Timestamp": "2017-05-18T09:19:33.938Z",
    "SignatureVersion": "1",
    "Signature": "ByjCovt38P55pwj97GaqF8BAnfTtjeeSN4MoMfSUXb6NPcgTJe+OwmKaXlo\/OAiSxld5FubnTHGcecOcA5cbyzK6uqVjO8Gb0zNZG7QIou8HDRMLoTple0v2OecYc\/KXw1Em0rs2p\/X0sFgZSDjdDuh3kuEd2ipVCHBX3Zek54nHe\/BBqQQiS1I40MT\/EF4PPJZMrkN9DJd5J\/kuXbgSQ4Lka2gqVneaxVMlYBkMQp\/yRf0pahpzor3DQjyo85GlQ4cifo7bZ0ZryIuh2R+wb8B4mZzb05GwksibZKpIOCOS04HebpBnewCAqYYs8W3D4NSkkVYxBFjNy0pQV38IsA==",
    "SigningCertURL": "https:\/\/sns.eu-west-1.amazonaws.com\/SimpleNotificationService-b95095beb82e8f6a046b3aafc7f4149a.pem",
    "UnsubscribeURL": "https:\/\/sns.eu-west-1.amazonaws.com\/?Action=Unsubscribe&SubscriptionArn=arn:aws:sns:eu-west-1:990978750721:getcontact-ses-callback:29e2c7cb-7263-406a-95a9-11ffe41295a8"
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
