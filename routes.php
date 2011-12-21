<?php 
/*
	Plugin Name: Routes 
	Plugin URI: http://www.onezerozeroone.com/
	Description: Create routes in wordpress
	Version: 1.0
	Author: camilo tapia
	Author URI: http://www.onezerozeroone.com
	License: GPL2
*/

/*
    
    example for loading a specific page (put this in your functions.php):
    $routes = Routes::getInstance();
    $routes->get("/(^katt\/.+)?/", array('type' => 'pagename', 'name' => 'produkter'));
    
    example for loading a specific file (put this in your functions.php):
    $routes = Routes::getInstance();
    $routes->get("/(^katt\/.+)?/", array('type' => 'filename', 'name' => 'produkter.php'));
    
    
    
    All matches are the available like this:
    $routes = Routes::getInstance();
    $routeAction = $routes->getRouteAction();
    var_dump($routeAction->matches);
    
*/

	/*  Copyright 2011  camilo tapia  (email : camilo.tapia@24hr.se)

	    This program is free software; you can redistribute it and/or modify
	    it under the terms of the GNU General Public License, version 2, as 
	    published by the Free Software Foundation.

	    This program is distributed in the hope that it will be useful,
	    but WITHOUT ANY WARRANTY; without even the implied warranty of
	    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	    GNU General Public License for more details.

	    You should have received a copy of the GNU General Public License
	    along with this program; if not, write to the Free Software
	    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

class Routes
{
    
    private $routes = array();
    
    private $hookIsSet = false;
    
    private $routeAction = false;
    
    // singleton instance reference
	public static $singletonRef = NULL;
    
    // creates an instance of the class, if no isntance was created before (singleton implementation)
	public static function getInstance()
	{
		if (self::$singletonRef == NULL)
		{
			self::$singletonRef = new Routes();
		}
		return self::$singletonRef;
	}
	
	public function __construct()
	{
	    //$this->action = new stdClass;
    }
    
    public function getRoutesList()
    {
        return $this->routes;
    }
    
    public function setRouteAction($actionItem)
	{
	    $this->routeAction = $actionItem;
    }
    
    public function getRouteAction()
	{
	    return $this->routeAction;
    }
    
    public function get($regExp, $action)
    {
        
        if (!$this->hookIsSet)
        {
            add_action('parse_request', 'Routes::checkRoutes');
            $this->hookIsSet = true;
        }
        
        $routeItem = new stdClass;
        $routeItem->regExp = $regExp;
        $routeItem->action = $action;
        
        array_push($this->routes, $routeItem);
        
    }
    
    public static function checkRoutes(&$wp)
    {
        $routesInstance = Routes::getInstance();
        
        //$match = array();
        $action = false;
        
        foreach($routesInstance->getRoutesList() as $routeItem) 
        {
            preg_match_all($routeItem->regExp, $wp->request, $match);
            if (sizeof($match) > 0)
            {
                $action = new StdClass;
                $action->action = $routeItem->action;
                $action->matches = $match;
                
                break;
            }
        }
        
        if ($action !== false)
        {
            $routesInstance->setRouteAction($action);
            add_action('template_redirect', 'Routes::doRoute');
        }
    }
    
    public static function doRoute()
    {
        $routesInstance = Routes::getInstance();
        $routeAction = $routesInstance->getRouteAction();
        
        // make sure we dont show a 404
        global $wp_query;
            if ($wp_query->is_404) {
                $wp_query->is_404 = false;
                $wp_query->is_archive = true;
            }
        
        header("HTTP/1.1 200 OK");
        
        //var_dump($routeAction->action);
        //var_dump($routeAction);
        
        if ($routeAction->action['type'] == 'pagename')
        {
            query_posts(array('pagename' => $routeAction->action['name']));
        }
        else if ($routeAction->action['type'] == 'filename')
        {
            include (TEMPLATEPATH . '/' . $routeAction->action['name']);
            exit;
        }
        
       
    }
    
}

?>