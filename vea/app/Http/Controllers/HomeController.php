<?php

namespace App\Http\Controllers;
use App\User;
use Illuminate\Http\Request;
use Auth;
use Session;
use App\Enquiry;
use App\Admission;
class HomeController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        
        $enquirycount=Enquiry::count();
        $orientationcount=Enquiry::where('date1','<>',NULL)->count();
        $usercount=User::count();
        $enquiry = Enquiry::orderBy('updated_at', 'desc')->limit(5)->get();
        $orientation = Enquiry::where('date1','<>',NULL)->orderBy('updated_at', 'desc')->limit(5)->get();
        $admissioncount=Admission::count();
        $admission = Admission::orderBy('updated_at', 'desc')->limit(5)->get();
        return view('home',compact('enquirycount','enquiry','usercount','orientationcount','orientation','admissioncount','admission'));


    }
}
