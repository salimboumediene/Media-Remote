<?php

namespace MediaRemoteBundle\Controller\Admin;


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

class MediaRemoteController extends Controller
{
    
    
    /**
     * @Route(
     * "/remote/{remote_name}/{media_name}/switch",
     *  name="switch_media_action_get",
     *  requirements={
     *      "remote_name"="^[A-Z]{1}[a-z]{2,15}$",
     *      "media_name"="^[A-Z]{1}[A-Za-z]{2,15}$"
     *  },
     *  methods="GET"
     *  )
     *  @Security("has_role('ROLE_ADMIN')")
     */
    public function getSwitchMediaAction(Request $request)
    {
        $this->get("session")->getFlashBag()->add("switch", true);
        $this->get("cache.app")
        ->deleteItem(md5($request->get("remote_name")));
        $mediaRemote =  $this
                        ->getDoctrine()
                        ->getManager()
                        ->getRepository(MediaRemote::class)
                        ->findByRemoteNameAndMediaName(
                            $request ->get("remote_name"),
                            $request ->get("media_name")
                         );
                   
        $mediaRemote->setMediaRemoteActive(
            !$mediaRemote->getMediaRemoteActive()
        );
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute("remote_get", [
            "remote_name" => $mediaRemote
                             ->getRemote()
                             ->getRemoteName()
        ]);
          
    }
    
}
