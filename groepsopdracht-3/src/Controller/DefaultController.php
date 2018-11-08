<?php

namespace App\Controller;

use App\Entity\Message;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class DefaultController extends Controller
{
    /**
     * @Route("/", name="homeroute")
     */
    public function index(Request $request)
    {
        $response = new Response('', 200);

        return $this->render(
            'default/index.html.twig',
            ['messages' => $this->getAllMessages()],
            $response
        );
    }

    /**
     * @Route("/adminpage", name="adminroute")
     */
    public function admin(Request $request)
    {
        return new Response("adminpage");
    }


    /**
     * @Route("/userpage", name="userroute")
     */
    public function user(Request $request)
    {
        return new Response("userpage<br/>");
    }

    /**
     * @Route("/quit", name="quitroute")
     */
    public function quit(Request $request)
    {
        header("HTTP/1.1 401 Access Denied");
        header("WWW-Authenticate: " .
            "Basic realm=\"localhost:8000/\"");
        header("Content-Length: 0");
        return new Response(null, 401);
    }

    private function getAllMessages()
    {
        $em = $this->getDoctrine()->getManager();
        return $em->getRepository(Message::class)->findAll();
    }
}
