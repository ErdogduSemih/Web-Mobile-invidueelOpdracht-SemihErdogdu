<?php

use App\Model\PDOMessageModel;
use PHPUnit\Framework\TestCase;
use App\Model\Connection;

class PDOMessageModelTest extends TestCase
{
    private $connection = null;

    public function setUp()
    {
        $this->connection = new Connection('sqlite::memory:');
        //TODO category
        $pdo = $this->connection->getPDO();
        $pdo->exec('CREATE TABLE IF NOT EXISTS Message (
            Id int(11),
            Contents varchar(200) NOT NULL,
            Category varchar(20) NOT NULL,
            Upvotes varchar(1000),
            Downvotes varchar(1000),
            PRIMARY KEY(Id));

            CREATE TABLE IF NOT EXISTS Comment (
            Id int(11),
            Contents varchar(200) NOT NULL,
            Token varchar(10) NOT NULL,
            Message_Id int(11),
            PRIMARY KEY(Id));');

        $messages = $this->providerMessages();
        foreach ($messages as $message) {
            $pdo->exec("INSERT INTO Message (Id, Contents, Category, Upvotes, Downvotes) VALUES ("
                . $message['id'] . ",'" . $message['contents'] . "','" . $message['category'] . "','" . $message['upvotes'] . "','" . $message['downvotes'] . "');");
        }

        $comments = $this->providerComments();
        foreach ($comments as $comment) {
            $pdo->exec("INSERT INTO Comment (Contents, Token, Message_Id) VALUES ("
                . "'" . $comment['contents'] . "','" . $comment['token'] . "'," . $comment['messageId'] . ");");
        }
    }

    public function tearDown()
    {
        $this->connection = null;
    }

    public function providerMessages()
    {
        return [['id' => '1', 'contents' => 'testcontents1', 'category' => 'testcategory1', 'upvotes' => '1', 'downvotes' => '1'],
            ['id' => '2', 'contents' => 'testcontents2', 'category' => 'testcategory2', 'upvotes' => '1}', 'downvotes' => '1'],
            ['id' => '3', 'contents' => 'testcontents3', 'category' => 'testcategory3', 'upvotes' => '1', 'downvotes' => '1'],
            ['id' => '4', 'contents' => 'dit is een zin', 'category' => 'testcategory4', 'upvotes' => '1', 'downvotes' => '1'],
            ['id' => '5', 'contents' => 'dit is een message voor de testen', 'category' => 'testcategory4', 'upvotes' => '1', 'downvotes' => '1'],
            ['id' => '6', 'contents' => 'message over een test voor phpstorm', 'category' => 'testcategory5', 'upvotes' => '1', 'downvotes' => '1']];
    }

    // TODO remove
    public function expectedMessages()
    {
        return [['id' => '1', 'contents' => 'testcontents1', 'category' => 'testcategory1', 'upvotes' => '1', 'downvotes' => '1'],
            ['id' => '2', 'contents' => 'testcontents2', 'category' => 'testcategory2', 'upvotes' => '1', 'downvotes' => '1'],
            ['id' => '3', 'contents' => 'testcontents3', 'category' => 'testcategory3', 'upvotes' => '1', 'downvotes' => '1'],
            ['id' => '4', 'contents' => 'dit is een zin', 'category' => 'testcategory4', 'upvotes' => '1', 'downvotes' => '1'],
            ['id' => '5', 'contents' => 'dit is een message voor de testen', 'category' => 'testcategory4', 'upvotes' => '1', 'downvotes' => '1'],
            ['id' => '6', 'contents' => 'message over een test voor phpstorm', 'category' => 'testcategory5', 'upvotes' => '1', 'downvotes' => '1']];
    }

    public function providerComments()
    {
        return [['contents' => 'testcomment1', 'token' => 'a000000001', 'messageId' => '1'],
            ['contents' => 'testcomment2', 'token' => 'a000000002', 'messageId' => '1'],
            ['contents' => 'testcomment3', 'token' => 'a000000003', 'messageId' => '2']];
    }

    public function providervalidExistingIds()
    {
        return [['1'], ['2'], ['3']];
    }

    public function providervalidUnexistingIds()
    {
        return [['20'], ['50']];
    }

    public function providerInvalidIds()
    {
        return [['0'], ['-1'], ['1.2'], ["aaa"], [12], [1.2]];
    }

    public function testGetMessages_MessageInDatabase_ArrayMessages()
    {
        $messageModel = new PDOMessageModel($this->connection);
        $actualMessages = $messageModel->getMessages();
        $expectedMessages = $this->expectedMessages();
        $this->assertEquals('array', gettype($actualMessages));
        $this->assertEquals(count($expectedMessages), count($actualMessages));
        foreach ($actualMessages as $actualMessage) {
            $this->assertContains($actualMessage, $expectedMessages);
        }
    }

    public function testGetMessages_NoMessagesInDatabase_EmptyArray()
    {
        $pdo = $this->connection->getPDO();
        $pdo->exec('DROP TABLE Message');
        $pdo->exec('CREATE TABLE IF NOT EXISTS Message (
            Id int(11),
            Contents varchar(200) NOT NULL,
            Category varchar(20) NOT NULL,
            Upvotes varchar(1000),
            Downvotes varchar(1000),
            PRIMARY KEY(Id));

            CREATE TABLE IF NOT EXISTS Comment (
            Id int(11),
            Contents varchar(200) NOT NULL,
            Token varchar(10) NOT NULL,
            Message_Id int(11),
            PRIMARY KEY(Id));');

        $messageModel = new PDOMessageModel($this->connection);
        $actualMessages = $messageModel->getMessages();
        $this->assertEquals('array', gettype($actualMessages));
        $this->assertEquals(0, count($actualMessages));
    }

    /**
     * @expectedException \PDOException
     */
    public function testGetMessages_NoTableMessages_PDOException()
    {
        $pdo = $this->connection->getPDO();
        $pdo->exec('DROP TABLE messages');
        $messageModel = new PDOMessageModel($this->connection);
        $messageModel->getMessages();
    }

    /**
     * @dataProvider providervalidExistingIds
     */
    public function testGetMessageById_MessageInDatabase_Message($id)
    {
        $messageModel = new PDOMessageModel($this->connection);
        $actualMessage = $messageModel->getMessageById($id);
        $expectedMessage = $this->expectedMessages();
        $this->assertContains($actualMessage, $expectedMessage);
    }

    /**
     * @dataProvider providervalidUnexistingIds
     */
    public function testGetMessageById_MessageNotInDatabase_Null($id)
    {
        $messageModel = new PDOMessageModel($this->connection);
        $actualMessage = $messageModel->getMessageById($id);
        $this->assertNull($actualMessage);
    }

    /**
     * @expectedException \PDOException
     * @dataProvider providervalidExistingIds
     */
    public function testGetMessageById_NoTableMessages_PDOException($id)
    {
        $pdo = $this->connection->getPDO();
        $pdo->exec('DROP TABLE messages');
        $messageModel = new PDOMessageModel($this->connection);
        $messageModel->getMessageById($id);
    }

    /**
     * @dataProvider providervalidExistingIds
     */
    public function testIdExists_ValidId_True($id)
    {
        $messageModel = new PDOMessageModel($this->connection);
        $actual = $messageModel->idExists($id);
        $this->assertTrue($actual);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @dataProvider providerInvalidIds
     */
    public function testIdExists_InvalidId_InvalidArgumentException($id)
    {
        $messageModel = new PDOMessageModel($this->connection);
        $actual = $messageModel->idExists($id);
    }

    /**
     * @dataProvider providerValidExistingIds
     */
    public function testIdExists_ExistingId_True($id)
    {
        $messageModel = new PDOMessageModel($this->connection);
        $this->assertTrue($messageModel->idExists($id));
    }

    /**
     * @dataProvider providerValidUnexistingIds
     */
    public function testIdExists_UnexistingId_False($id)
    {
        $messageModel = new PDOMessageModel($this->connection);
        $this->assertFalse($messageModel->idExists($id));
    }

    public function testFindMessage_PassingOneSearchTerm_OneResult()
    {
        $messageModel = new PDOMessageModel($this->connection);
        $actualMessage = $messageModel->getMessagesBySearchInMessage("testcontents1")[0];
        $expectedMessage = $this->expectedMessages()[0];
        $this->assertTrue($actualMessage == $expectedMessage);
    }

    public function testFindMessage_PassingMultipleSearchTerms_OneResult()
    {
        $messageModel = new PDOMessageModel($this->connection);
        $actualMessage = $messageModel->getMessagesBySearchInMessage("message over een test voor phpstorm")[0];
        $expectedMessage = $this->expectedMessages()[5];
        $this->assertTrue($actualMessage == $expectedMessage);
    }

    public function testFindMessage_PassingMultipleSearchTerms_MultipleResults()
    {
        $messageModel = new PDOMessageModel($this->connection);
        $actualMessages = $messageModel->getMessagesBySearchInMessage("dit is");
        $expectedMessages = [];
        array_push($expectedMessages, $this->expectedMessages()[3]);
        array_push($expectedMessages, $this->expectedMessages()[4]);
        $this->assertEquals(count($expectedMessages), count($actualMessages));
        $this->assertTrue($expectedMessages == $actualMessages);
    }

    public function testFindMessage_PassingOneSearchTermAndCategory_OneMessage()
    {
        $messageModel = new PDOMessageModel($this->connection);
        $actualMessage = $messageModel->getMessagesBySearchInMessageAndCategory("testen", "testcategory4")[0];
        $expectedMessage = $this->expectedMessages()[4];
        $this->assertTrue($expectedMessage == $actualMessage);
    }

    /**
     * @dataProvider providerComments
     */
    public function testAddCommentByMessageId_AddCommentToDatabase_Comment($contents, $token, $messageId)
    {
        $messageModel = new PDOMessageModel($this->connection);
        $actualComment = $messageModel->addCommentByMessageId($contents, $token, $messageId);
        $expectedComment = $this->providerComments();
        $this->assertContains($actualComment, $expectedComment);
    }

    /**
     * @dataProvider providervalidExistingIds
     */
    public function testGetMessageUpvotes_MessageExist($id)
    {
        $messageModel = new PDOMessageModel($this->connection);
        $actualUpvotes = $messageModel->getMessageUpvotes($id);
        $this->assertTrue($actualUpvotes != null);
    }

    /**
     * @dataProvider providervalidUnexistingIds
     */
    public function testGetMessageUpvotes_MessageNotInDatabase_Null($id)
    {
        $messageModel = new PDOMessageModel($this->connection);
        $actualMessage = $messageModel->getMessageUpvotes($id);
        $this->assertNull($actualMessage);
    }

    /**
     * @expectedException \PDOException
     * @dataProvider providervalidExistingIds
     */
    public function testGetMessageUpvotes_noTableMessages_PDOException($id)
    {
        $pdo = $this->connection->getPDO();
        $pdo->exec('DROP TABLE messages');
        $messageModel = new PDOMessageModel($this->connection);
        $messageModel->getMessageUpvotes($id);
    }

    /**
     * @dataProvider providervalidExistingIds
     */
    public function testGetMessageDownvotes_MessageExist($id)
    {
        $messageModel = new PDOMessageModel($this->connection);
        $actualDownvotes = $messageModel->getMessageDownvotes($id);
        $this->assertTrue($actualDownvotes != null);
    }

    /**
     * @dataProvider providervalidUnexistingIds
     */
    public function testGetMessageDownvotes_MessageNotInDatabase_Null($id)
    {
        $messageModel = new PDOMessageModel($this->connection);
        $actualMessage = $messageModel->getMessageDownvotes($id);
        $this->assertNull($actualMessage);
    }

    /**
     * @expectedException \PDOException
     * @dataProvider providervalidExistingIds
     */
    public function testGetMessageDownvotes_noTableMessages_PDOException($id)
    {
        $pdo = $this->connection->getPDO();
        $pdo->exec('DROP TABLE messages');
        $messageModel = new PDOMessageModel($this->connection);
        $messageModel->getMessageDownvotes($id);
    }

    /**
     * @expectedException \PDOException
     * @dataProvider providervalidExistingIds
     */
    public function testUpdateMessageDownvotes_noTableMessages_PDOException($id)
    {
        $pdo = $this->connection->getPDO();
        $pdo->exec('DROP TABLE messages');
        $messageModel = new PDOMessageModel($this->connection);
        $messageModel->updateMessageDownvotes($id, "testtest");
    }
}
