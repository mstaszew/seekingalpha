<?php
namespace App\Controller;

use App\Utils\Utils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;

class DefaultController extends AbstractController
{
    private function init() {
        $utils = new Utils($this->getDoctrine());
        return $utils;
    }

    public function index()
    {
        if (!isset($_COOKIE['user_id'])) return $this->render('denied.html.twig', []);
        $utils = $this->init();
        $users=$utils->getAllUsers();
        return $this->render('index.html.twig', ['user_name'=>$utils->getUserNameById(intval($_COOKIE['user_id'])),
            'users'=>$users]);
    }
    public function login()
    {
        $response = new RedirectResponse($_ENV['INDEX_PAGE']);
        $response->headers->setCookie(new Cookie('user_id', rand(1,10),time() + 3600, '/', null, false, false));
        return $response;
    }
    public function getfollowing() {
        if (!isset($_COOKIE['user_id'])) return $this->render('denied.html.twig', []);
        $utils = $this->init();
        $response = new JsonResponse($utils->getfollowing(intval($_COOKIE['user_id'])));
        return $response;
    }
    public function follow() {
        if (!isset($_COOKIE['user_id'])) return $this->render('denied.html.twig', []);        
        $utils = $this->init();
        $response = new JsonResponse($utils->follow(intval($_COOKIE['user_id']),intval($_REQUEST['author_id'])));
        return $response;
    }
    public function unfollow() {
        if (!isset($_COOKIE['user_id'])) return $this->render('denied.html.twig', []);
        $utils = $this->init();
        $response = new JsonResponse($utils->unfollow(intval($_COOKIE['user_id']),intval($_REQUEST['author_id'])));
        return $response;
    }
}
