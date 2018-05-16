<?php
namespace FGC\MenuBundle\Twig;

use FGC\MenuBundle\Util\MenuRender;

class MenuRenderExtension extends \Twig_Extension
{
    /**
     * @var MenuRender
     */
    private $menuRender;

	/**
	 * MenuRenderExtension constructor.
	 *
	 * @param MenuRender $menuRender
	 */
    public function __construct(MenuRender $menuRender)
    {
        $this->menuRender = $menuRender;
    }

	/**
	 * @return array|\Twig_Function[]
	 */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('fgc_menu', array($this,'FGCMenuRender'), array('is_safe' => array('html')))
        );
    }

	/**
	 * @param string $name
	 * @param string $template
	 * @param int    $depth
	 *
	 * @return string
	 * @throws \Exception
	 * @throws \Twig_Error_Loader
	 * @throws \Twig_Error_Runtime
	 * @throws \Twig_Error_Syntax
	 */
    public function FGCMenuRender($name = 'default', $template = 'default', $depth = 2)
    {
        return $this->menuRender->FGCMenuRender($name, $template, $depth);
    }
}