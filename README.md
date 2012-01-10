Routes for WP
===

Routes for WP is a simple plugin for Wordpress to catch and handle routes. Like in nodejs/express or python/django you can catch a request with something like this:

    $routes = Routes::getInstance();
    $routes->get("/foo/:bar", array('type' => 'pagename', 'name' => 'products'));
    
This example will catch any call to /foo/[something] and show the page with the slug "producs".
When inside that page, you can assess the parameters catched by writing:

    $routes = Routes::getInstance();
    echo $routes->param->bar;
    
You can also send a reg exp to the get command. Also, its possible to send a php file name as an action when something is catched;

    $routes = Routes::getInstance();
    $routes->get("/products\/prod\d/", array('type' => 'filename', 'name' => 'prod.php'));
    
This will take any call to /products/prod0 to /products/prod9 and use the file prod.php (under the current theme folder) to render what ever result prod.php want to render.

## Remember
Remember to install the plugin first

## License 

(GNU General Public License, version 2)

Copyright 2011  camilo tapia  (email : camilo.tapia@gmail.com)

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
