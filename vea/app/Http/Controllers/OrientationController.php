<?php

namespace App\Http\Controllers;
use App\User;
use Illuminate\Http\Request;

use Auth;
use Session;
use App\Enquiry;

class OrientationController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function view(Enquiry $enquiry)
    {
        
        return \Response::json($enquiry);
    }

    public function add(Request $request,Enquiry $enquiry)
    {
    
    if($request->date1!="")
    {
        $enquiry->date1=$request->date1;    
    }
    if($request->response1!="")
    {
        $enquiry->response1=$request->response1;    
    }
    if($request->attendance1!="")
    {
        $enquiry->attendance1=$request->attendance1;    
    }
    if($request->date2!="")
    {
        $enquiry->date2=$request->date2;    
    }
    if($request->response2!="")
    {
        $enquiry->response2=$request->response2;    
    }
    if($request->attendance2!="")
    {
        $enquiry->attendance2=$request->attendance2;    
    }
    if($request->date3!="")
    {
        $enquiry->date3=$request->date3;    
    }
    if($request->response3!="")
    {
        $enquiry->response3=$request->response3;    
    }
    if($request->attendance3!="")
    {
        $enquiry->attendance3=$request->attendance3;    
    }
    
    $enquiry->update();

    return \Response::json($enquiry);
    }

    public function index()
    {
        $enquiry=Enquiry::where('date1','<>',NULL)->orderBy('updated_at', 'desc')->paginate(10);
        return view('orientation/index',compact('enquiry'));
    }


     public function filters(Request $request)
    {
        
        $this->validate($request,[
            'filters' => 'required',
            ]);
        $filter=$request->filters;
        if($filter=='LATESTTOOLD')
        {
            $enquiry=Enquiry::where('date1','<>',NULL)->orderBy('date', 'desc')->paginate(10);
        }
        elseif($filter=='ONLYVIII')
        {
            $enquiry=Enquiry::where('date1','<>',NULL)->where('standard','=','VIII')->orderBy('updated_at', 'desc')->paginate(10);
        }
        elseif($filter=='ONLYIX')
        {
            $enquiry=Enquiry::where('date1','<>',NULL)->where('standard','=','IX')->orderBy('updated_at', 'desc')->paginate(10);
        }
        elseif($filter=='ONLYX')
        {
            $enquiry=Enquiry::where('date1','<>',NULL)->where('standard','=','X')->orderBy('updated_at', 'desc')->paginate(10);
        }
        elseif($filter=='ONLYIXANDX')
        {
            $enquiry=Enquiry::where('date1','<>',NULL)->where(function ($query) {
                $query->where('standard','=','X')
                      ->orWhere('standard','=','IX');
            })
            ->orderBy('updated_at', 'desc')->paginate(10);
        }


        return view('orientation/index',compact('enquiry'));
    }

    public function search(Request $request)
    {
        $this->validate($request,[
            'searchtxt' => 'required',
            ]);
        $searchterm=$request->searchtxt;
        $enquiry=Enquiry::where('date1','<>',NULL)->where('name','like',$searchterm.'%')->orderBy('updated_at', 'desc')->paginate(10);

        return view('orientation/index',compact('enquiry'));
    }
}
