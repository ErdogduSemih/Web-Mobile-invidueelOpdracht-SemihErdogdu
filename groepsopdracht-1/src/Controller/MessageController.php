<?php

namespace App\Controller;
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET,POST,OPTIONS");

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

use App\Model\MessageModel;

class MessageController extends AbstractController
{
    private $messageModel;

    public function __construct(MessageModel $messageModel)
    {
        $this->messageModel = $messageModel;
    }

    /**
     * @Route("/message", methods={"GET"}, name="getMessages")
     */
    public function getMessages()
    {
        $statuscode = 200;
        $messages = [];

        try {
            $messages = $this->messageModel->getMessages();
        } catch (\PDOException $exception) {
            $statuscode = 500;
        }

        if (empty($messages)) {
            $statuscode = 400;
        }

        return new JsonResponse($messages, $statuscode);
    }

    /**
     * @Route("/message/{id}", methods={"GET"}, name="getMessageById")
     */
    public function getMessageById($id)
    {
        $statuscode = 200;
        $message = null;

        try {
            $message = $this->messageModel->getMessageById($id);

            if ($message == null) {
                $statuscode = 400;
            }
        } catch (\InvalidArgumentException $exception) {
            $statuscode = 400;
        } catch (\PDOException $exception) {
            $statuscode = 500;
        }

        return new JsonResponse($message, $statuscode);
    }

    /**
     * @Route("/message/{id}/upvote", methods={"GET"}, name="upvoteMessage")
     */
    public function upvoteMessage($id)
    {
        $statuscode = 200;
        $message = null;

        try {
            $this->messageModel->upvoteMessage($id);
            $message = $this->messageModel->getMessageById($id);

            if ($message == null) {
                $statuscode = 400;
            }
        } catch (\InvalidArgumentException $exception) {
            $statuscode = 400;
        } catch (\PDOException $exception) {
            $statuscode = 500;
        }

        return new JsonResponse($message, $statuscode);
    }

    /**
     * @Route("/message/{id}/downvote", methods={"GET"}, name="downvoteMessage")
     */
    public function downvoteMessage($id)
    {
        $statuscode = 200;
        $message = null;

        try {
            $this->messageModel->downvoteMessage($id);
            $message = $this->messageModel->getMessageById($id);

            if ($message == null) {
                $statuscode = 400;
            }
        } catch (\InvalidArgumentException $exception) {
            $statuscode = 400;
        } catch (\PDOException $exception) {
            $statuscode = 500;
        }

        return new JsonResponse($message, $statuscode);
    }

    /**
     * @Route("/message/", methods={"GET"}, name="getMessageBySearchTerm")
     * @param Request $request
     * @return JsonResponse
     */
    public function getMessageBySearchTerm(Request $request)
    {
        $searchTerm = $request->query->get("searchTerm");
        $statuscode = 200;
        $messages = [];

        try {
            $messages = $this->messageModel->getMessagesBySearchInMessage($searchTerm);

            if ($messages == null) {
                $statuscode = 400;
            }
        } catch (\InvalidArgumentException $exception) {
            $statuscode = 400;
        } catch (\PDOException $exception) {
            $statuscode = 500;
        }
        return new JsonResponse($messages, $statuscode);
    }

    /**
     * @Route("/message/{id}/comment", methods={"GET"}, name="getComments")
     * @param Request $request
     * @return JsonResponse
     */
    public function getComments($id)
    {
        $statuscode = 200;
        $comments = [];

        try {
            $comments = $this->messageModel->getComments($id);
        } catch (\PDOException $exception) {
            $statuscode = 500;
        }

        if (empty($comments)) {
            $statuscode = 400;
        }

        return new JsonResponse($comments, $statuscode);
    }

    /**
     * @Route("/message/{id}/comment/new", methods={"POST"}, name="addCommentOnMessageById")
     * @param Request $request
     * @return JsonResponse
     */
    public function addCommentOnMessageById(Request $request, $id)
    {
        $content = $request->get("content");

        $statuscode = 200;
        $token = "";

        try {
            $token = $this->messageModel->addCommentOnMessageById($id, $content);
            if ($token == null) {
                $statuscode = 400;
            }
        } catch (\InvalidArgumentException $exception) {
            $statuscode = 400;
        } catch (\PDOException $exception) {
            $statuscode = 500;
        }
        return new JsonResponse($token, $statuscode);
    }

    /**
     * @Route("/message/searchWithCategory/", methods={"GET"}, name="getMessageByKeywordAndCategory")
     * @param Request $request
     * @return JsonResponse
     */
    public function getMessageByKeywordAndCategory(Request $request)
    {
        $searchTerm = $request->query->get("searchTerm");
        $category = $request->query->get("category");

        $statuscode = 200;
        $messages = [];

        try {
            $messages = $this->messageModel->getMessagesBySearchInMessageAndCategory($searchTerm, $category);
            if ($messages == null) {
                $statuscode = 400;
            }
        } catch (\InvalidArgumentException $exception) {
            $statuscode = 400;
        } catch (\PDOException $exception) {
            $statuscode = 500;
        }
        return new JsonResponse($messages, $statuscode);
    }
}
