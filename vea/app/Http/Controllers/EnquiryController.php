<?php

namespace App\Http\Controllers;
use App\User;
use Illuminate\Http\Request;
use Auth;
use Session;
use App\Enquiry;
class EnquiryController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create()
    {
        return view('enquiry/add');
    }

     public function index()
    {
        $enquiry=Enquiry::orderBy('updated_at', 'desc')->paginate(10);
        return view('enquiry/index',compact('enquiry'));
    }

     public function filters(Request $request)
    {
        
        $this->validate($request,[
            'filters' => 'required',
            ]);
        $filter=$request->filters;
        if($filter=='LATESTTOOLD')
        {
            $enquiry=Enquiry::orderBy('date', 'desc')->paginate(10);
        }
        elseif($filter=='ONLYVIII')
        {
            $enquiry=Enquiry::where('standard','=','VIII')->orderBy('updated_at', 'desc')->paginate(10);
        }
        elseif($filter=='ONLYIX')
        {
            $enquiry=Enquiry::where('standard','=','IX')->orderBy('updated_at', 'desc')->paginate(10);
        }
        elseif($filter=='ONLYX')
        {
            $enquiry=Enquiry::where('standard','=','X')->orderBy('updated_at', 'desc')->paginate(10);
        }
        elseif($filter=='ONLYIXANDX')
        {
            $enquiry=Enquiry::where('standard','=','IX')->orWhere('standard','=','X')->orderBy('updated_at', 'desc')->paginate(10);
        }


        return view('enquiry/index',compact('enquiry'));
    }

    public function store(Request $request)
    {
        
        
        $this->validate($request,[
            'standard' => 'required',
            'date'=>'required',
            'name' => 'required|min:3|max:50|regex:/^[\pL\s]+$/u|unique:enquiry,name,fatherno,motherno',
            'school' => 'required',
            'otherschool' =>'nullable',
            'fatherno' => 'nullable|digits:10',
            'motherno' => 'nullable|digits:10',
            'landline' =>'nullable|digits:10',

            ]);
        $enquiry = new Enquiry;
        $enquiry->standard = $request->standard;
        $enquiry->date=$request->date;
        $enquiry->name=$request->name;
        $enquiry->school=$request->school;
        $enquiry->otherschool=$request->otherschool;
        $enquiry->fatherno=$request->fatherno;
        $enquiry->motherno=$request->motherno;
        $enquiry->landline=$request->landline;
        $enquiry->save();
        session()->flash('message','Enquiry added successfully!');
        return redirect('/enquiry');
    }
    public function edit(Enquiry $enquiry)
    {
        return view('enquiry/edit',compact('enquiry'));
    }
    public function update(Request $request,Enquiry $enquiry)
    {
       
       $this->validate($request,[
            'standard' => 'required',
            'date'=>'required',
            'name' => 'required|min:3|max:50|regex:/^[\pL\s]+$/u',
            'school' => 'required',
            'otherschool' =>'nullable',
            'fatherno' => 'nullable|digits:10',
            'motherno' => 'nullable|digits:10',
            'landline' =>'nullable|digits:10',

            ]);
        $enquiry->standard = $request->standard;
        $enquiry->date=$request->date;
        $enquiry->name=$request->name;
        $enquiry->school=$request->school;
        $enquiry->otherschool=$request->otherschool;
        $enquiry->fatherno=$request->fatherno;
        $enquiry->motherno=$request->motherno;
        $enquiry->landline=$request->landline;
        $enquiry->update();
        session()->flash('message','Enquiry record updated successfully!');
        return redirect('/enquiry');
    }

    public function delete(Enquiry $enquiry)
    {
       $enquiry->delete();
       session()->flash('message','Enquiry record deleted');
       return back(); 
    }

    public function search(Request $request)
    {
        $this->validate($request,[
            'searchtxt' => 'required',
            ]);
        $searchterm=$request->searchtxt;
        $enquiry=Enquiry::where('name','like', $searchterm.'%')->orderBy('updated_at', 'desc')->paginate(10);

        return view('enquiry/index',compact('enquiry'));
    }
}
