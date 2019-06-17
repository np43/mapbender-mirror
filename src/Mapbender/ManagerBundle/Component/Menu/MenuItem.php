<?php


namespace Mapbender\ManagerBundle\Component\Menu;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class MenuItem
{
    /** @var string */
    protected $title;
    /** @var string|null */
    protected $route;
    /** @var MenuItem[] */
    protected $children;

    /**
     * @param string $title
     * @param string|null $route
     */
    public function __construct($title, $route)
    {
        $this->title = $title;
        $this->route = $route;
        $this->children = array();
    }

    /**
     * @param $title
     * @param $route
     * @return static
     */
    public static function create($title, $route)
    {
        return new static($title, $route);
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string|null
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @return MenuItem[]
     */
    public function getSubroutes()
    {
        return $this->children;
    }

    /**
     * @param MenuItem[] $children
     * @return $this
     */
    public function addChildren($children)
    {
        $this->children = array_merge($this->children, $children);
        return $this;
    }

    public function enabled(AuthorizationCheckerInterface $authorizationChecker)
    {
        return true;
    }

    public function filter(AuthorizationCheckerInterface $authorizationChecker)
    {
        if (!$this->enabled($authorizationChecker)) {
            return false;
        } else {
            foreach ($this->children as $index => $child) {
                if (!$child->filter($authorizationChecker)) {
                    unset($this->children[$index]);
                }
            }
            return true;
        }
    }

    public function isCurrent(Request $request)
    {
        $route = $request->attributes->get('_route');
        return $this->route !== null && $route === $this->route;
    }

    public function getActive(Request $request)
    {
        if ($this->isCurrent($request)) {
            return true;
        } else {
            foreach ($this->children as $child) {
                if ($child->getActive($request)) {
                    return true;
                }
            }
            return false;
        }
    }
}
