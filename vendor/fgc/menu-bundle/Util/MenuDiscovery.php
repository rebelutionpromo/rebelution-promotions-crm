<?php

namespace FGC\MenuBundle\Util;

use FGC\MenuBundle\Annotation\Menu;
use Doctrine\Common\Annotations\Reader;
use FGC\MenuBundle\Event\DiscoverMenuEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class MenuDiscovery
{
    private $namespace;

    private $directory;

    private $annotationReader;

    private $rootDir;

    private $menus;

    private $fetched;

    private $dispatcher;

    /**
     * MenuDiscovery constructor.
     * @param $rootDir
     * @param Reader $annotationReader
     * @param array $options
     */
    public function __construct($rootDir, Reader $annotationReader, $options, EventDispatcherInterface $dispatcher)
    {
        $this->fetched = false;
        $this->annotationReader = $annotationReader;
        $this->rootDir = $rootDir;
        $this->dispatcher = $dispatcher;
        $this->directory = $options['directory'];
        $this->namespace = $options['namespace'];

        foreach ($options['menus'] as $group => $items) {
            foreach ($items as $name => $values) {
                $values['name']  = $name;
                $values['group'] = $group;
                $item = new Menu($values);
                if (isset($this->menus[$group])) {
                    $this->menus[$group][] = $item;
                } else {
                    $this->menus[$group] = array($item);
                }
            }
        }
    }

    /**
     * Returns all menus
     *
     * @return array
     */
    public function getMenus()
    {
        if (!$this->directory || !$this->namespace) {
            throw new \InvalidArgumentException('Directory/Namespace not set correctly.');
        }

        if (!$this->fetched) {
            $this->discoverMenus();
        }

        return $this->menus;
    }

	/**
	 * Gathers Annotation information for menus

	 * @throws \ReflectionException
	 */
    private function discoverMenus()
    {
        $path = $this->rootDir . '/../src/' . $this->directory;
        $finder = new Finder();
        $finder->files()->in($path);

        /** @var SplFileInfo $file */
        foreach ($finder as $file) {
            $class = $this->namespace . ($file->getRelativePath() ? '\\'.$file->getRelativePath().'\\' : '\\') . $file->getBasename('.php');
            foreach (get_class_methods($class) as $method) {
                $annotation = $this->annotationReader->getMethodAnnotations(new \ReflectionMethod("$class::$method"));
                /** @var Menu $ann */
                foreach ($annotation as $ann) {
                    if ($ann instanceof Menu) {
                        if (isset($this->menus[$ann->getGroup()])) {
                            $this->menus[$ann->getGroup()][] = $ann;
                        } else {
                            $this->menus[$ann->getGroup()] = array($ann);
                        }
                    }
                }
            }
        }

        // Event Dispatch (Add dynamic Menu Items)
	    $event = new DiscoverMenuEvent();
        $new_items = $this->dispatcher->dispatch(DiscoverMenuEvent::NAME, $event)->getItems();
        if ($new_items) {
        	foreach($new_items as $item) {
        		if ($item instanceof Menu) {
			        if (isset($this->menus[$item->getGroup()])) {
				        $this->menus[$item->getGroup()][] = $item;
			        } else {
				        $this->menus[$item->getGroup()] = array($item);
			        }
		        }
	        }
        }

        foreach ($this->menus as &$group) {
        	/** $a, $b Menu  */
            usort($group, function ($a, $b) {return $a->getOrder()<=$b->getOrder()?-1:1;});
        }

        $this->fetched = true;
    }
}