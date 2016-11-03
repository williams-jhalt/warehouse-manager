<?php 

namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class Builder implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
    	$menu->setChildrenAttribute('class', 'nav navbar-nav');

        $menu->addChild('Home', array('route' => 'homepage'));

        return $menu;
    }
    
    public function sidebarMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
    	$menu->setChildrenAttribute('class', 'nav nav-pills nav-stacked');
        
        $menu->addChild('Document Tracker', array('route' => 'document_tracker_index'));
        $menu->addChild('Picker Log', array('route' => 'picker_log_index'));
        $menu->addChild('Product Lookup', array('route' => 'product_lookup_index'));
        $menu->addChild('Order Lookup', array('route' => 'sales_orders_index'));
        
        return $menu;
    }

    public function documentLogMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
    	$menu->setChildrenAttribute('class', 'nav nav-tabs');

        $menu->addChild('Scan', array('route' => 'document_tracker_index'));
        $menu->addChild('Search', array('route' => 'document_tracker_search'));

        return $menu;
    }

    public function pickerLogMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
    	$menu->setChildrenAttribute('class', 'nav nav-tabs');

        $menu->addChild('Scan', array('route' => 'picker_log_index'));
        $menu->addChild('Search', array('route' => 'picker_log_search'));

        return $menu;
    }

    public function productMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
    	$menu->setChildrenAttribute('class', 'nav nav-tabs');

        $menu->addChild('Lookup', array('route' => 'product_lookup_index'));
        $menu->addChild('Committed', array('route' => 'product_lookup_committed'));

        return $menu;
    }
    
}