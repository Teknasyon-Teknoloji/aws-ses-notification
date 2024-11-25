<?php

namespace unit\AwsSesNotification\Event;

use PHPUnit\Framework\TestCase;

class OpenEventTest extends TestCase
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

    public function testFactory()
    {
        file_put_contents(
            'php://input',
            '{
    "Type": "Notification",
    "MessageId": "b07218ef-d597-5f55-b1ac-4978bcf20073",
    "TopicArn": "arn:aws:sns:eu-west-1:990978750721:getcontact-ses-callback",
    "Message": "{\"eventType\":\"Open\",\"mail\":{\"commonHeaders\":{\"from\":[\"sender@example.com\"],\"messageId\":\"EXAMPLE7c191be45-e9aedb9a-02f9-4d12-a87d-dd0099a07f8a-000000\",\"subject\":\"Message sent from Amazon SES\",\"to\":[\"recipient@example.com\"]},\"destination\":[\"recipient@example.com\"],\"headers\":[{\"name\":\"X-SES-CONFIGURATION-SET\",\"value\":\"ConfigSet\"},{\"name\":\"X-SES-MESSAGE-TAGS\",\"value\":\"myCustomTag1=myCustomValue1, myCustomTag2=myCustomValue2\"},{\"name\":\"From\",\"value\":\"sender@example.com\"},{\"name\":\"To\",\"value\":\"recipient@example.com\"},{\"name\":\"Subject\",\"value\":\"Message sent from Amazon SES\"},{\"name\":\"MIME-Version\",\"value\":\"1.0\"},{\"name\":\"Content-Type\",\"value\":\"text/html; charset=UTF-8\"}],\"headersTruncated\":false,\"messageId\":\"EXAMPLE7c191be45-e9aedb9a-02f9-4d12-a87d-dd0099a07f8a-000000\",\"sendingAccountId\":\"123456789012\",\"source\":\"sender@example.com\",\"tags\":{\"myCustomTag1\":[\"myCustomValue1\"],\"myCustomTag2\":[\"myCustomValue2\"],\"ses:caller-identity\":[\"IAM_user_or_role_name\"],\"ses:configuration-set\":[\"ConfigSet\"],\"ses:from-domain\":[\"example.com\"],\"ses:source-ip\":[\"192.0.2.0\"]},\"timestamp\":\"2017-08-09T21:59:49.927Z\"},\"open\":{\"ipAddress\":\"192.0.2.1\",\"timestamp\":\"2017-08-09T22:00:19.652Z\",\"userAgent\":\"Mozilla/5.0 (iPhone; CPU iPhone OS 10_3_3 like Mac OS X) AppleWebKit/603.3.8 (KHTML, like Gecko) Mobile/14G60\"}}",
    "Timestamp": "2017-05-18T09:19:33.938Z",
    "SignatureVersion": "1",
    "Signature": "ByjCovt38P55pwj97GaqF8BAnfTtjeeSN4MoMfSUXb6NPcgTJe+OwmKaXlo\/OAiSxld5FubnTHGcecOcA5cbyzK6uqVjO8Gb0zNZG7QIou8HDRMLoTple0v2OecYc\/KXw1Em0rs2p\/X0sFgZSDjdDuh3kuEd2ipVCHBX3Zek54nHe\/BBqQQiS1I40MT\/EF4PPJZMrkN9DJd5J\/kuXbgSQ4Lka2gqVneaxVMlYBkMQp\/yRf0pahpzor3DQjyo85GlQ4cifo7bZ0ZryIuh2R+wb8B4mZzb05GwksibZKpIOCOS04HebpBnewCAqYYs8W3D4NSkkVYxBFjNy0pQV38IsA==",
    "SigningCertURL": "https:\/\/sns.eu-west-1.amazonaws.com\/SimpleNotificationService-b95095beb82e8f6a046b3aafc7f4149a.pem",
    "UnsubscribeURL": "https:\/\/sns.eu-west-1.amazonaws.com\/?Action=Unsubscribe&SubscriptionArn=arn:aws:sns:eu-west-1:990978750721:getcontact-ses-callback:29e2c7cb-7263-406a-95a9-11ffe41295a8"
}'
        );
        $this->assertInstanceOf(
            \Teknasyon\AwsSesNotification\Event\OpenEvent::class,
            \Teknasyon\AwsSesNotification\Event\BaseEvent::factory(\Aws\Sns\Message::fromRawPostData()),
            'Open Event creation failed!'
        );
    }

    public function testGetReceipts()
    {
        file_put_contents(
            'php://input',
            '{
    "Type": "Notification",
    "MessageId": "b07218ef-d597-5f55-b1ac-4978bcf20073",
    "TopicArn": "arn:aws:sns:eu-west-1:990978750721:getcontact-ses-callback",
    "Message": "{\"eventType\":\"Open\",\"mail\":{\"commonHeaders\":{\"from\":[\"sender@example.com\"],\"messageId\":\"EXAMPLE7c191be45-e9aedb9a-02f9-4d12-a87d-dd0099a07f8a-000000\",\"subject\":\"Message sent from Amazon SES\",\"to\":[\"recipient@example.com\"]},\"destination\":[\"recipient@example.com\"],\"headers\":[{\"name\":\"X-SES-CONFIGURATION-SET\",\"value\":\"ConfigSet\"},{\"name\":\"X-SES-MESSAGE-TAGS\",\"value\":\"myCustomTag1=myCustomValue1, myCustomTag2=myCustomValue2\"},{\"name\":\"From\",\"value\":\"sender@example.com\"},{\"name\":\"To\",\"value\":\"recipient@example.com\"},{\"name\":\"Subject\",\"value\":\"Message sent from Amazon SES\"},{\"name\":\"MIME-Version\",\"value\":\"1.0\"},{\"name\":\"Content-Type\",\"value\":\"text/html; charset=UTF-8\"}],\"headersTruncated\":false,\"messageId\":\"EXAMPLE7c191be45-e9aedb9a-02f9-4d12-a87d-dd0099a07f8a-000000\",\"sendingAccountId\":\"123456789012\",\"source\":\"sender@example.com\",\"tags\":{\"myCustomTag1\":[\"myCustomValue1\"],\"myCustomTag2\":[\"myCustomValue2\"],\"ses:caller-identity\":[\"IAM_user_or_role_name\"],\"ses:configuration-set\":[\"ConfigSet\"],\"ses:from-domain\":[\"example.com\"],\"ses:source-ip\":[\"192.0.2.0\"]},\"timestamp\":\"2017-08-09T21:59:49.927Z\"},\"open\":{\"ipAddress\":\"192.0.2.1\",\"timestamp\":\"2017-08-09T22:00:19.652Z\",\"userAgent\":\"Mozilla/5.0 (iPhone; CPU iPhone OS 10_3_3 like Mac OS X) AppleWebKit/603.3.8 (KHTML, like Gecko) Mobile/14G60\"}}",
    "Timestamp": "2017-05-18T09:19:33.938Z",
    "SignatureVersion": "1",
    "Signature": "ByjCovt38P55pwj97GaqF8BAnfTtjeeSN4MoMfSUXb6NPcgTJe+OwmKaXlo\/OAiSxld5FubnTHGcecOcA5cbyzK6uqVjO8Gb0zNZG7QIou8HDRMLoTple0v2OecYc\/KXw1Em0rs2p\/X0sFgZSDjdDuh3kuEd2ipVCHBX3Zek54nHe\/BBqQQiS1I40MT\/EF4PPJZMrkN9DJd5J\/kuXbgSQ4Lka2gqVneaxVMlYBkMQp\/yRf0pahpzor3DQjyo85GlQ4cifo7bZ0ZryIuh2R+wb8B4mZzb05GwksibZKpIOCOS04HebpBnewCAqYYs8W3D4NSkkVYxBFjNy0pQV38IsA==",
    "SigningCertURL": "https:\/\/sns.eu-west-1.amazonaws.com\/SimpleNotificationService-b95095beb82e8f6a046b3aafc7f4149a.pem",
    "UnsubscribeURL": "https:\/\/sns.eu-west-1.amazonaws.com\/?Action=Unsubscribe&SubscriptionArn=arn:aws:sns:eu-west-1:990978750721:getcontact-ses-callback:29e2c7cb-7263-406a-95a9-11ffe41295a8"
}'
        );
        $mailObj = \Teknasyon\AwsSesNotification\Event\BaseEvent::factory(\Aws\Sns\Message::fromRawPostData());
        $this->assertEquals(['recipient@example.com'],
            $mailObj->getReceipts(),
            'Open Event getReceipts failed!'
        );
    }
}
