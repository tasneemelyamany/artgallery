<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Egyptian_Images;

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
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Egyptian_Images::where('user_id', $user->id);
        if($request->category){
            $query->where('category', $request->category);
        }
        if($request->sort_by){
            $order = ($request->sort_by == 'oldest')?'ASC':'DESC';
            $query->orderBy('created_at',$order);
        }else{
            $query->orderBy('created_at','desc');  //latest first
        }
        $data['images'] = $query->paginate(3);

        return view('home', $data);
    }
}