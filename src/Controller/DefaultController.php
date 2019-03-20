<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends AbstractController
{
    public function index()
    {

        return $this->render('base.html.twig', []);
    }
    public function indexJson()
    {
        $number = random_int(0, 100);

        return new JsonResponse(
            array('lucky_number'=>$number)
        );
    }
}