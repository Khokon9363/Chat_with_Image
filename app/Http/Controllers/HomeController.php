<?php

namespace App\Http\Controllers;

use App\Chat;
use App\User;
use App\Slider;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
    public function users()
    {
        return response()->json(User::where('id','!=',auth()->user()->id)->get(), 200);
    }
    public function user($id)
    {
        return response()->json(Chat::where('sender_id',auth()->user()->id)->where('receiver_id',$id)->orWhere('receiver_id',auth()->user()->id)->where('sender_id',$id)->get(), 200);
    }
    public function store(Request $request)
    {   
        if ($request->chat != null || $request->hasFile('image')) {
            $chat = new Chat();
        
            if ($request->hasFile('image')) {
               $name = $request->file('image')->extension();
               $rename = time().'_'.date('d-m-Y').'.'.$name;
               $request->file('image')->move('chats_pics', $rename);
    
               $chat->image = $rename;
            }
            
            $chat->chat = $request->chat;
            $chat->receiver_id = $request->receiver_id;
            $chat->sender_id = auth()->user()->id;
            $chat->save();
            return response()->json($chat, 200);   
        }
    }
}