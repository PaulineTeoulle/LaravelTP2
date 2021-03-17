<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RecipesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $recipes = Recipe::all(); //get all recipes

        return view('recettes',array(
            'recipes' => $recipes,
        ));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $recipe = Recipe::where('id',$id)->first();

        $comments = Comment::all()->where('recipe_id',$id);
         return view('recipes/single', array(
                'recipe' => $recipe,
                'comments' =>$comments,
         ));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('recipes/create');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Gestion des images

        //TODO : verif si image existe deja => changer nom
        
        $urlString = $_SERVER['DOCUMENT_ROOT'];
        $info = pathinfo($urlString);
        $target_dir = $info['dirname'] . '\public\images\\';

        $file = request('media');
        $filename = $file->getClientOriginalName();

        $target_file = $target_dir . $filename;
        move_uploaded_file($file, $target_file);

        $recipe = new Recipe();
        $recipe->author_id = 1;
        $recipe->title = request('title');
        $recipe->content = request('content');
        $recipe->ingredients = request('ingredients');
        $recipe->url = 'url static'; //STATIQUE
        $recipe->date = date('Y-m-d H:i:s');
        $recipe->status = 'status static'; //STATIQUE
        $recipe->media = $filename; //STATIQUE
        $recipe->save();

        return redirect('/recettes');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $recipe = Recipe::where('id',$id)->first(); //get first recipe with recipe_nam == $recipe_name

        return view('recipes/edit', array( //Pass the recipe to the view
            'recipe' => $recipe
        ));
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
        $recipe = Recipe::findOrFail($id);
        $input = $request->all();
        $recipe->fill($input)->save();

        return view('recipes/single', array(
            'recipe' => $recipe
        ));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Recipe::findOrFail($id)->delete();
        $recipes = Recipe::all();
        return view('recettes',array(
            'recipes' => $recipes,
        ));
    }


}
