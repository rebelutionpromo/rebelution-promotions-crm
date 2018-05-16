<?php

namespace FGC\MenuBundle\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * Class Menu
 * @package AppBundle\Annotation
 *
 * @Annotation
 * @Annotation\Target("METHOD")
 */
class Menu
{
    /**
     * @var string
     *
     * @Annotation\Required()
     */
    private $name;

    /**
     * @var string
     *
     * @Annotation\Required()
     */
    private $route;

    /**
     * @var array
     */
    private $routeOptions;

    /**
     * @var string
     */
    private $icon;

    /**
     * @var integer
     */
    private $order;

    /**
     * @var string
     */
    private $group;

    /**
     * @var string
     */
    private $role;

    /**
     * @var string
     */
    private $children;

    /**
     * Menu constructor.
     * @param $options
     */
    public function __construct($options = null)
    {
    	if ($options) {
		    if (isset($options['value'])) {
			    $options['name'] = $options['value'];
			    unset($options['value']);
		    }

		    foreach ($options as $key => $value) {
			    if (!property_exists($this, $key)) {
				    throw new \InvalidArgumentException(sprintf('Property "%s" does not exist', $key));
			    }

			    $this->$key = $value;
		    }
	    }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @return array
     */
    public function getRouteOptions()
    {
        return $this->routeOptions ? $this->routeOptions : array();
    }

    /**
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @return integer
     */
    public function getOrder()
    {
        return $this->order ? $this->order : 1000;
    }

    /**
     * @return string
     */
    public function getGroup()
    {
        return $this->group ? $this->group : 'default';
    }

    /**
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @return string
     */
    public function getChildren()
    {
        return $this->children;
    }

	/**
	 * @param string $name
	 */
	public function setName( $name ) {
		$this->name = $name;
		return $this;
	}

	/**
	 * @param string $route
	 */
	public function setRoute( $route ) {
		$this->route = $route;
		return $this;
	}

	/**
	 * @param array $routeOptions
	 */
	public function setRouteOptions( $routeOptions ) {
		$this->routeOptions = $routeOptions;
		return $this;
	}

	/**
	 * @param string $icon
	 */
	public function setIcon( $icon ) {
		$this->icon = $icon;
		return $this;
	}

	/**
	 * @param int $order
	 */
	public function setOrder( $order ) {
		$this->order = $order;
		return $this;
	}

	/**
	 * @param string $group
	 */
	public function setGroup( $group ) {
		$this->group = $group;
		return $this;
	}

	/**
	 * @param string $role
	 */
	public function setRole( $role ) {
		$this->role = $role;
		return $this;
	}

	/**
	 * @param string $children
	 */
	public function setChildren( $children ) {
		$this->children = $children;
		return $this;
	}
}