<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DashboardController extends Controller
{
    /**
     * @Route("/admin-area", name="dashboard")
     * @Method({"GET"})
     */
    public function indexAction()
    {
        return $this->render('dashboard.html.twig');
    }
}
