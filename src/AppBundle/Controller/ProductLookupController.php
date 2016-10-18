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

}
