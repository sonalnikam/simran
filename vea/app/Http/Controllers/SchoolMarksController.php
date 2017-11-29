<?php

namespace App\Http\Controllers;
use App\User;
use Illuminate\Http\Request;
use Auth;
use Session;
use App\Batch;
use Carbon\Carbon;
use App\Schoolmark;
use App\Admission;
use Illuminate\Support\Facades\DB;

class SchoolMarksController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function batchlist_bhandup()
    {
        $eight=Batch::where('branch','BHANDUP')->where('standard','=','VIII')->get();
        $ninth=Batch::where('branch','BHANDUP')->where('standard','=','IX')->get();
        $tenth=Batch::where('branch','BHANDUP')->where('standard','=','X')->get();
        $branch='BHANDUP';
        return view('schoolmarks.batchlist',compact('eight','ninth','tenth','branch'));
    }

    public function batchlist_mulund()
    {
        $eight=Batch::where('branch','MULUND')->where('standard','=','VIII')->get();
        $ninth=Batch::where('branch','MULUND')->where('standard','=','IX')->get();
        $tenth=Batch::where('branch','MULUND')->where('standard','=','X')->get();
        $branch='MULUND';
        return view('schoolmarks.batchlist',compact('eight','ninth','tenth','branch'));
    }

    public function createmarks($branch,Batch $batch,$standard)
    {
        $dt = Carbon::now();
        $current=$dt->format('Y'); 
        $next=$current+1;
        return view('schoolmarks.add',compact('batch','standard','current','next','branch'));
    }

    public function addmarks($branch,Batch $batch,$standard,Request $request)
    {
        $this->validate($request,[
            'from_year'=>'required',
            'to_year'=>'required',
            'date'=>'required',
            'topic_name'   =>'required',
            'portion'   =>'required',
            'total_marks'   =>'required|numeric',
            ]);

        $marks=new Schoolmark;
        $marks->fromyear=$request->from_year;
        $marks->toyear=$request->to_year;
        $marks->date=$request->date;
        $marks->branch=$branch;
        $marks->standard=$standard;
        $marks->batch=$batch->batchname;
        $marks->topic_name=$request->topic_name;
        $marks->portion=$request->portion;
        $marks->total_marks=$request->total_marks;
        $marks->save();

        session()->flash('message','Marks details added successfully!');
        return redirect('/schoolmarks/'.$branch.'/'.$batch->id.'/'.$standard.'/listmarks');
    }

    public function listmarks($branch,Batch $batch,$standard)
    {
        $marks=Schoolmark::where('branch',$branch)
                    ->where('batch',$batch->batchname)
                    ->where('standard',$standard)
                    ->orderBy('updated_at', 'desc')->paginate(50);

        return view('schoolmarks.index',compact('marks','batch','standard','branch'));
    }

    public function editmarks($branch,Schoolmark $marks,Batch $batch,$standard)
    {
        return view('schoolmarks.edit',compact('marks','batch','standard','branch'));
    }
    
    public function updatemarks($branch,Schoolmark $marks,Batch $batch,$standard,Request $request)
    {
        $this->validate($request,[
            'from_year'=>'required',
            'to_year'=>'required',
            'date'=>'required',
            'topic_name'   =>'required',
            'portion'   =>'required',
            'total_marks'   =>'required|numeric',
            ]);

        
        $marks->fromyear=$request->from_year;
        $marks->toyear=$request->to_year;
        $marks->date=$request->date;
        $marks->standard=$standard;
        $marks->batch=$batch->batchname;
        $marks->topic_name=$request->topic_name;
        $marks->portion=$request->portion;
        $marks->total_marks=$request->total_marks;
        $marks->update();

        session()->flash('message','Marks details updated successfully!');
        return redirect('/schoolmarks/'.$branch.'/'.$batch->id.'/'.$standard.'/listmarks');
    }


    public function deletemarks($branch,Schoolmark $marks,Batch $batch,$standard)
    {
       $marks->delete();
       session()->flash('message','Marks record deleted');
       return back(); 
    }

    public function search($branch,Batch $batch,$standard,Request $request)
    {
        $this->validate($request,[
            'searchtxt' => 'required',
            ]);
        $searchterm=$request->searchtxt;
        $marks=Schoolmark::where('topic_name','like', $searchterm.'%')
                    ->where('branch',$branch)
                    ->where('batch',$batch->batchname)
                    ->where('standard',$standard)
                    ->orderBy('updated_at', 'desc')->paginate(10);

        return view('schoolmarks.index',compact('marks','batch','standard','branch'));
    }


    public function addstudentmarks($branch,Schoolmark $marks,Batch $batch,$standard,Request $request)
    {
        $admission=Admission::where('branch',$branch)
                              ->where('fromyear',$marks->fromyear)
                              ->where('toyear',$marks->toyear)
                              ->where('standard',$standard)
                              ->where('admissionbatch',$batch->batchname)
                              ->paginate(50);
        
        return view('schoolmarks.addstudentmarks',compact('admission','marks','batch','standard','branch'));
    }

    public function storestudentmarks($branch,Schoolmark $marks,Batch $batch,$standard,Request $request)
    {
        /*$mark=DB::table('admission_marks')
                  ->where('admission_id',$admission->id)
                  ->where('mark_id',$marks->id)
                  ->value('marks_obtained');
        echo "helo".$mark;

        if(is_null($mark) or $mark=='')
        {
            $admission->marks()->attach($marks, ['marks_obtained'=>$marks_obtained]);
        }
        else
        {
            DB::table('admission_marks')
                  ->where('admission_id',$admission->id)
                  ->where('mark_id',$marks->id)
                  ->update(['marks_obtained' =>$marks_obtained]);
        }*/

        $count = sizeof($request->adm);
        for($i = 0; $i < $count; $i ++)
        {   
            
                $exists=DB::table('admission_school_marks')
                     ->where('admission_id',$request->adm[$i])
                     ->where('schoolmark_id',$marks->id)
                     ->value('marks_obtained');

                if(empty($exists))
                {
                    DB::table('admission_school_marks')->insert(
                [
                    'admission_id' => $request->adm[$i], 
                    'schoolmark_id'=> $marks->id,
                    'marks_obtained'=>$request->marks[$i],
                    'created_at'=> new \DateTime(),
                    'updated_at'=> new \DateTime()
                ]);
                }
                else
                {
                    DB::table('admission_school_marks')->where('admission_id',$request->adm[$i])
                     ->where('schoolmark_id',$marks->id)
                     ->update(['marks_obtained' =>$request->marks[$i]]);
                }
        }
        return redirect('/schoolmarks/'.$branch.'/'.$batch->id.'/'.$standard.'/listmarks');
    }

    public function liststudentmarks($branch,Schoolmark $marks,Batch $batch,$standard,Request $request)
    {
        $admission=Admission::where('branch',$branch)
                              ->where('fromyear',$marks->fromyear)
                              ->where('toyear',$marks->toyear)
                              ->where('standard',$standard)
                              ->where('admissionbatch',$batch->batchname)
                              ->paginate(50);

        
        return view('schoolmarks.liststudentmarks',compact('admission','marks','batch','standard','branch'));
    }

    public function summaryreportacayear($branch,Batch $batch,$standard)
    {
        $dt = Carbon::now();
        $current=$dt->format('Y'); 
        $next=$current+1;
        return view('schoolmarks.summaryreportacayear',compact('batch','standard','branch','current','next'));
    }

    public function viewreport($branch,Batch $batch,$standard,Request $request)
    {
        
        $this->validate($request,[
            'from_year'=>'required',
            'to_year'=>'required',
            ]);
        
        /*$summary=Admission::join('admission_marks','admission.id','=','admission_marks.admission_id')
                            ->join('marks','marks.id','=','admission_marks.mark_id')
                            ->where('admission.fromyear',$request->from_year)
                            ->where('admission.toyear',$request->to_year)
                            ->where('admission.branch',$branch)
                            ->where('admission.standard',$standard)
                            ->where('admission.admissionbatch',$batch->batchname)
                            ->select('admission.studentname','marks.topic_name','marks.portion','marks.total_marks','admission_marks.marks_obtained')
                            ->get();*/
        $admission=Admission::where('branch',$branch)
                              ->where('fromyear',$request->from_year)
                              ->where('toyear',$request->to_year)
                              ->where('standard',$standard)
                              ->where('admissionbatch',$batch->batchname)
                              ->get();
        
        $marks=Schoolmark::where('branch',$branch)
                    ->where('batch',$batch->batchname)
                    ->where('standard',$standard)
                    ->where('fromyear',$request->from_year)
                    ->where('toyear',$request->to_year)
                    ->get();
        
        return view('schoolmarks.summaryreport',compact('batch','standard','branch','current','next','admission','marks'));
    }
}
