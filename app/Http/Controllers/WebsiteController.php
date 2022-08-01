<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserReport;
use App\Models\Page;

class WebsiteController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('website');
    }

        /*----------------------------------------------------
    || Name     : get message                            |
    || Tested   : Done                                    |
    || parameter:                                         |
    || Info     : type                                    |
    -----------------------------------------------------*/
    public function sendMessage(Request $request)
    {
        
        $message = UserReport::create([
            'name' => $request->name,
            'email' => $request->email,
            'message' => $request->message,
            //'user_id' => Auth::id(),
            'status' => 'not_responded'
        ]);

        return view('website');
    }
    
    public function terms(){

        //$page = Page::where('id',$id)->select('name_'. app()->getLocale() .' as name', 'description_'. app()->getLocale().' as description')->first();
//dd($page);
        $pages = Page::all();
        return view('terms',compact('pages'));
    }
}
