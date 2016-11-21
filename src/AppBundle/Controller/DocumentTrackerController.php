<?php

namespace AppBundle\Controller;

use AppBundle\Entity\DocumentLog;
use DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
        $documentAction = strtoupper($request->get('documentAction'));

        $messages = array();

        if (!preg_match("/\d+-\d+/", $orderNumber)) {
            $messages[] = "Not a valid order number";
        }

        if ($documentAction != 'CHECK IN' && $documentAction != 'CHECK OUT') {
            $messages[] = "Not a valid action";
        }

        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
                        'SELECT o '
                        . 'FROM AppBundle:DocumentLog o '
                        . 'WHERE o.orderNumber = :orderNumber '
                        . 'ORDER BY o.timestamp DESC')
                ->setParameter('orderNumber', $orderNumber)
                ->setMaxResults(1);
                
        $test = $query->getOneOrNullResult();
        
        if ($test && $documentAction == $test->getDocumentAction()) {
            $messages[] = "$orderNumber has already been $documentAction";
        }

        if (sizeof($messages) == 0) {
            $scan = new DocumentLog();
            $scan->setOrderNumber($orderNumber);
            $scan->setUser($user);
            $scan->setDocumentAction($documentAction);
            $this->getDoctrine()->getManager()->persist($scan);
            $this->getDoctrine()->getManager()->flush();
        }

        return new Response(json_encode(['messages' => $messages]));
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
        $startDate = $request->get('startDate');
        $endDate = $request->get('endDate');

        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder()
                ->select('o')
                ->from('AppBundle:DocumentLog', 'o');
        
        if ($searchTerms !== null) {
            $qb->andWhere('o.orderNumber LIKE :search OR o.user = :user')
                    ->setParameter('search', $searchTerms . "%")
                    ->setParameter('user', $searchTerms);            
        }
        
        if ($startDate !== null) {
            $qb->andWhere('o.timestamp >= :startDate')
                    ->setParameter('startDate', new DateTime($startDate));
        }
        
        if ($endDate !== null) {
            $qb->andWhere('o.timestamp <= :endDate')
                    ->setParameter('endDate', new DateTime($endDate));
        }
        
        $qb->orderBy('o.timestamp', 'desc')
                ->setMaxResults(50);

        $scans = $qb->getQuery()->getResult();

        return $this->render('document-tracker/search.html.twig', ['scans' => $scans]);
    }

}
