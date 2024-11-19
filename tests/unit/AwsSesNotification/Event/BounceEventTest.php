<?php

namespace Event;

use PHPUnit\Framework\TestCase;

class BounceEventTest extends TestCase
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
    "Message": "{\"eventType\":\"Bounce\",\"bounce\":{\"bounceType\":\"Permanent\",\"bounceSubType\":\"General\",\"bouncedRecipients\":[{\"emailAddress\":\"recipient@example.com\",\"action\":\"failed\",\"status\":\"5.1.1\",\"diagnosticCode\":\"smtp; 550 5.1.1 user unknown\"}],\"timestamp\":\"2017-08-05T00:41:02.669Z\",\"feedbackId\":\"01000157c44f053b-61b59c11-9236-11e6-8f96-7be8aexample-000000\",\"reportingMTA\":\"dsn; mta.example.com\"},\"mail\":{\"timestamp\":\"2017-08-05T00:40:02.012Z\",\"source\":\"Sender Name <sender@example.com>\",\"sourceArn\":\"arn:aws:ses:us-east-1:123456789012:identity/sender@example.com\",\"sendingAccountId\":\"123456789012\",\"messageId\":\"EXAMPLE7c191be45-e9aedb9a-02f9-4d12-a87d-dd0099a07f8a-000000\",\"destination\":[\"recipient@example.com\"],\"headersTruncated\":false,\"headers\":[{\"name\":\"From\",\"value\":\"Sender Name <sender@example.com>\"},{\"name\":\"To\",\"value\":\"recipient@example.com\"},{\"name\":\"Subject\",\"value\":\"Message sent from Amazon SES\"},{\"name\":\"MIME-Version\",\"value\":\"1.0\"}],\"commonHeaders\":{\"from\":[\"Sender Name <sender@example.com>\"],\"to\":[\"recipient@example.com\"],\"messageId\":\"EXAMPLE7c191be45-e9aedb9a-02f9-4d12-a87d-dd0099a07f8a-000000\",\"subject\":\"Message sent from Amazon SES\"},\"tags\":{\"ses:configuration-set\":[\"ConfigSet\"],\"ses:source-ip\":[\"192.0.2.0\"],\"ses:from-domain\":[\"example.com\"],\"ses:caller-identity\":[\"ses_user\"]}}}",
    "Timestamp": "2017-05-18T09:19:33.938Z",
    "SignatureVersion": "1",
    "Signature": "ByjCovt38P55pwj97GaqF8BAnfTtjeeSN4MoMfSUXb6NPcgTJe+OwmKaXlo\/OAiSxld5FubnTHGcecOcA5cbyzK6uqVjO8Gb0zNZG7QIou8HDRMLoTple0v2OecYc\/KXw1Em0rs2p\/X0sFgZSDjdDuh3kuEd2ipVCHBX3Zek54nHe\/BBqQQiS1I40MT\/EF4PPJZMrkN9DJd5J\/kuXbgSQ4Lka2gqVneaxVMlYBkMQp\/yRf0pahpzor3DQjyo85GlQ4cifo7bZ0ZryIuh2R+wb8B4mZzb05GwksibZKpIOCOS04HebpBnewCAqYYs8W3D4NSkkVYxBFjNy0pQV38IsA==",
    "SigningCertURL": "https:\/\/sns.eu-west-1.amazonaws.com\/SimpleNotificationService-b95095beb82e8f6a046b3aafc7f4149a.pem",
    "UnsubscribeURL": "https:\/\/sns.eu-west-1.amazonaws.com\/?Action=Unsubscribe&SubscriptionArn=arn:aws:sns:eu-west-1:990978750721:getcontact-ses-callback:29e2c7cb-7263-406a-95a9-11ffe41295a8"
}'
        );
        $this->assertInstanceOf(
            \Teknasyon\AwsSesNotification\Event\BounceEvent::class,
            \Teknasyon\AwsSesNotification\Event\BaseEvent::factory(\Aws\Sns\Message::fromRawPostData()),
            'Bounce Event creation failed!'
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
    "Message": "{\"eventType\":\"Bounce\",\"bounce\":{\"bounceType\":\"Permanent\",\"bounceSubType\":\"General\",\"bouncedRecipients\":[{\"emailAddress\":\"recipient@example.com\",\"action\":\"failed\",\"status\":\"5.1.1\",\"diagnosticCode\":\"smtp; 550 5.1.1 user unknown\"}],\"timestamp\":\"2017-08-05T00:41:02.669Z\",\"feedbackId\":\"01000157c44f053b-61b59c11-9236-11e6-8f96-7be8aexample-000000\",\"reportingMTA\":\"dsn; mta.example.com\"},\"mail\":{\"timestamp\":\"2017-08-05T00:40:02.012Z\",\"source\":\"Sender Name <sender@example.com>\",\"sourceArn\":\"arn:aws:ses:us-east-1:123456789012:identity/sender@example.com\",\"sendingAccountId\":\"123456789012\",\"messageId\":\"EXAMPLE7c191be45-e9aedb9a-02f9-4d12-a87d-dd0099a07f8a-000000\",\"destination\":[\"recipient@example.com\"],\"headersTruncated\":false,\"headers\":[{\"name\":\"From\",\"value\":\"Sender Name <sender@example.com>\"},{\"name\":\"To\",\"value\":\"recipient@example.com\"},{\"name\":\"Subject\",\"value\":\"Message sent from Amazon SES\"},{\"name\":\"MIME-Version\",\"value\":\"1.0\"}],\"commonHeaders\":{\"from\":[\"Sender Name <sender@example.com>\"],\"to\":[\"recipient@example.com\"],\"messageId\":\"EXAMPLE7c191be45-e9aedb9a-02f9-4d12-a87d-dd0099a07f8a-000000\",\"subject\":\"Message sent from Amazon SES\"},\"tags\":{\"ses:configuration-set\":[\"ConfigSet\"],\"ses:source-ip\":[\"192.0.2.0\"],\"ses:from-domain\":[\"example.com\"],\"ses:caller-identity\":[\"ses_user\"]}}}",
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
            'Bounce Event getReceipts failed!'
        );
    }
}
