<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Article;
use Validator;

class ArticleController extends BaseController
{
    /**
     * Display a listing of the resource.
     * GET
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user_id = $request->user()->id;
        $articles = Article::where('user_id',$user_id)->orderBy('article_id','asc')->get();

        $data['user_name'] = $request->user()->name;

        if (is_null($articles) || $articles->isEmpty()) {
            return $this->sendError('There are not articles',$data);
        }
        $data['products'] = $articles;

        return $this->sendResponse($data, 'Articles from database');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *  POST
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
            'description' => 'required',
            'amount' => 'required|numeric',
            'price' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Error de validacion', $validator->errors());
        }

        $input['user_id'] = $request->user()->id;

        $article = Article::create($input);

        return $this->sendResponse($article, 'Article was created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $user_id = $request->user()->id;
        $article = Article::where([
            ['user_id','=', $user_id],
            ['article_id', '=', $id]
        ])->first();

        if (is_null($article)) {
            return $this->sendError('Article not found');
        }

        return $this->sendResponse($article, 'Datos traidos exitosamente');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
            'description' => 'required',
            'amount' => 'required|numeric',
            'price' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Error de validacion', $validator->errors());
        }

        //Buscar el elemento a actualizar
        $user_id = $request->user()->id;
        $article = Article::where([
            ['user_id','=', $user_id],
            ['article_id', '=', $id]
        ])->first();

        //Si no se encuentra el elemento
        if (is_null($article)) {
            return $this->sendError('Article not found');
        }

        $article->name = $input['name'];
        $article->description = $input['description'];
        $article->amount = $input['amount'];
        $article->price = $input['price'];

        $article->save();

        return $this->sendResponse($article,'El producto fue actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        $user_id = $request->user()->id;


        $article = Article::where([
            [ 'article_id', '=', $id ],
            ['user_id', '=', $user_id]
        ])->first();


        if (is_null($article)) {
            return $this->sendError('Producto no encontrado');
        }

        // return $this->sendResponse($article, 'Producto eliminado correctamente');
        Article::find($id)->delete();

        return $this->sendResponse($id, 'Producto eliminado correctamente');

    }
}
