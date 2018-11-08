<?php

namespace App\Model;

use App\Utils\Votes;

class PDOMessageModel implements MessageModel
{
    private $connection = null;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getMessages()
    {
        $pdo = $this->connection->getPDO();
        $statement = $pdo->prepare('SELECT * FROM Message');

        $statement->execute();
        $statement->bindColumn(1, $id, \PDO::PARAM_INT);
        $statement->bindColumn(2, $contents, \PDO::PARAM_STR);
        $statement->bindColumn(3, $category, \PDO::PARAM_STR);
        $statement->bindColumn(4, $upvotes, \PDO::PARAM_INT);
        $statement->bindColumn(5, $downvotes, \PDO::PARAM_INT);

        $messages = [];
        while ($statement->fetch(\PDO::FETCH_BOUND)) {
            $messages[] = ['id' => $id,
                'contents' => $contents,
                'category' => $category,
                'upvotes' => $upvotes,
                'downvotes' => $downvotes];
        }
        return $messages;
    }

    public function getMessageById($id)
    {
        $this->validateId($id);
        $pdo = $this->connection->getPDO();

        $statement = $pdo->prepare('SELECT * from Message WHERE id=:id');
        $statement->bindParam(':id', $id, \PDO::PARAM_INT);
        $statement->execute();
        $statement->bindColumn(1, $id, \PDO::PARAM_INT);
        $statement->bindColumn(2, $contents, \PDO::PARAM_STR);
        $statement->bindColumn(3, $category, \PDO::PARAM_STR);
        $statement->bindColumn(4, $upvotes, \PDO::PARAM_INT);
        $statement->bindColumn(5, $downvotes, \PDO::PARAM_INT);
        $message = null;
        if ($statement->fetch(\PDO::FETCH_BOUND)) {
            $message = ['id' => $id,
                'contents' => $contents,
                'category' => $category,
                'upvotes' => $upvotes,
                'downvotes' => $downvotes];
        }
        return $message;
    }

    public function getMessagesBySearchInMessage($searchWords)
    {
        $pdo = $this->connection->getPDO();
        $likeVar = "%" . $searchWords . "%";

        $statement = $pdo->prepare("SELECT * from Message WHERE Contents LIKE :searchWords");
        $statement->bindParam(':searchWords', $likeVar, \PDO::PARAM_STR);
        $statement->execute();
        $statement->bindColumn(1, $id, \PDO::PARAM_INT);
        $statement->bindColumn(2, $contents, \PDO::PARAM_STR);
        $statement->bindColumn(3, $category, \PDO::PARAM_STR);
        $statement->bindColumn(4, $upvotes, \PDO::PARAM_INT);
        $statement->bindColumn(5, $downvotes, \PDO::PARAM_INT);
        $message = null;

        $foundMessages = [];

        while ($statement->fetch(\PDO::FETCH_BOUND)) {
            $message = ['id' => $id,
                'contents' => $contents,
                'category' => $category,
                'upvotes' => $upvotes,
                'downvotes' => $downvotes];
            array_push($foundMessages, $message);
        }
        return $foundMessages;
    }

    public function getMessagesBySearchInMessageAndCategory($searchWords, $category)
    {
        $pdo = $this->connection->getPDO();
        $likeVar = "%" . $searchWords . "%";

        $statement = $pdo->prepare("SELECT * 
                              from Message
                              WHERE Contents 
                              LIKE  :searchWords 
                              AND category=:category");
        $statement->bindParam(':searchWords', $likeVar, \PDO::PARAM_STR);
        $statement->bindParam(':category', $category, \PDO::PARAM_STR);

        $statement->execute();
        $statement->bindColumn(1, $id, \PDO::PARAM_INT);
        $statement->bindColumn(2, $contents, \PDO::PARAM_STR);
        $statement->bindColumn(3, $category, \PDO::PARAM_STR);
        $statement->bindColumn(4, $upvotes, \PDO::PARAM_INT);
        $statement->bindColumn(5, $downvotes, \PDO::PARAM_INT);
        $message = null;

        $foundMessages = [];

        while ($statement->fetch(\PDO::FETCH_BOUND)) {
            $message = ['id' => $id,
                'contents' => $contents,
                'category' => $category,
                'upvotes' => $upvotes,
                'downvotes' => $downvotes];
            array_push($foundMessages, $message);
        }
        return $foundMessages;
    }

    public function getMessageUpvotes($id)
    {
        $pdo = $this->connection->getPDO();

        $statement = $pdo->prepare('SELECT Upvotes FROM Message WHERE id=:id');
        $statement->bindParam(':id', $id, \PDO::PARAM_INT);
        $statement->execute();
        $statement->bindColumn(1, $upvotes, \PDO::PARAM_INT);
        $returnVotes = null;

        if ($statement->fetch(\PDO::FETCH_BOUND)) {
            $returnVotes = $upvotes;
        }
        return $returnVotes;
    }

    public function getMessageDownvotes($id)
    {
        $pdo = $this->connection->getPDO();

        $statement = $pdo->prepare('SELECT Downvotes FROM Message WHERE id=:id');
        $statement->bindParam(':id', $id, \PDO::PARAM_INT);
        $statement->execute();
        $statement->bindColumn(1, $downvotes, \PDO::PARAM_INT);
        $returnVotes = null;

        if ($statement->fetch(\PDO::FETCH_BOUND)) {
            $returnVotes = $downvotes;
        }
        return $returnVotes;
    }

    public function upvoteMessage($id)
    {
        $pdo = $this->connection->getPDO();

        $upvotes = intval($this->getMessageUpvotes($id)) + 1;

        $statement = $pdo->prepare('UPDATE Message SET upvotes=:upvotes WHERE id=:id');
        $statement->bindParam(':id', $id, \PDO::PARAM_INT);
        $statement->bindParam(':upvotes', $upvotes, \PDO::PARAM_INT);
        $statement->execute();
    }

    public function downvoteMessage($id)
    {
        $pdo = $this->connection->getPDO();

        $upvotes = intval($this->getMessageDownvotes($id)) + 1;

        $statement = $pdo->prepare('UPDATE Message SET downvotes=:downvotes WHERE id=:id');
        $statement->bindParam(':id', $id, \PDO::PARAM_INT);
        $statement->bindParam(':downvotes', $upvotes, \PDO::PARAM_INT);
        $statement->execute();
    }


    public function idExists($id)
    {
        $this->validateId($id);
        $pdo = $this->connection->getPDO();

        $statement = $pdo->prepare('SELECT id from Message WHERE Id=:id');
        $statement->bindParam(':id', $id, \PDO::PARAM_INT);
        $statement->execute();
        if ($statement->fetch() === false) {
            return false;
        }
        return true;
    }

    private function validateId($id)
    {
        if (!(is_string($id) && preg_match("/^[0-9]+$/", $id) && (int)$id > 0)) {
            throw new \InvalidArgumentException("id needs to have an integer above 0");
        }
    }

    public function getComments($messageId)
    {
        $pdo = $this->connection->getPDO();
        $statement = $pdo->prepare('SELECT * FROM Comment WHERE Message_Id=:id');
        $statement->bindParam(':id', $messageId, \PDO::PARAM_INT);
        $statement->execute();
        $statement->bindColumn(1, $id, \PDO::PARAM_INT);
        $statement->bindColumn(2, $contents, \PDO::PARAM_STR);
        $statement->bindColumn(3, $token, \PDO::PARAM_STR);
        $statement->bindColumn(4, $messageId, \PDO::PARAM_INT);

        $comments = [];
        while ($statement->fetch(\PDO::FETCH_BOUND)) {
            $comments[] = ['id' => $id,
                'contents' => $contents,
                'token' => $token,
                'messageId' => $messageId];
        }
        return $comments;
    }

    public function addCommentOnMessageById($id, $content)
    {
        $pdo = $this->connection->getPDO();

        $token = hash("adler32", $id . $content);

        $statement = $pdo->prepare('INSERT INTO Comment (Contents,Token,Message_Id) 
                                  values (:content,:token,:message_id)');
        $statement->bindParam(':content', $content, \PDO::PARAM_STR);
        $statement->bindParam(':token', $token, \PDO::PARAM_STR);
        $statement->bindParam(':message_id', $id, \PDO::PARAM_STR);
        $statement->execute();

        return $token;
    }
}
