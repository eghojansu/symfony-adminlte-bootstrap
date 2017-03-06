<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class AssetController extends Controller
{
    /**
     * @Route("/avatar/{file}", name="avatar")
     * @Method({"GET"})
     */
    public function avatarAction($file)
    {
        $path = $this->get('kernel')->getUploadDir().'/'.$file;
        if (!file_exists($path)) {
            $this->createNotFoundException('The image was not found.');
        }

        return $this->file($path, null, ResponseHeaderBag::DISPOSITION_INLINE);
    }
}
