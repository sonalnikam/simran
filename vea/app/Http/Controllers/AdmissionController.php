<?php

namespace App\Http\Controllers;
use App\User;
use Illuminate\Http\Request;
use Auth;
use Session;
use App\Enquiry;
use App\Admission;
use App\Batch;
use App\Fee;
use \PDF;
use Mail;
use File;
use Carbon\Carbon;
use App\Receipt;

class AdmissionController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        
        $eight=Batch::where('standard','=','VIII')->get();
        $ninth=Batch::where('standard','=','IX')->get();
        $tenth=Batch::where('standard','=','X')->get();

        return view('admission.index',compact('eight','ninth','tenth'));


    }

    public function create()
    {
        $dt = Carbon::now();
        $current=$dt->format('Y'); 
        $next=$current+1;
        return view('admission.add',compact('current','next'));
    }

    public function standard($standard,$branch)
    {
        $batch=Batch::where('branch',$branch)
                      ->where('standard','=',$standard)->get();
        return \Response::json($batch);
    }

    public function batch($batch,$standard,$branch)
    {
       $batchname=$batch;
       $batch=Batch::where('branch',$branch)
        ->where('batchname','=',$batchname)
        ->where('standard','=',$standard)
        ->join('batch_day','batches.id','=','batch_day.batch_id')
        ->join('days','days.id','=','batch_day.day_id')
        ->select('days.days')
        ->get();

        $batchcount=Batch::where('branch',$branch)
        ->where('batchname','=',$batchname)
        ->where('standard','=',$standard)
        ->join('batch_day','batches.id','=','batch_day.batch_id')
        ->join('days','days.id','=','batch_day.day_id')
        ->select('days.days')
        ->count();
        
        /*$days="";*/
        /*foreach($batch as $bat)
        { 
            $days=$days.' '.$bat->days;

        }*/
        $day1=$batch[0]->days;
        $day2="";
        $timings1="";
        if($batchcount==2)
        {
            $day2=$batch[1]->days;
        }
        
        $day2exists=0;

        if(!(empty($day2)))
        {
            $start1=Batch::where('branch',$branch)
            ->where('batchname','=',$batchname)
            ->where('standard','=',$standard)
            ->value('start1');
            $end1=Batch::where('batchname','=',$batchname)
            ->where('standard','=',$standard)
            ->value('end1');
            $timings1=$start1.' - '.$end1;
            $day2exists=1;
        }

        $start=Batch::where('branch',$branch)
        ->where('batchname','=',$batchname)
        ->where('standard','=',$standard)
        ->value('start');
        $end=Batch::where('batchname','=',$batchname)
        ->where('standard','=',$standard)
        ->value('end');
       
        
        $timings=$start.' - '.$end;

         $data = ([
            'day1'=> $day1,
            'day2'=> $day2,
            'timings'=> $timings,
            'timings1'=> $timings1,
            'day2exists'=>$day2exists
            ]);
        return \Response::json($data);
    }

    public function save(Request $request)
    {
        $this->validate($request,[
            'from_year'=>'required',
            'to_year'=>'required',
            'branch'   =>'required',
            'date'=>'required',
            'name' => 'required|min:3|max:50|regex:/^[\pL\s]+$/u',
            'address'=>'required',
            'school' => 'required',
            'otherschool' =>'nullable',
            'fatherno' => 'nullable|digits:10',
            'motherno' => 'nullable|digits:10',
            'whatsapptext' =>'required',
            'landline' =>'nullable|digits:10',
            'email'    =>'nullable|email',
            'standard'=>'required',
            'admbatch'=>'required',
            'timings'=>'required',
            'days'=>'required',
            'onumber'=>'nullable|digits:10',
            ]);

        $fee_id=Fee::where('standard','=',$request->standard)
                     ->where('fromyear','=',$request->from_year)
                     ->where('toyear','=',$request->to_year)
                     ->value('id');

        $admission=new Admission;
        $admission->fromyear=$request->from_year;
        $admission->toyear=$request->to_year;
        $admission->branch=$request->branch;
        $admission->date=$request->date;
        $admission->studentname=$request->name;
        $admission->address=$request->address;
        $admission->school=$request->school;
        $admission->otherschool=$request->otherschool;
        $admission->fatherno=$request->fatherno;
        $admission->motherno=$request->motherno;
        $admission->whatsappon=$request->whatsapptext;
        $admission->landline=$request->landline;
        $admission->email=$request->email;
        $admission->standard=$request->standard;
        $admission->admissionbatch=$request->admbatch;
        $admission->timing1=$request->timings;
        $admission->day1=$request->days;
        $admission->timing2=$request->timings1;
        $admission->day2=$request->days1;
        $admission->parentname=$request->pname;
        $admission->occupation=$request->occupation;
        $admission->officeaddress=$request->oaddress;
        $admission->officenumber=$request->onumber;
        $admission->lasttermpercent=$request->lasttermpercent;
        $admission->english1=$request->english1;
        $admission->english2=$request->english2;
        $admission->overallpercent=$request->overallpercent;
        $admission->fee_id=$fee_id;
        $admission->save();

        session()->flash('message','Admission record added successfully!');
        $batch_id=Batch::where('batchname','=',$admission->admissionbatch)->value('id');
        $standard=$admission->standard;
        return redirect('/batch/'.$batch_id.'/'.$standard.'/admission');
    }

    public function list(Admission $admission)
    {
        $batch=Batch::where('batchname','=',$admission->admissionbatch)->value('id');
        return view('admission.list',compact('admission','batch'));
    }

    public function edit(Admission $admission)
    {
        $batch_id=Batch::where('batchname','=',$admission->admissionbatch)->value('id');
        $batch=Batch::where('standard','=',$admission->standard)->get();
        return view('admission.edit',compact('admission','batch','batch_id'));
    }

    public function update(Request $request,Admission $admission)
    {
         $this->validate($request,[
            'from_year'=>'required',
            'to_year'=>'required',
            'branch'   =>'required',
            'date'=>'required',
            'name' => 'required|min:3|max:50|regex:/^[\pL\s]+$/u',
            'address'=>'required',
            'school' => 'required',
            'otherschool' =>'nullable',
            'fatherno' => 'nullable|digits:10',
            'motherno' => 'nullable|digits:10',
            'whatsapptext' =>'required',
            'landline' =>'nullable|digits:10',
            'email'    =>'nullable|email',
            'standard'=>'required',
            'admbatch'=>'required',
            'timings'=>'required',
            'days'=>'required',
            'onumber'=>'nullable|digits:10',
            ]);

         $fee_id=Fee::where('standard','=',$request->standard)
                     ->where('fromyear','=',$request->from_year)
                     ->where('toyear','=',$request->to_year)
                     ->value('id');
        
         $admission->fromyear=$request->from_year;
         $admission->toyear=$request->to_year;
         $admission->branch=$request->branch;
         $admission->date=$request->date;
         $admission->studentname=$request->name;
         $admission->address=$request->address;
         $admission->school=$request->school;
         $admission->otherschool=$request->otherschool;
         $admission->fatherno=$request->fatherno;
         $admission->motherno=$request->motherno;
         $admission->whatsappon=$request->whatsapptext;
         $admission->landline=$request->landline;
         $admission->email=$request->email;
         $admission->standard=$request->standard;
         $admission->admissionbatch=$request->admbatch;
         $admission->timing=$request->timings;
         $admission->days=$request->days;
         $admission->parentname=$request->pname;
         $admission->occupation=$request->occupation;
         $admission->officeaddress=$request->oaddress;
         $admission->officenumber=$request->onumber;
         $admission->lasttermpercent=$request->lasttermpercent;
         $admission->english1=$request->english1;
         $admission->english2=$request->english2;
         $admission->overallpercent=$request->overallpercent;
         $admission->fee_id=$fee_id;
        $admission->update();

        session()->flash('message','Admission record updated successfully!');
        $batch_id=Batch::where('batchname','=',$admission->admissionbatch)->value('id');
        $standard=$admission->standard;
        return redirect('/batch/'.$batch_id.'/'.$standard.'/admission');
    }

    public function delete(Admission $admission)
    {
       $admission->delete();
       session()->flash('message','Admission record deleted');
       return back(); 
    }

    public function filters(Request $request)
    {
        
        $this->validate($request,[
            'filters' => 'required',
            ]);
        $filter=$request->filters;
        if($filter=='LATESTTOOLD')
        {
            $admission=Admission::orderBy('date', 'desc')->paginate(10);
        }
        elseif($filter=='ONLYVIII')
        {
            $admission=Admission::where('standard','=','VIII')->orderBy('date', 'desc')->paginate(10);
        }
        elseif($filter=='ONLYIX')
        {
            $admission=Admission::where('standard','=','IX')->orderBy('date', 'desc')->paginate(10);
        }
        elseif($filter=='ONLYX')
        {
            $admission=Admission::where('standard','=','X')->orderBy('date', 'desc')->paginate(10);
        }
        elseif($filter=='ONLYIXANDX')
        {
            $admission=Admission::where('standard','=','IX')->orWhere('standard','=','X')->orderBy('date', 'desc')->paginate(10);
        }


        return view('admission.index',compact('admission'));
    }

    public function search(Batch $batch,$standard,Request $request)
    {
        $this->validate($request,[
            'searchtxt' => 'required',
            ]);
        $searchterm=$request->searchtxt;
        $admission=Admission::where('studentname','like',$searchterm.'%')
        ->where('admissionbatch','=',$batch->batchname)
        ->where('standard','=',$standard)
        ->paginate(10);
       
        return view('admission.admissionbatchlist',compact('admission','standard','batch'));
    }

    public function admissionsview(Batch $batch,$standard)
    {
        $admission=Admission::where('admissionbatch','=',$batch->batchname)
        ->where('standard','=',$standard)
        ->paginate(10);
       
        return view('admission.admissionbatchlist',compact('admission','standard','batch'));
    }

    public function fee(Admission $admission,$installment)
    {
        $batch_id=Batch::where('batchname','=',$admission->admissionbatch)->value('id');
        $batch=Batch::find($batch_id);
        $fee=Fee::where('standard','=',$admission->standard)->get();
        return view('admission.feeadd',compact('admission','installment','fee','batch'));
    }

    public function feeadd(Admission $admission,$installment,Request $request)
    {
        
        $this->validate($request,[
            'date' => 'required',
            'paymentmode'=>'required',
            ]);

        $paymentmode=$request->paymentmode;
        $installment=$installment;

        $receipt=Receipt::find(1);
        $cashcount=$receipt->cash_receipt;
        $chequecount=$receipt->cheque_receipt;

        
        if($paymentmode=='CASH')
        {
            if($admission["installment_date".$installment]=="")
            {
                $cashcount=$cashcount+1;
                $admission["receipt_id".$installment]=$cashcount;
            }
            elseif($admission["installment_date".$installment]!="" and ($admission["installment_mode".$installment]=="CHEQUE" or $admission["installment_mode".$installment]=="ONLINEPAYMENT"))
            {
                $cashcount=$cashcount+1;
                $chequecount=$chequecount-1;
                $admission["receipt_id".$installment]=$cashcount;
            }
            $admission["installment_date".$installment]=$request->date;
            $admission["installment_mode".$installment]=$request->paymentmode;
            $admission["bank".$installment]="";
            $admission["branch".$installment]="";
            $admission["chequeno".$installment]="";
            $admission["transactionid".$installment]="";
        }
        elseif($paymentmode=='CHEQUE')
        {
            
            if($admission["installment_date".$installment]=="")
            {
                $chequecount=$chequecount+1;
                $admission["receipt_id".$installment]=$cashcount;
            }
            elseif($admission["installment_date".$installment]!="" and $admission["installment_mode".$installment]=="CASH")
            {
                $chequecount=$chequecount+1;
                $cashcount=$cashcount-1;
                $admission["receipt_id".$installment]=$cashcount;
            }
            $admission["installment_date".$installment]=$request->date;
            $admission["installment_mode".$installment]=$request->paymentmode;
            $admission["bank".$installment]=$request->bank;
            $admission["branch".$installment]=$request->branch;
            $admission["chequeno".$installment]=$request->chequeno;
            $admission["transactionid".$installment]="";
        }
        elseif($paymentmode=='ONLINEPAYMENT')
        {
            if($admission["installment_date".$installment]=="")
            {
                $chequecount=$chequecount+1;
                $admission["receipt_id".$installment]=$chequecount;
            }
            elseif($admission["installment_date".$installment]!="" and $admission["installment_mode".$installment]=="CASH")
            {
                $chequecount=$chequecount+1;
                $cashcount=$cashcount-1;
                $admission["receipt_id".$installment]=$chequecount;
            }
            $admission["installment_date".$installment]=$request->date;
            $admission["installment_mode".$installment]=$request->paymentmode;
            $admission["transactionid".$installment]=$request->transactionid;
            $admission["bank".$installment]="";
            $admission["branch".$installment]="";
            $admission["chequeno".$installment]="";
        }
        $admission->update();
        Receipt::where('id',1)->update(['cash_receipt' =>$cashcount,'cheque_receipt'=>$chequecount]);
        session()->flash('message','Fee record updated!');
        $batch_id=Batch::where('batchname','=',$admission->admissionbatch)->value('id');
        return redirect('/batch/'.$batch_id.'/'.$admission->standard.'/admission');
    }

    public function viewfeereceipt(Admission $admission,$installment)
    {
        $batch_id=Batch::where('batchname','=',$admission->admissionbatch)->value('id');
        $batch=Batch::find($batch_id);
        $fee=Fee::where('standard','=',$admission->standard)->get();
        return view('pdf.receiptview',compact('admission','installment','fee','batch'));
    }

    public function downloadreceipt(Admission $admission,$installment)
    {
        $batch_id=Batch::where('batchname','=',$admission->admissionbatch)->value('id');
        $batch=Batch::find($batch_id);
        $fee=Fee::where('standard','=',$admission->standard)->get();
        $header = \View::make('layouts.pdfheader')->render();
        $footer = \View::make('layouts.pdffooter')->render();
        $pdf = PDF::loadView('pdf.receipt', compact('admission','installment','fee','batch'))
                  ->setOption('header-html',$header)
                  ->setOption('footer-html',$footer);
        //return view('pdf.receipt',compact('admission','installment','fee','batch'));
        return $pdf->download($admission->studentname.'.pdf');
    }

    public function emailreceipt(Admission $admission,$installment)
    {
        $storage_path = storage_path();
        $batch_id=Batch::where('batchname','=',$admission->admissionbatch)->value('id');
        $batch=Batch::find($batch_id);
        $fee=Fee::where('standard','=',$admission->standard)->get();
        $header = \View::make('layouts.pdfheader')->render();
        $footer = \View::make('layouts.pdffooter')->render();
        $pdf = PDF::loadView('pdf.receipt', compact('admission','installment','fee','batch'))
                  ->setOption('header-html',$header)
                  ->setOption('footer-html',$footer);

        $pdf->save($storage_path.'/receipts/'.$admission->studentname.'.pdf');
        
        Mail::send('email.feereceipt', ['title' => "", 'content' => ""], function ($message) use ($admission,$installment,$storage_path)
                {
                    $message->from('veaenglishacademy@gmail.com', 'VEA Team')
                            ->to($admission->email)
                            ->subject('Installment Receipt')
                            ->attach($storage_path.'/receipts/'.$admission->studentname.'.pdf', [
                            'as' => $admission->studentname.'.pdf', 
                            'mime' => 'application/pdf'
                ]);

                });
        File::delete($storage_path.'/receipts/'.$admission->studentname.'.pdf');
        session()->flash('message','Email send successfully!');
        return back();
    }



}
