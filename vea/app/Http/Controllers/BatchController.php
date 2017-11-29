<?php

namespace App\Http\Controllers;
use App\User;
use Illuminate\Http\Request;
use Auth;
use Session;
use App\Enquiry;
use App\Admission;
use App\Day;
use App\Batch;
use Carbon\Carbon;
class BatchController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        
        $batch=Batch::orderBy('updated_at', 'desc')->paginate(10);
        $days=Day::all();

        return view('batch.index',compact('batch','days'));


    }

    public function create()
    {
        $days=Day::all();
        $dt = Carbon::now();
        $current=$dt->format('Y'); 
        $next=$current+1;
        return view('batch.add',compact('days','current','next'));
    }

    public function save(Request $request)
    {
        $this->validate($request,[
            'from_year'=>'required',
            'to_year'=>'required',
            'branch'   =>'required',
            'standard'=>'required',
            'bname'=>'required',
            'days'=>'required',
            'from'=>'required',
            'to'=>'required',
            ]);

        $batch = new Batch;
        $batch->fromyear=$request->from_year;
        $batch->toyear=$request->to_year;
        $batch->branch=$request->branch;
        $batch->standard=request('standard');
        $batch->batchname=request('bname');
        $batch->start=request('from');
        $batch->end=request('to');
        $batch->start1=request('from1');
        $batch->end1=request('to1');
        $batch->save();
        session()->flash('message','Batch details added!');
        $batch->days()->attach($request->input('days')?: []);
        return redirect('/batch');
    }

    public function edit(Batch $batch)
    {
        $days=Day::all();
        return view('batch.edit',compact('batch','days'));
    }

    public function update(Request $request,Batch $batch)
    {
         $this->validate($request,[
            'standard'=>'required',
            'from_year'=>'required',
            'to_year'=>'required',
            'branch'   =>'required',
            'bname'=>'required',
            'days'=>'required',
            'from'=>'required',
            'to'=>'required',
            ]);
        $batch->fromyear=$request->from_year;
        $batch->toyear=$request->to_year;
        $batch->branch=$request->branch;
        $batch->standard=request('standard');
        $batch->batchname=request('bname');
        $batch->start=request('from');
        $batch->end=request('to');
        $batch->start1=request('from1');
        $batch->end1=request('to1');

        $batch->update();

        session()->flash('message','Batch details updated!');
        $batch->days()->sync($request->input('days')?: []);
        return redirect('/batch');
    }

    public function delete(Batch $batch)
    {
       $batch->delete();
       session()->flash('message','Batch record deleted');
       return back(); 
    }

    public function filters(Request $request)
    {
        
        $this->validate($request,[
            'filters' => 'required',
            ]);
        $days=Day::all();
        $filter=$request->filters;
        if($filter=='LATESTTOOLD')
        {
            $batch=Batch::orderBy('updated_at', 'desc')->paginate(10);
        }
        elseif($filter=='ONLYVIII')
        {
            $batch=Batch::where('standard','=','VIII')->orderBy('updated_at', 'desc')->paginate(10);
        }
        elseif($filter=='ONLYIX')
        {
            $batch=Batch::where('standard','=','IX')->orderBy('updated_at', 'desc')->paginate(10);
        }
        elseif($filter=='ONLYX')
        {
            $batch=Batch::where('standard','=','X')->orderBy('updated_at', 'desc')->paginate(10);
        }
        elseif($filter=='ONLYIXANDX')
        {
            $batch=Batch::where('standard','=','IX')->orWhere('standard','=','X')->orderBy('updated_at', 'desc')->paginate(10);
        }
        elseif($filter=='BHANDUP')
        {
            $batch=Batch::where('branch','=','BHANDUP')->orderBy('updated_at', 'desc')->paginate(10);
        }
        elseif($filter=='MULUND')
        {
            $batch=Batch::where('branch','=','MULUND')->orderBy('updated_at', 'desc')->paginate(10);
        }



        return view('batch/index',compact('batch','days'));
    }

    public function search(Request $request)
    {
        $this->validate($request,[
            'searchtxt' => 'required',
            ]);
        $days=Day::all();
        $searchterm=$request->searchtxt;
        $batch=Batch::where('batchname','like',$searchterm.'%')->paginate(10);

        return view('batch/index',compact('batch','days'));
    }

}
