<?php

namespace Event;

use PHPUnit\Framework\TestCase;

class ComplaintEventTest extends TestCase
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
    "Message": "{\n\t\"complaint\": {\n\t\t\"complainedRecipients\": [\n\t\t\t{\n\t\t\t\t\"emailAddress\": \"recipient@example.com\"\n\t\t\t}\n\t\t], \n\t\t\"userAgent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.90 Safari/537.36\", \n\t\t\"feedbackId\": \"01000157c44f053b-61b59c11-9236-11e6-8f96-7be8aexample-000000\", \n\t\t\"arrivalDate\": \"2017-08-05T00:41:02.669Z\", \n\t\t\"timestamp\": \"2017-08-05T00:41:02.669Z\", \n\t\t\"complaintFeedbackType\": \"abuse\"\n\t}, \n\t\"mail\": {\n\t\t\"commonHeaders\": {\n\t\t\t\"to\": [\n\t\t\t\t\"recipient@example.com\"\n\t\t\t], \n\t\t\t\"messageId\": \"EXAMPLE7c191be45-e9aedb9a-02f9-4d12-a87d-dd0099a07f8a-000000\", \n\t\t\t\"from\": [\n\t\t\t\t\"Sender Name <sender@example.com>\"\n\t\t\t], \n\t\t\t\"subject\": \"Message sent from Amazon SES\"\n\t\t}, \n\t\t\"source\": \"Sender Name <sender@example.com>\", \n\t\t\"tags\": {\n\t\t\t\"ses:caller-identity\": [\n\t\t\t\t\"ses_user\"\n\t\t\t], \n\t\t\t\"ses:from-domain\": [\n\t\t\t\t\"example.com\"\n\t\t\t], \n\t\t\t\"ses:configuration-set\": [\n\t\t\t\t\"ConfigSet\"\n\t\t\t], \n\t\t\t\"ses:source-ip\": [\n\t\t\t\t\"192.0.2.0\"\n\t\t\t]\n\t\t}, \n\t\t\"headersTruncated\": false, \n\t\t\"destination\": [\n\t\t\t\"recipient@example.com\"\n\t\t], \n\t\t\"sendingAccountId\": \"123456789012\", \n\t\t\"timestamp\": \"2017-08-05T00:40:01.123Z\", \n\t\t\"messageId\": \"EXAMPLE7c191be45-e9aedb9a-02f9-4d12-a87d-dd0099a07f8a-000000\", \n\t\t\"headers\": [\n\t\t\t{\n\t\t\t\t\"value\": \"Sender Name <sender@example.com>\", \n\t\t\t\t\"name\": \"From\"\n\t\t\t}, \n\t\t\t{\n\t\t\t\t\"value\": \"recipient@example.com\", \n\t\t\t\t\"name\": \"To\"\n\t\t\t}, \n\t\t\t{\n\t\t\t\t\"value\": \"Message sent from Amazon SES\", \n\t\t\t\t\"name\": \"Subject\"\n\t\t\t}, \n\t\t\t{\n\t\t\t\t\"value\": \"1.0\", \n\t\t\t\t\"name\": \"MIME-Version\"\n\t\t\t}, \n\t\t\t{\n\t\t\t\t\"value\": \"multipart/alternative; boundary=----=_Part_7298998_679725522.1516840859643\", \n\t\t\t\t\"name\": \"Content-Type\"\n\t\t\t}\n\t\t], \n\t\t\"sourceArn\": \"arn:aws:ses:us-east-1:123456789012:identity/sender@example.com\"\n\t}, \n\t\"eventType\": \"Complaint\"\n}",
    "Timestamp": "2017-05-18T09:19:33.938Z",
    "SignatureVersion": "1",
    "Signature": "ByjCovt38P55pwj97GaqF8BAnfTtjeeSN4MoMfSUXb6NPcgTJe+OwmKaXlo\/OAiSxld5FubnTHGcecOcA5cbyzK6uqVjO8Gb0zNZG7QIou8HDRMLoTple0v2OecYc\/KXw1Em0rs2p\/X0sFgZSDjdDuh3kuEd2ipVCHBX3Zek54nHe\/BBqQQiS1I40MT\/EF4PPJZMrkN9DJd5J\/kuXbgSQ4Lka2gqVneaxVMlYBkMQp\/yRf0pahpzor3DQjyo85GlQ4cifo7bZ0ZryIuh2R+wb8B4mZzb05GwksibZKpIOCOS04HebpBnewCAqYYs8W3D4NSkkVYxBFjNy0pQV38IsA==",
    "SigningCertURL": "https:\/\/sns.eu-west-1.amazonaws.com\/SimpleNotificationService-b95095beb82e8f6a046b3aafc7f4149a.pem",
    "UnsubscribeURL": "https:\/\/sns.eu-west-1.amazonaws.com\/?Action=Unsubscribe&SubscriptionArn=arn:aws:sns:eu-west-1:990978750721:getcontact-ses-callback:29e2c7cb-7263-406a-95a9-11ffe41295a8"
    }'
        );
        $this->assertInstanceOf(
            \Teknasyon\AwsSesNotification\Event\ComplaintEvent::class,
            \Teknasyon\AwsSesNotification\Event\BaseEvent::factory(\Aws\Sns\Message::fromRawPostData()),
            'Complaint Event creation failed!'
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
    "Message": "{\n\t\"complaint\": {\n\t\t\"complainedRecipients\": [\n\t\t\t{\n\t\t\t\t\"emailAddress\": \"recipient@example.com\"\n\t\t\t}\n\t\t], \n\t\t\"userAgent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.90 Safari/537.36\", \n\t\t\"feedbackId\": \"01000157c44f053b-61b59c11-9236-11e6-8f96-7be8aexample-000000\", \n\t\t\"arrivalDate\": \"2017-08-05T00:41:02.669Z\", \n\t\t\"timestamp\": \"2017-08-05T00:41:02.669Z\", \n\t\t\"complaintFeedbackType\": \"abuse\"\n\t}, \n\t\"mail\": {\n\t\t\"commonHeaders\": {\n\t\t\t\"to\": [\n\t\t\t\t\"recipient@example.com\"\n\t\t\t], \n\t\t\t\"messageId\": \"EXAMPLE7c191be45-e9aedb9a-02f9-4d12-a87d-dd0099a07f8a-000000\", \n\t\t\t\"from\": [\n\t\t\t\t\"Sender Name <sender@example.com>\"\n\t\t\t], \n\t\t\t\"subject\": \"Message sent from Amazon SES\"\n\t\t}, \n\t\t\"source\": \"Sender Name <sender@example.com>\", \n\t\t\"tags\": {\n\t\t\t\"ses:caller-identity\": [\n\t\t\t\t\"ses_user\"\n\t\t\t], \n\t\t\t\"ses:from-domain\": [\n\t\t\t\t\"example.com\"\n\t\t\t], \n\t\t\t\"ses:configuration-set\": [\n\t\t\t\t\"ConfigSet\"\n\t\t\t], \n\t\t\t\"ses:source-ip\": [\n\t\t\t\t\"192.0.2.0\"\n\t\t\t]\n\t\t}, \n\t\t\"headersTruncated\": false, \n\t\t\"destination\": [\n\t\t\t\"recipient@example.com\"\n\t\t], \n\t\t\"sendingAccountId\": \"123456789012\", \n\t\t\"timestamp\": \"2017-08-05T00:40:01.123Z\", \n\t\t\"messageId\": \"EXAMPLE7c191be45-e9aedb9a-02f9-4d12-a87d-dd0099a07f8a-000000\", \n\t\t\"headers\": [\n\t\t\t{\n\t\t\t\t\"value\": \"Sender Name <sender@example.com>\", \n\t\t\t\t\"name\": \"From\"\n\t\t\t}, \n\t\t\t{\n\t\t\t\t\"value\": \"recipient@example.com\", \n\t\t\t\t\"name\": \"To\"\n\t\t\t}, \n\t\t\t{\n\t\t\t\t\"value\": \"Message sent from Amazon SES\", \n\t\t\t\t\"name\": \"Subject\"\n\t\t\t}, \n\t\t\t{\n\t\t\t\t\"value\": \"1.0\", \n\t\t\t\t\"name\": \"MIME-Version\"\n\t\t\t}, \n\t\t\t{\n\t\t\t\t\"value\": \"multipart/alternative; boundary=----=_Part_7298998_679725522.1516840859643\", \n\t\t\t\t\"name\": \"Content-Type\"\n\t\t\t}\n\t\t], \n\t\t\"sourceArn\": \"arn:aws:ses:us-east-1:123456789012:identity/sender@example.com\"\n\t}, \n\t\"eventType\": \"Complaint\"\n}",
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
            'Complaint Event getReceipts failed!'
        );
    }
}