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

class RemoteController extends Controller
{
    /**
     * 
     * @param string $name
     * @throws NotFoundHttpException
     * @return array
     * 
     */
    private function getMediaRemote($name):array
    {
     if (!($mediaRemote =  $this
                            ->getDoctrine()
                            ->getManager()
                            ->getRepository(MediaRemote::class)
                            ->findByRemoteName($name))){
         throw new NotFoundHttpException("Remote not found");
     }
     return $mediaRemote;
    }
    
    
    /**
     * @Route("/remote", name="remote")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function indexAction(Request $request)
    {
       return $this->redirectToRoute("remote_get", [
              "remote_name" => $this->getDoctrine()
                               ->getManager()
                               ->getRepository(Remote::class)
                               ->findDefaultRemoteName()
       ]);
    }
    
    /**
     * @Route(
     * "/remote/{remote_name}",
     *  name="remote_get",
     *  requirements={"remote_name"="[A-Za-z]{2,32}"},
     *  methods="GET"
     *  )
     *  @Security("has_role('ROLE_ADMIN')")
     */
    public function getAction(Request $request)
    {
        $etag = md5($request->getUri());
        if(!$this->get("session")->getFlashBag()->get("switch")
            && '"' . $etag . '"' === current($request->getETags())){
            $response = new Response();
            $response -> setNotModified();
            return $response;
        }

        $key = md5($request->get("remote_name"));
//      Le cache s'est le dossier pool
        $cache = $this->get("cache.app");
//      l'item s'est le fichier dans pool
        $item = $cache -> getItem($key);
//      on receherche que la donnée dans la sgdbr
        if(!$item->isHit()){
//       on verifie la donnée est accessible
            $mediaRemote = $this ->getMediaRemote($request->get("remote_name"));
//          on met la donnée dans le fichier
            $item->set($mediaRemote);
//          on sauvegarde
            $cache->save($item);
//          sinon on recupere la donnée depuis l'item
        }else{
            $mediaRemote = $item->get();
            foreach ($mediaRemote as $value){
                $value->setMedia(
                    $this ->getDoctrine()
                          ->getManager()
                          ->merge($value->getMedia())
                    );
            }
        }
        $response = $this->render('@MediaRemote/Remote/index.html.twig', array(
                "mediaRemote" => $mediaRemote,
                "form"        => $this->get("form.factory")
                ->create(RemoteType::class,$mediaRemote[0]->getRemote())
                ->createView()
        ));
        $response->setEtag($etag);
        
        return $response;
    }

    /**
     * @Route(
     * "/remote/{remote_name}",
     *  name="remote_post",
     *  requirements={"remote_name"="^[A-Z]{1}[a-z]{2,15}$"},
     *  methods="POST"
     *  )
     *  @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function postAction(Request $request)
    { 
        $mediaRemote =  $this ->getMediaRemote($request -> get("remote_name"));
        $form = $this->get("form.factory")
        ->create(RemoteType::class,$mediaRemote[0]->getRemote());
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            try {
                 $this->getDoctrine()->getManager()->flush();
                 $response =  $this->redirectToRoute("remote_get", ["remote_name"
                    => $mediaRemote[0]->getRemote()->getRemoteName()]);
                $this->get("cache.app")
                ->deleteItem(md5($request->get("remote_name")));
                $response->setEtag(null);       
                return $response;
            
            } catch (\Throwable $e) {
                $form->addError(new FormError("name.exists"));
                
            }
            
        }
        return $this->render('@MediaRemote/Remote/index.html.twig', array(
            "mediaRemote" => $mediaRemote,
            "form"        => $form->createView()));
    }
    
}
