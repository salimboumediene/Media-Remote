<?php

namespace MediaRemoteBundle\Controller\Admin;


use MediaRemoteBundle\Entity\MediaRemoteFile;
use MediaRemoteBundle\Entity\Media;
use MediaRemoteBundle\Entity\Remote;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use MediaRemoteBundle\Entity\MediaRemote;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use MediaRemoteBundle\Form\RemoteType;
use Throwable;
use Symfony\Component\Form\FormError;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;

class RemoteMediaFileController extends Controller
{
   
    
    /**
     * @Route("/remotefile",
     * name="remote_name")
     * 
     */
    public function indexAction()
    {
        return $this->render('@MediaRemote/Remote/remoteFile/remoteFile.html.twig');
    }
    
    
}
