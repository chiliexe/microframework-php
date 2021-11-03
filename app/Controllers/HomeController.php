<?php

namespace App\Controllers;

use App\Models\Post;
use Core\Controller;
use stdClass;


class HomeController extends Controller
{
    private $post;
    public function __construct()
    {
        $this->setLayoutPath("layout");
        $this->post = new Post();
    }

    public function index($args)
    {
        $this->data->title = "Página Inicial";
        $this->data->nome = "robertinha dahora";
        $this->render("home/index");
    }

    public function getPosts($args, object $request){
        $this->data->posts = $this->post->findAll("-id");
        $this->data->title = "Todos os Posts";
        $this->render("home/posts");
    }

    public function detailedPost(array $params, object $request)
    {
        $this->data->post = $this->post->findById($params[0]);
        $this->data->title = $this->data->post->title;
        $this->render('home/detailed');
    }

    public function addPost($params, object $request)
    {
        $this->data->title = "Criar novo Artigo";
        $this->render('home/new');
    }

    public function create($params, object $request)
    {
        if(!isset($request->post))
            $this->redirect("/new");

        $success = $this->post->create([
            "title" => $request->post->title,
            "content" => $request->post->content
        ]);

        if($success)
            $this->redirect("/posts");
        else
            echo "erro";
    }

    public function updateView($params, object $request)
    {
        $this->data->title = "Atualizar Postagem";
        $this->data->post = $this->post->findById($params[0]);
        $this->render("home/update");
    }
    public function update($params, object $request)
    {
        $data = [
            "title" => $request->post->title,
            "content" => $request->post->content
        ];
       $success = $this->post->update($data, $params[0]);

       if($success)
           $this->redirect("/posts");
       else
           echo "erro";
    }

    public function delete($params, $request)
    {
        $success = $this->post->delete($params[0]);
        if($success)
            $this->redirect("/posts");
        else
            echo "erro";
    }
}