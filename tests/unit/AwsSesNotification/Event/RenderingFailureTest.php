<?php

namespace Event;

use PHPUnit\Framework\TestCase;

class RenderingFailureTest extends TestCase
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
    "Message": "{\n\t\"mail\": {\n\t\t\"source\": \"sender@example.com\", \n\t\t\"tags\": {\n\t\t\t\"ses:configuration-set\": [\n\t\t\t\t\"ConfigSet\"\n\t\t\t]\n\t\t}, \n\t\t\"headersTruncated\": false, \n\t\t\"destination\": [\n\t\t\t\"recipient@example.com\"\n\t\t], \n\t\t\"sendingAccountId\": \"123456789012\", \n\t\t\"timestamp\": \"2018-01-22T18:43:06.197Z\", \n\t\t\"messageId\": \"EXAMPLE7c191be45-e9aedb9a-02f9-4d12-a87d-dd0099a07f8a-000000\", \n\t\t\"sourceArn\": \"arn:aws:ses:us-east-1:123456789012:identity/sender@example.com\"\n\t}, \n\t\"eventType\": \"Rendering Failure\", \n\t\"failure\": {\n\t\t\"errorMessage\": \"Attribute \'attributeName\' is not present in the rendering data.\", \n\t\t\"templateName\": \"MyTemplate\"\n\t}\n}",
    "Timestamp": "2017-05-18T09:19:33.938Z",
    "SignatureVersion": "1",
    "Signature": "ByjCovt38P55pwj97GaqF8BAnfTtjeeSN4MoMfSUXb6NPcgTJe+OwmKaXlo\/OAiSxld5FubnTHGcecOcA5cbyzK6uqVjO8Gb0zNZG7QIou8HDRMLoTple0v2OecYc\/KXw1Em0rs2p\/X0sFgZSDjdDuh3kuEd2ipVCHBX3Zek54nHe\/BBqQQiS1I40MT\/EF4PPJZMrkN9DJd5J\/kuXbgSQ4Lka2gqVneaxVMlYBkMQp\/yRf0pahpzor3DQjyo85GlQ4cifo7bZ0ZryIuh2R+wb8B4mZzb05GwksibZKpIOCOS04HebpBnewCAqYYs8W3D4NSkkVYxBFjNy0pQV38IsA==",
    "SigningCertURL": "https:\/\/sns.eu-west-1.amazonaws.com\/SimpleNotificationService-b95095beb82e8f6a046b3aafc7f4149a.pem",
    "UnsubscribeURL": "https:\/\/sns.eu-west-1.amazonaws.com\/?Action=Unsubscribe&SubscriptionArn=arn:aws:sns:eu-west-1:990978750721:getcontact-ses-callback:29e2c7cb-7263-406a-95a9-11ffe41295a8"
    }'
        );
        $this->assertInstanceOf(
            \Teknasyon\AwsSesNotification\Event\RenderingFailureEvent::class,
            \Teknasyon\AwsSesNotification\Event\BaseEvent::factory(\Aws\Sns\Message::fromRawPostData()),
            'Rendering Failure Event creation failed!'
        );
    }

    public function testGetErrorMessage()
    {
        file_put_contents(
            'php://input',
            '{
    "Type": "Notification",
    "MessageId": "b07218ef-d597-5f55-b1ac-4978bcf20073",
    "TopicArn": "arn:aws:sns:eu-west-1:990978750721:getcontact-ses-callback",
    "Message": "{\n\t\"mail\": {\n\t\t\"source\": \"sender@example.com\", \n\t\t\"tags\": {\n\t\t\t\"ses:configuration-set\": [\n\t\t\t\t\"ConfigSet\"\n\t\t\t]\n\t\t}, \n\t\t\"headersTruncated\": false, \n\t\t\"destination\": [\n\t\t\t\"recipient@example.com\"\n\t\t], \n\t\t\"sendingAccountId\": \"123456789012\", \n\t\t\"timestamp\": \"2018-01-22T18:43:06.197Z\", \n\t\t\"messageId\": \"EXAMPLE7c191be45-e9aedb9a-02f9-4d12-a87d-dd0099a07f8a-000000\", \n\t\t\"sourceArn\": \"arn:aws:ses:us-east-1:123456789012:identity/sender@example.com\"\n\t}, \n\t\"eventType\": \"Rendering Failure\", \n\t\"failure\": {\n\t\t\"errorMessage\": \"Attribute \'attributeName\' is not present in the rendering data.\", \n\t\t\"templateName\": \"MyTemplate\"\n\t}\n}",
    "Timestamp": "2017-05-18T09:19:33.938Z",
    "SignatureVersion": "1",
    "Signature": "ByjCovt38P55pwj97GaqF8BAnfTtjeeSN4MoMfSUXb6NPcgTJe+OwmKaXlo\/OAiSxld5FubnTHGcecOcA5cbyzK6uqVjO8Gb0zNZG7QIou8HDRMLoTple0v2OecYc\/KXw1Em0rs2p\/X0sFgZSDjdDuh3kuEd2ipVCHBX3Zek54nHe\/BBqQQiS1I40MT\/EF4PPJZMrkN9DJd5J\/kuXbgSQ4Lka2gqVneaxVMlYBkMQp\/yRf0pahpzor3DQjyo85GlQ4cifo7bZ0ZryIuh2R+wb8B4mZzb05GwksibZKpIOCOS04HebpBnewCAqYYs8W3D4NSkkVYxBFjNy0pQV38IsA==",
    "SigningCertURL": "https:\/\/sns.eu-west-1.amazonaws.com\/SimpleNotificationService-b95095beb82e8f6a046b3aafc7f4149a.pem",
    "UnsubscribeURL": "https:\/\/sns.eu-west-1.amazonaws.com\/?Action=Unsubscribe&SubscriptionArn=arn:aws:sns:eu-west-1:990978750721:getcontact-ses-callback:29e2c7cb-7263-406a-95a9-11ffe41295a8"
}'
        );
        $mailObj = \Teknasyon\AwsSesNotification\Event\BaseEvent::factory(\Aws\Sns\Message::fromRawPostData());
        $this->assertEquals('Attribute \'attributeName\' is not present in the rendering data.',
            $mailObj->getErrorMessage(),
            'Rendering Failure Event getReceipts failed!'
        );
    }
}
