<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Page;
use LaravelLocalization;
use Illuminate\Http\Request;
use App\Models\UserReport;
use Illuminate\Support\Facades\Auth;


class PageController extends Controller
{
    public function __construct()
    {
        $local = (!empty(Request()->route())) ? (Request()->route()->parameters()['locale']) : 'en';
        LaravelLocalization::setLocale($local);
    }

    /*----------------------------------------------------
    || Name     : get pages                               |
    || Tested   : Done                                    |
    || parameter:                                         |
    || Info     : type                                    |
    -----------------------------------------------------*/
    public function GetPages(Request $request)
    {
        $pages=Page::where('slug',$request->slug)->first();

        return $this->responseWithoutMessageJson(1,$pages);
    }

    /*----------------------------------------------------
    || Name     : get message                            |
    || Tested   : Done                                    |
    || parameter:                                         |
    || Info     : type                                    |
    -----------------------------------------------------*/
    public function sendMessage(Request $request)
    {
        $rule = [
            'name' => 'required',
            'email' => 'required',
            'message' => 'required',
        ];

        $customMessages = [
            'required' => __('validation.attributes.required'),
        ];

        $validator = validator()->make($request->all(), $rule, $customMessages);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'message' => validationErrorsToString($validator->errors())], 422);
        }
        
        $message = UserReport::create([
            'name' => $request->name,
            'email' => $request->email,
            'message' => $request->message,
            'user_id' => Auth::id(),
            'status' => 'not_responded'
        ]);

        return $this->responseWithoutMessageJson(1,$message);
    }
}
