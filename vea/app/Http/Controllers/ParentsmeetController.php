<?php

namespace App\Http\Controllers;
use App\User;
use Illuminate\Http\Request;
use Auth;
use Session;
use App\Enquiry;
use App\Parentsmeet;
use Carbon\Carbon;
use App\Batch;

class ParentsmeetController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create()
    {
        $dt = Carbon::now();
        $current=$dt->format('Y'); 
        $next=$current+1;
        return view('parentsmeet.add',compact('current','next'));

    }

     public function index()
    {
        $parentsmeet=Parentsmeet::orderBy('updated_at', 'desc')->paginate(10);
        return view('parentsmeet.index',compact('parentsmeet'));
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'from_year'=>'required',
            'to_year'=>'required',
            'date_call'=>'required',
            'branch'=>'required',
            'standard' => 'required',
            'batch'=>'required',
            'name' => 'required|min:3|max:50|regex:/^[\pL\s]+$/u',
            'parents_name' => 'required|min:3|max:50|regex:/^[\pL\s]+$/u',
            
            ]);
        $parentsmeet = new Parentsmeet;
        $parentsmeet->fromyear = $request->from_year;
        $parentsmeet->toyear = $request->to_year;
        $parentsmeet->date_of_call = $request->date_call;
        $parentsmeet->branch = $request->branch;
        $parentsmeet->standard = $request->standard;
        $parentsmeet->batch = $request->batch;
        $parentsmeet->studentname = $request->name;
        $parentsmeet->parentname = $request->parents_name;
        $parentsmeet->date_of_meet = $request->date_meet;
        $parentsmeet->reason = $request->reason;
        $parentsmeet->remarks = $request->remarks;
        $parentsmeet->save();
        session()->flash('message','Parents meet details added successfully!');
        return redirect('/parentsmeet');
    }
    public function edit(Parentsmeet $parentsmeet)
    {
        $batch=Batch::where('standard','=',$parentsmeet->standard)->get();
        return view('parentsmeet.edit',compact('parentsmeet','batch'));
    }
    public function update(Request $request,Parentsmeet $parentsmeet)
    {
       $this->validate($request,[
            'from_year'=>'required',
            'to_year'=>'required',
            'date_call'=>'required',
            'branch'=>'required',
            'standard' => 'required',
            'batch'=>'required',
            'name' => 'required|min:3|max:50|regex:/^[\pL\s]+$/u',
            'parents_name' => 'required|min:3|max:50|regex:/^[\pL\s]+$/u',
            
            ]);
        
        $parentsmeet->fromyear = $request->from_year;
        $parentsmeet->toyear = $request->to_year;
        $parentsmeet->date_of_call = $request->date_call;
        $parentsmeet->branch = $request->branch;
        $parentsmeet->standard = $request->standard;
        $parentsmeet->batch = $request->batch;
        $parentsmeet->studentname = $request->name;
        $parentsmeet->parentname = $request->parents_name;
        $parentsmeet->date_of_meet = $request->date_meet;
        $parentsmeet->reason = $request->reason;
        $parentsmeet->remarks = $request->remarks;
        $parentsmeet->update();
        session()->flash('message','Parent meet record updated successfully!');
        return redirect('/parentsmeet');
    }

    public function delete(Parentsmeet $parentsmeet)
    {
       $parentsmeet->delete();
       session()->flash('message','Parent meet record deleted');
       return back(); 
    }

    public function search(Request $request)
    {
        $this->validate($request,[
            'searchtxt' => 'required',
            ]);
        $searchterm=$request->searchtxt;
        $parentsmeet=Parentsmeet::where('studentname','like', $searchterm.'%')->orderBy('updated_at', 'desc')->paginate(10);

        return view('parentsmeet.index',compact('parentsmeet'));
    }
}
