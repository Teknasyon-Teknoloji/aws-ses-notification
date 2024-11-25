<?php

namespace Event;

use PHPUnit\Framework\TestCase;

class DeliveryDelayEventTest extends TestCase
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
    "Message": "{\n\t\"mail\": {\n\t\t\"source\": \"sender@example.com\", \n\t\t\"tags\": {\n\t\t\t\"ses:configuration-set\": [\n\t\t\t\t\"ConfigSet\"\n\t\t\t]\n\t\t}, \n\t\t\"headersTruncated\": false, \n\t\t\"destination\": [\n\t\t\t\"recipient@example.com\"\n\t\t], \n\t\t\"sendingAccountId\": \"123456789012\", \n\t\t\"timestamp\": \"2020-06-16T00:15:40.641Z\", \n\t\t\"messageId\": \"EXAMPLE7c191be45-e9aedb9a-02f9-4d12-a87d-dd0099a07f8a-000000\", \n\t\t\"sourceArn\": \"arn:aws:ses:us-east-1:123456789012:identity/sender@example.com\"\n\t}, \n\t\"deliveryDelay\": {\n\t\t\"expirationTime\": \"2020-06-16T00:25:40.914Z\", \n\t\t\"timestamp\": \"2020-06-16T00:25:40.095Z\", \n\t\t\"delayedRecipients\": [\n\t\t\t{\n\t\t\t\t\"status\": \"4.4.1\", \n\t\t\t\t\"diagnosticCode\": \"smtp; 421 4.4.1 Unable to connect to remote host\", \n\t\t\t\t\"emailAddress\": \"recipient@example.com\"\n\t\t\t}\n\t\t], \n\t\t\"delayType\": \"TransientCommunicationFailure\"\n\t}, \n\t\"eventType\": \"DeliveryDelay\"\n}",
    "Timestamp": "2017-05-18T09:19:33.938Z",
    "SignatureVersion": "1",
    "Signature": "ByjCovt38P55pwj97GaqF8BAnfTtjeeSN4MoMfSUXb6NPcgTJe+OwmKaXlo\/OAiSxld5FubnTHGcecOcA5cbyzK6uqVjO8Gb0zNZG7QIou8HDRMLoTple0v2OecYc\/KXw1Em0rs2p\/X0sFgZSDjdDuh3kuEd2ipVCHBX3Zek54nHe\/BBqQQiS1I40MT\/EF4PPJZMrkN9DJd5J\/kuXbgSQ4Lka2gqVneaxVMlYBkMQp\/yRf0pahpzor3DQjyo85GlQ4cifo7bZ0ZryIuh2R+wb8B4mZzb05GwksibZKpIOCOS04HebpBnewCAqYYs8W3D4NSkkVYxBFjNy0pQV38IsA==",
    "SigningCertURL": "https:\/\/sns.eu-west-1.amazonaws.com\/SimpleNotificationService-b95095beb82e8f6a046b3aafc7f4149a.pem",
    "UnsubscribeURL": "https:\/\/sns.eu-west-1.amazonaws.com\/?Action=Unsubscribe&SubscriptionArn=arn:aws:sns:eu-west-1:990978750721:getcontact-ses-callback:29e2c7cb-7263-406a-95a9-11ffe41295a8"
    }'
        );
        $this->assertInstanceOf(
            \Teknasyon\AwsSesNotification\Event\DeliveryDelayEvent::class,
            \Teknasyon\AwsSesNotification\Event\BaseEvent::factory(\Aws\Sns\Message::fromRawPostData()),
            'Delivery Delay Event creation failed!'
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
    "Message": "{\n\t\"mail\": {\n\t\t\"source\": \"sender@example.com\", \n\t\t\"tags\": {\n\t\t\t\"ses:configuration-set\": [\n\t\t\t\t\"ConfigSet\"\n\t\t\t]\n\t\t}, \n\t\t\"headersTruncated\": false, \n\t\t\"destination\": [\n\t\t\t\"recipient@example.com\"\n\t\t], \n\t\t\"sendingAccountId\": \"123456789012\", \n\t\t\"timestamp\": \"2020-06-16T00:15:40.641Z\", \n\t\t\"messageId\": \"EXAMPLE7c191be45-e9aedb9a-02f9-4d12-a87d-dd0099a07f8a-000000\", \n\t\t\"sourceArn\": \"arn:aws:ses:us-east-1:123456789012:identity/sender@example.com\"\n\t}, \n\t\"deliveryDelay\": {\n\t\t\"expirationTime\": \"2020-06-16T00:25:40.914Z\", \n\t\t\"timestamp\": \"2020-06-16T00:25:40.095Z\", \n\t\t\"delayedRecipients\": [\n\t\t\t{\n\t\t\t\t\"status\": \"4.4.1\", \n\t\t\t\t\"diagnosticCode\": \"smtp; 421 4.4.1 Unable to connect to remote host\", \n\t\t\t\t\"emailAddress\": \"recipient@example.com\"\n\t\t\t}\n\t\t], \n\t\t\"delayType\": \"TransientCommunicationFailure\"\n\t}, \n\t\"eventType\": \"DeliveryDelay\"\n}",
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
            'Delivery Delay Event getReceipts failed!'
        );
    }
}
