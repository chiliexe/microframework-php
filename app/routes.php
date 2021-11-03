<?php

$routes[] = ["/", "HomeController.index"];
$routes[] = ["/new", "HomeController.addPost"];
$routes[] = ["/post/create", "HomeController.create"];
$routes[] = ["/post/{id}", "HomeController.detailedPost"];
$routes[] = ["/post/edit/{id}", "HomeController.updateView"];
$routes[] = ["/post/delete/{id}", "HomeController.delete"];
$routes[] = ["/post/update/{id}", "HomeController.update"];
$routes[] = ["/posts", "HomeController.getPosts"];

return $routes;
