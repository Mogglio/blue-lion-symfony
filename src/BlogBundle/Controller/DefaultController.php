<?php

namespace BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class DefaultController extends Controller
{

    public function indexAction()
    {
        return $this->render('@Blog/index/index.html.twig');
    }
}
