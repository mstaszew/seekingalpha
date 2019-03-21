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
        if (!isset($_COOKIE['user_id'])) {
            return $this->render('denied.html.twig', []);
        }
        $utils = new Utils($this->getDoctrine());
        return $utils;
    }
    public function index()
    {
        $utils = $this->init();
        $users=$utils->getAllUsers();
        return $this->render('index.html.twig', ['user_name'=>$utils->getUserNameById($_COOKIE['user_id']),
            'users'=>$users]);
    }
    public function login()
    {
        $response = new RedirectResponse($_ENV['INDEX_PAGE']);
        $response->headers->setCookie(new Cookie('user_id', rand(1,10),time() + 3600, '/', null, false, false));
        return $response;
    }
    public function getfollowing() {
        $utils = $this->init();
        $response = new JsonResponse($utils->getfollowing($_COOKIE['user_id']));
        return $response;
    }
    public function follow() {
        $utils = $this->init();
        $response = new JsonResponse($utils->follow($_REQUEST['author_id']));
        return $response;
    }
}