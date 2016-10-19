<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/product-lookup")
 */
class ProductLookupController extends Controller {

    /**
     * @Route("/", name="product_lookup_index")
     */
    public function indexAction(Request $request) {
        return $this->render('product-lookup/index.html.twig');
    }

    /**
     * @Route("/search", name="product_lookup_search")
     */
    public function searchAction(Request $request) {
        
        $service = $this->get('app.product_service');
        $items = $service->findBySearchTerms($request->get('searchTerms'));
        
        return $this->render('product-lookup/search.html.twig', ['items' => $items]);
    }
    
    /**
     * @Route("/committed", name="product_lookup_committed")
     */
    public function committedAction(Request $request) {
        
        $searchTerms = $request->get('searchTerms');
        $page = $request->get('page', 1);
        
        $service = $this->get('app.product_service');
        
        $limit = 50;
        $offset = (($page - 1) * $limit);
        
        $items = $service->committedProducts($searchTerms, $offset, $limit);
        
        return $this->render('product-lookup/committed.html.twig', ['items' => $items, 'page' => $page]);
        
    }

}
