<?php

namespace AppBundle\Controller;

use AppBundle\Entity\DocumentLog;
use AppBundle\Entity\PickerLog;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/picker-log")
 */
class PickerLogController extends Controller {

    /**
     * @Route("/", name="picker_log_index")
     */
    public function indexAction(Request $request) {
        return $this->render('picker-log/index.html.twig');
    }

    /**
     * @Route("/scan", name="picker_log_scan")
     */
    public function scanAction(Request $request) {

        $orderNumber = $request->get('orderNumber');
        $user = $request->get('user');

        $messages = array();

        if (!preg_match("/\d+-\d+/", $orderNumber)) {
            $messages[] = "Not a valid order number";
        }

        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
                        'SELECT o '
                        . 'FROM AppBundle:PickerLog o '
                        . 'WHERE o.orderNumber = :orderNumber '
                        . 'ORDER BY o.timestamp DESC')
                ->setParameter('orderNumber', $orderNumber)
                ->setMaxResults(1);
                
        $test = $query->getOneOrNullResult();

        if (sizeof($messages) == 0) {
            $scan = new PickerLog();
            $scan->setOrderNumber($orderNumber);
            $scan->setUser($user);
            $this->getDoctrine()->getManager()->persist($scan);
            $this->getDoctrine()->getManager()->flush();
        }

        return new Response(json_encode(['messages' => $messages]));
    }

    /**
     * @Route("/list", name="picker_log_list")
     */
    public function listAction(Request $request) {

        $scans = $this->getDoctrine()->getRepository('AppBundle:PickerLog')->findBy(array(), array('timestamp' => 'desc'), 50);

        return $this->render('picker-log/list.html.twig', ['scans' => $scans]);
    }

    /**
     * @Route("/search", name="picker_log_search")
     */
    public function searchAction(Request $request) {

        $searchTerms = $request->get('searchTerms');

        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
                        'SELECT o '
                        . 'FROM AppBundle:PickerLog o '
                        . 'WHERE o.orderNumber LIKE :search '
                        . 'OR o.user = :user')
                ->setParameter('search', $searchTerms . "%")
                ->setParameter('user', $searchTerms)
                ->setMaxResults(50);

        $scans = $query->getResult();

        return $this->render('picker-log/search.html.twig', ['scans' => $scans]);
    }

}