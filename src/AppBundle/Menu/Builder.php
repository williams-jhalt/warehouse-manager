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

        $menu->addChild('Home', array('route' => 'homepage'));

        return $menu;
    }
    
    public function sidebarMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
        
        $menu->addChild('Document Tracker', array('route' => 'document_tracker_index'));
        $menu->addChild('Picker Log', array('route' => 'picker_log_index'));
        $menu->addChild('Product Lookup', array('route' => 'product_lookup_index'));
        
        return $menu;
    }

    public function documentLogMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');

        $menu->addChild('Scan', array('route' => 'document_tracker_index'));
        $menu->addChild('Search', array('route' => 'document_tracker_search'));

        return $menu;
    }

    public function pickerLogMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');

        $menu->addChild('Scan', array('route' => 'picker_log_index'));
        $menu->addChild('Search', array('route' => 'picker_log_search'));

        return $menu;
    }
    
}