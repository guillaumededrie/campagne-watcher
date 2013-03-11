<?php

namespace Watcher\WatchBundle\Controller;

use Watcher\WatchBundle\Entity\Vote;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;




class DefaultController extends Controller
{

  protected $listeArray = array(
		    "1" => Vote::WALL_LISTE,
		    "2" => Vote::GUILIGUILIST
		    );

  public function checkUser($trigramme, $mdp) {
    return True;
  }  

  public function indexAction(Request $request)
  {
    $em = $this->getDoctrine()->getEntityManager();
    $repository = $em->getRepository('WatcherWatchBundle:Vote');

    $query = "SELECT COUNT(p.ip) as c, p.datevote as datev FROM WatcherWatchBundle:Vote p WHERE p.liste = :listename GROUP BY p.datevote";

    $query_wall_liste = $em->createQuery($query)
      ->setParameter('listename', Vote::WALL_LISTE);

    $query_guiliguilist = $em->createQuery($query)
      ->setParameter('listename', Vote::GUILIGUILIST);


    return $this->render('WatcherWatchBundle:Default:main.html.twig', array(
									    'vote_wall_liste' => $query_wall_liste->getResult(),
									    'vote_guiliguilist' => $query_guiliguilist->getResult(),
									    ));
  }


  public function voteAction($id)
  {

    $form = $this->createFormBuilder()
      ->add('id','hidden', array('data' => $id))
      ->add('trigramme', 'text', array('label' => 'Identifiant Ensimag'))
      ->add('mdp', 'password', array('label' => 'Mot de passe Ensimag'))
      ->add('captcha','captcha')
      ->getForm();

    if ($this->getRequest()->isMethod('POST')) {
      $form->bind($this->getRequest());

      if ($form->isValid()) {
	$data = $form->getData();

	if (!$this->checkUser($data['trigramme'], $data['mdp'])) {
	  $this->get('session')->getFlashBag()->add('notice','Mauvais mot de passe ou identifiant !');
	} else {

	  // TO FIX : use listeArray
	  if (! in_array($data['id'], array(1,2))) {
	    $this->get('session')->getFlashBag()->add('notice','Cette liste n\'existe pas ! Retente ta chance ! ');
	  } else {

	        $em = $this->getDoctrine()->getEntityManager();


		$vote = $em->createQueryBuilder()
		  ->select('b')
		  ->from('WatcherWatchBundle:Vote', 'b')
		  ->where('b.trigramme = :trigramme AND b.datevote = :datevote')
		  ->setParameter('datevote', date('Y-m-d H:00:00', time()))
		  ->setParameter('trigramme', $data['trigramme'])
		  ->getQuery()
		  ->getResult();

	    if ($vote) {
	      $this->get('session')->getFlashBag()->add('notice', 'Votre compte a déjà voté ! Revenez dans 1 heure !');
	    } else {
	      $vote = new Vote();
	      $vote->setDateVote(new \DateTime(date('Y-m-d H:00:00')));
	      $vote->setIp($this->getRequest()->server->get("REMOTE_ADDR"));
	      $vote->setTrigramme($data['trigramme']);
	      $vote->setListe($this->listeArray[$data['id']]);

	      $em = $this->getDoctrine()->getEntityManager();
	      $em->persist($vote);
	      $em->flush();

	      $this->get('session')->getFlashBag()->add('notice','Votre vote a bien été pris en compte !');
	    }


	  }

	}

      } else {
	$this->get('session')->getFlashBag()->add('notice','Mauvais Captcha !');
      }

      return $this->redirect($this->generateUrl('watcher_watch_homepage'));
    }

    return $this->render('WatcherWatchBundle:Default:vote.html.twig', array('form' => $form->createView(), 'id' => $id));
  }


}
