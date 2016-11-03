<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/orders")
 */
class SalesOrdersController extends Controller {

    /**
     * @Route("/", name="sales_orders_index")
     */
    public function indexAction(Request $request) {
        return $this->render('sales-orders/index.html.twig');
    }

    /**
     * @Route("/list", name="sales_orders_list")
     */
    public function listAction(Request $request) {
        $manifestId = $request->get('manifestId');
        
        $service = $this->get('app.order_service');
        
        $salesOrder = $service->getOrder($manifestId);
        
        return $this->render('sales-orders/list.html.twig', [
            'order' => $salesOrder
        ]);
        
    }
    
    /**
     * @Route("/view", name="sales_orders_view")
     */
    public function viewAction(Request $request) {
        
        $manifestId = $request->get('manifestId');
        $orderNumber = $request->get('orderNumber');
        $recordSequence = $request->get('recordSequence');
        
        $service = $this->get('app.order_service');
        
        $salesOrder = $service->getOrder($manifestId);        
        $cartons = $service->getCartons($orderNumber, $recordSequence);
        
        return $this->render('sales-orders/view.html.twig', [
            'order' => $salesOrder,
            'cartons' => $cartons
        ]);
        
    }
    
    /**
     * @Route("/carton", name="sales_orders_carton")
     */
    public function cartonAction(Request $request) {
        
        $ucc = $request->get('ucc');
        
        $service = $this->get('app.order_service');
        
        $items = $service->getItems($ucc);
        
        return $this->render('sales-orders/carton.html.twig', [
            'items' => $items
        ]);
        
    }

}
