<?php

namespace App\Http\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function index()
    {
        if(empty($this->getUser())) {
            return $this->redirectToRoute('user_login');
        }
        return $this->render('Page/Index/index.twig', [
            'user_info' => 'IndexController',
        ]);
    }
}
