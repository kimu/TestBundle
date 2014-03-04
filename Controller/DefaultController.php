<?php

namespace Infinity\Bundle\TestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('InfinityTestBundle:Default:index.html.twig', array('name' => $name));
    }
}
