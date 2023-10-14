<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(User $user)
    {
        $post=Post::where('user_id',$user->id)->paginate(20);      
        return view('dashboard',[
            'user'=>$user,
            'posts'=>$post
        ]);
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'titulo'=>'required|max:255',
            'descripcion'=>'required',
            'imagen'=>'required'

        ]);

        //primera forma
        // Post::create([
        //     'titulo'=>$request->titulo,
        //     'descripcion'=>$request->descripcion,
        //     'imagen'=>$request->imagen,
        //     'user_id'=>auth()->user()->id
        // ]);

        //segunda forma
        // $post=new Post;
        // $post->titulo=$request->titulo;
        // $post->descripcion=$request->descripcion;
        // $post->imagen=$request->imagen;
        // $post->user_id=auth()->user()->id;
        // $post->save();

        //3ra forma
        $request->user()->posts()->create([
            'titulo'=>$request->titulo,
            'descripcion'=>$request->descripcion,
            'imagen'=>$request->imagen,
            'user_id'=>auth()->user()->id
        ]);

        return redirect()->route('posts.index',auth()->user()->username);
    }

    public function show(Post $post)
    {
        return view('posts.show',[
            'post'=>$post,
        ]);
    }
}
