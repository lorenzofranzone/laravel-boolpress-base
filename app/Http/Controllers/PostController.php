<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;

class PostController extends Controller
{

    private $validationPost = [
        'title' => 'required|string|max:255',
        'text' => 'required|string',
        'img' => 'required|string'
    ];

    // --------------------------------------------------
    // I N D E X
    // --------------------------------------------------

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::all();
        return view('posts.index', compact('posts'));
    }

    // --------------------------------------------------
    // C R E A T E
    // --------------------------------------------------

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create');
    }

    // --------------------------------------------------
    // S T O R E
    // --------------------------------------------------

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        // Validazione
        $request->validate([
            'img' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'text' => 'required|text'
        ]);

        $newPost = new Post;

        // Metodo 2
        $newPost->fill($data);
        $saved = $newPost->save();
        
        if($saved){
            $post = Post::orderBy('id', 'desc')->first();
            return redirect()->route('posts.show', compact('post'));
        }
        dd('Post non inserito');
    }

    // --------------------------------------------------
    // S H O W
    // --------------------------------------------------

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        if(empty($post)){
            abort('404');
        }
        return view('posts.show', compact('post'));
    }

    // --------------------------------------------------
    // E D I T
    // --------------------------------------------------

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        return view('posts.create', compact('post'));

        if(empty($post)){
            abort('404');
        }
    }

    // --------------------------------------------------
    // U P D A T E
    // --------------------------------------------------

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $post = Post::find($id);

        if(empty($post)){
            abort('404');
        }

        $data = $request->all();

        $request->validate($this->validationPost);
        
        $updated = $post->update($data);

        if($updated){
            $post = Post::find($id);
            return redirect()->route('posts.show', compact('post'));
        }
    }

    // --------------------------------------------------
    // D E S T R O Y
    // --------------------------------------------------

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $id = $post->id;
        $deleted = $post->delete();
        // $data = [
        //     'id' => $id,
        //     'posts' => Post::all()
        // ];
        // return view('posts.index', $data);
        return redirect()->route('posts.index')->with('id', $id);
    }
}
