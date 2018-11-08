<?php

namespace App\Model;

interface MessageModel
{
    public function getMessages();

    public function getMessageById($id);

    public function getMessagesBySearchInMessage($searchWords);

    public function getMessagesBySearchInMessageAndCategory($searchWords, $category);

    public function upvoteMessage($id);

    public function downvoteMessage($id);

    public function idExists($id);

    public function getComments($messageId);

    public function addCommentOnMessageById($id, $content);
}
