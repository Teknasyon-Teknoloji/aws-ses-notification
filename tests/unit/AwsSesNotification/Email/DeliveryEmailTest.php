<?php

use PHPUnit\Framework\TestCase;

class DeliveryEmailTest extends TestCase
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

    public function testFactory()
    {
        file_put_contents(
            'php://input',
            '{
    "Type": "Notification",
    "MessageId": "b07218ef-d597-5f55-b1ac-4978bcf20073",
    "TopicArn": "arn:aws:sns:eu-west-1:990978750721:getcontact-ses-callback",
    "Message": "{\"notificationType\":\"Delivery\",\"mail\":{\"timestamp\":\"2017-05-18T09:19:32.000Z\",\"source\":\"GetContact <notification@getcontact.com>\",\"sourceArn\":\"arn:aws:ses:eu-west-1:990978750721:identity\/notification@getcontact.com\",\"sourceIp\":\"31.145.77.18\",\"sendingAccountId\":\"990978750721\",\"messageId\":\"0102015c1adae824-024ea4c1-197e-47ef-897f-5e7352c9bc36-000000\",\"destination\":[\"bounce@simulator.amazonses.com\"],\"headersTruncated\":false,\"headers\":[{\"name\":\"From\",\"value\":\"GetContact <notification@getcontact.com>\"},{\"name\":\"To\",\"value\":\"bounce@simulator.amazonses.com\"},{\"name\":\"Subject\",\"value\":\"test email\"},{\"name\":\"MIME-Version\",\"value\":\"1.0\"},{\"name\":\"Content-Type\",\"value\":\"text\/html; charset=UTF-8\"},{\"name\":\"Content-Transfer-Encoding\",\"value\":\"7bit\"}],\"commonHeaders\":{\"from\":[\"GetContact <notification@getcontact.com>\"],\"to\":[\"bounce@simulator.amazonses.com\"],\"subject\":\"test email\"}}}",
    "Timestamp": "2017-05-18T09:19:33.938Z",
    "SignatureVersion": "1",
    "Signature": "ByjCovt38P55pwj97GaqF8BAnfTtjeeSN4MoMfSUXb6NPcgTJe+OwmKaXlo\/OAiSxld5FubnTHGcecOcA5cbyzK6uqVjO8Gb0zNZG7QIou8HDRMLoTple0v2OecYc\/KXw1Em0rs2p\/X0sFgZSDjdDuh3kuEd2ipVCHBX3Zek54nHe\/BBqQQiS1I40MT\/EF4PPJZMrkN9DJd5J\/kuXbgSQ4Lka2gqVneaxVMlYBkMQp\/yRf0pahpzor3DQjyo85GlQ4cifo7bZ0ZryIuh2R+wb8B4mZzb05GwksibZKpIOCOS04HebpBnewCAqYYs8W3D4NSkkVYxBFjNy0pQV38IsA==",
    "SigningCertURL": "https:\/\/sns.eu-west-1.amazonaws.com\/SimpleNotificationService-b95095beb82e8f6a046b3aafc7f4149a.pem",
    "UnsubscribeURL": "https:\/\/sns.eu-west-1.amazonaws.com\/?Action=Unsubscribe&SubscriptionArn=arn:aws:sns:eu-west-1:990978750721:getcontact-ses-callback:29e2c7cb-7263-406a-95a9-11ffe41295a8"
}'
        );
        $this->assertInstanceOf(
            \Teknasyon\AwsSesNotification\Email\DeliveryEmail::class,
            \Teknasyon\AwsSesNotification\Email\BaseEmail::factory(\Aws\Sns\Message::fromRawPostData()),
            'DeliveryEmail creation failed!'
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
    "Message": "{\"notificationType\":\"Delivery\",\"mail\":{\"timestamp\":\"2017-05-18T09:19:32.000Z\",\"source\":\"GetContact <notification@getcontact.com>\",\"sourceArn\":\"arn:aws:ses:eu-west-1:990978750721:identity\/notification@getcontact.com\",\"sourceIp\":\"31.145.77.18\",\"sendingAccountId\":\"990978750721\",\"messageId\":\"0102015c1adae824-024ea4c1-197e-47ef-897f-5e7352c9bc36-000000\",\"destination\":[\"bounce@simulator.amazonses.com\"],\"headersTruncated\":false,\"headers\":[{\"name\":\"From\",\"value\":\"GetContact <notification@getcontact.com>\"},{\"name\":\"To\",\"value\":\"bounce@simulator.amazonses.com\"},{\"name\":\"Subject\",\"value\":\"test email\"},{\"name\":\"MIME-Version\",\"value\":\"1.0\"},{\"name\":\"Content-Type\",\"value\":\"text\/html; charset=UTF-8\"},{\"name\":\"Content-Transfer-Encoding\",\"value\":\"7bit\"}],\"commonHeaders\":{\"from\":[\"GetContact <notification@getcontact.com>\"],\"to\":[\"bounce@simulator.amazonses.com\"],\"subject\":\"test email\"}}}",
    "Timestamp": "2017-05-18T09:19:33.938Z",
    "SignatureVersion": "1",
    "Signature": "fakesig",
    "SigningCertURL": "https:\/\/sns.eu-west-1.amazonaws.com\/fake.pem",
    "UnsubscribeURL": "https:\/\/sns.eu-west-1.amazonaws.com\/?Action=Unsubscribe&SubscriptionArn=arn:aws:sns:eu-west-1:990978750721:getcontact-ses-callback:29e2c7cb-7263-406a-95a9-11ffe41295a8"
}'
        );
        $mailObj = \Teknasyon\AwsSesNotification\Email\BaseEmail::factory(\Aws\Sns\Message::fromRawPostData());
        $this->assertEquals([],
            $mailObj->getReceipts(),
            'DeliveryEmail getReceipts failed!'
        );
        $this->assertEquals(
            ["bounce@simulator.amazonses.com"],
            $mailObj->getDestination(),
            'DeliveryEmail getDestination failed!'
        );
        $this->assertTrue($mailObj->isDelivery(), 'DeliveryEmail isBounced failed!');
        $this->assertFalse($mailObj->shouldRemoved(), 'DeliveryEmail shouldRemoved failed!');
        $this->assertNull($mailObj->getSesMessage(), 'DeliveryEmail getSesMessage failed!');
        $this->assertEquals('notification@getcontact.com', $mailObj->getSource(),'DeliveryEmail getSource failed!');
        $this->assertEquals(
            '31.145.77.18',
            $mailObj->getSourceIp(),
            'DeliveryEmail getSourceIp failed!'
        );
        $this->assertEquals(
            '0102015c1adae824-024ea4c1-197e-47ef-897f-5e7352c9bc36-000000',
            $mailObj->getMessageId(),
            'DeliveryEmail getMessageId failed!'
        );
        $this->assertEquals(
            [
                "from"=>[
        "GetContact <notification@getcontact.com>"
    ],
             "to"=>[
        "bounce@simulator.amazonses.com"
    ],
             "subject"=>"test email"
            ],
            $mailObj->getCommonHeaders(),
            'DeliveryEmail getCommonHeaders failed!'
        );
        $this->assertEquals(
            [
                "GetContact <notification@getcontact.com>"
            ],
            $mailObj->getHeaders('from'),
            'DeliveryEmail getHeaders failed!'
        );
    }
}
