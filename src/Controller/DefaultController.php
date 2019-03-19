<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController
{
    public function index()
    {
        $number = random_int(0, 100);

        return new JsonResponse(
            array('lucky_number'=>$number)
        );
    }
}