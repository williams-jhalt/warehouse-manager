<?php

namespace AppBundle\Controller;

use AppBundle\Entity\DocumentLog;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/document-tracker")
 */
class DocumentTrackerController extends Controller {

    /**
     * @Route("/", name="document_tracker_index")
     */
    public function indexAction(Request $request) {       
        return $this->render('document-tracker/index.html.twig');
    }
    
    
    /**
     * @Route("/scan", name="document_tracker_scan")
     */
    public function scanAction(Request $request) {

        $orderNumber = $request->get('orderNumber');
        $user = $request->get('user');
        $documentAction = $request->get('documentAction');
        
        $scan = new DocumentLog();
        $scan->setOrderNumber($orderNumber);
        $scan->setUser($user);
        $scan->setDocumentAction($documentAction);
        $this->getDoctrine()->getManager()->persist($scan);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute("document_tracker_index");
    }
    
    /**
     * @Route("/list", name="document_tracker_list")
     */
    public function listAction(Request $request) {
        
        $scans = $this->getDoctrine()->getRepository('AppBundle:DocumentLog')->findBy(array(), array('timestamp' => 'desc'), 50);
        
        return $this->render('document-tracker/list.html.twig', ['scans' => $scans]);
        
    }
    
    /**
     * @Route("/search", name="document_tracker_search")
     */
    public function searchAction(Request $request) {
        
        $searchTerms = $request->get('searchTerms');
        
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
                'SELECT o '
                . 'FROM AppBundle:DocumentLog o '
                . 'WHERE o.orderNumber LIKE :search '
                . 'OR o.user = :user')
                ->setParameter('search', $searchTerms . "%")
                ->setParameter('user', $searchTerms);
        
        $scans = $query->getResult();
        
        return $this->render('document-tracker/search.html.twig', ['scans' => $scans]);
        
    }


}
