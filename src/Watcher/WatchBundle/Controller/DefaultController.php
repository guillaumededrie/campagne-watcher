<?php

namespace Watcher\WatchBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('WatcherWatchBundle:Default:main.html.twig');
    }


    public function voteAction($id)
    {
        return $this->render('WatcherWatchBundle:Default:vote.html.twig', array('id' => $id));
    }


}
