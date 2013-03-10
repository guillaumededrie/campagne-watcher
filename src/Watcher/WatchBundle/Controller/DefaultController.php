<?php

namespace Watcher\WatchBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class DefaultController extends Controller
{
  public function indexAction(Request $request)
  {

    return $this->render('WatcherWatchBundle:Default:main.html.twig');
  }


  public function voteAction($id)
  {
    $form = $this->createFormBuilder()
      ->add('id','hidden', array('data' => $id))
      ->add('identifiant', 'text', array('label' => 'Identifiant Ensimag'))
      ->add('mdp', 'password', array('label' => 'Mot de passe Ensimag'))
      ->add('captcha','captcha')
      ->getForm();

    if ($this->getRequest()->isMethod('POST')) {
      $form->bind($this->getRequest());
      if ($form->isValid()) {
            // effectuez quelques actions, comme sauvegarder la tâche dans
            // la base de données
	  $this->get('session')->getFlashBag()->add('notice','Your changes were saved!');
      }
      return $this->redirect($this->generateUrl('watcher_watch_homepage'));
    }

    return $this->render('WatcherWatchBundle:Default:vote.html.twig', array('form' => $form->createView(), 'id' => $id));
  }


}
