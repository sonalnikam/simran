<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Auth;
use Session;
use App\Admission;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    
    public function create()
    { 
        return view('export.create');
    }
    public function export(Request $request)
    {
        $this->validate($request,[
            'month'=>'required',
            'branch'=>'required',
            ]);
        $branch=$request->branch;
        $mon=$request->month;
        $keywords = preg_split('[-]',$mon);
        $year=$keywords[0];
        $nextyear=$year+1;
        $mon_number=$keywords[1];
        $months = array (1=>'Jan',2=>'Feb',3=>'Mar',4=>'Apr',5=>'May',6=>'Jun',7=>'Jul',8=>'Aug',9=>'Sep',10=>'Oct',11=>'Nov',12=>'Dec');
        $month=$months[(int)$mon_number];

        

       

        $admission_currentyear=Admission::join('fees','fees.id','=','admission.fee_id')
                              ->where('branch','=',$branch)
                              ->where('admission.fromyear','=',$year)
                              ->where(function($query) use($mon)
                                      {
                                            $query->where('installment_date1','like',$mon.'%')
                                                  ->orWhere('installment_date2','like',$mon.'%')
                                                  ->orWhere('installment_date3','like',$mon.'%')
                                                  ->orWhere('installment_date0','like',$mon.'%');
                                      })
                              ->get();

        $admission_nextyear=Admission::join('fees','fees.id','=','admission.fee_id')
                              ->where('branch','=',$branch)
                              ->where('admission.fromyear','=',$nextyear)
                              ->where(function($query) use($mon)
                                      {
                                            $query->where('installment_date1','like',$mon.'%')
                                                  ->orWhere('installment_date2','like',$mon.'%')
                                                  ->orWhere('installment_date3','like',$mon.'%')
                                                  ->orWhere('installment_date0','like',$mon.'%');
                                      })
                              ->get();
        

        
        $mytime =Carbon::now();
        $datetm=$mytime->toDateTimeString();
        $filename='export_'.  $datetm;
        
        $month_year=$month.' '.$year;
        Excel::create($filename, function($excel) use($branch,$month_year,&$admission_currentyear,&$admission_nextyear,$mon)
        {   
            $excel->sheet('CASH-CURRENT YEAR',function($sheet2) use($branch,$month_year,&$admission_currentyear,$mon)
            {
               /*$sheet1->mergeCells('A1:J1');
               $sheet1->setCellValueByColumnAndRow(2, 1, "Platforms");*/
               $sheet2->cell('A1:J1', function($cell) {
               $cell->setFontSize(40);
               
               });

               $sheet2->mergeCells('A1:K1');
               $sheet2->SetCellValue('A1','                   VIKRAM\'S ENGLISH ACADEMY');

               $sheet2->cell('A2:G2', function($cell) {
               $cell->setFontSize(20);
               
               });

               $sheet2->mergeCells('A2:B2');
               $sheet2->SetCellValue('A2','BRANCH :');
               $sheet2->mergeCells('C2:D2');
               $sheet2->SetCellValue('C2',$branch);
               $sheet2->mergeCells('E2:F2');
               $sheet2->SetCellValue('E2','MONTH :');
               $sheet2->mergeCells('G2:H2');
               $sheet2->SetCellValue('G2',$month_year);

               $sheet2->cell('A3:I3', function($cell) {
               $cell->setFontSize(15);
               });
               
               $sheet2->SetCellValue('A3','SR NO.');
               $sheet2->SetCellValue('B3','RECEIPT NUMBER');
               $sheet2->SetCellValue('C3','DATE OF RECEIPT');
               $sheet2->SetCellValue('D3','NAME OF STUDENTS');
               $sheet2->SetCellValue('E3','TUITION FEES RECEIVED TAXABLE AMT');
               $sheet2->SetCellValue('F3','GST 18%');
               $sheet2->SetCellValue('G3','TOTAL AMT');
               $sheet2->SetCellValue('H3','MODE OF PAYMENT');
               $sheet2->SetCellValue('I3','CHEQUE NO. / TRANSACTION ID');

               $number=4;
               $srno=1;
               foreach($admission_currentyear as $adm)
               {
                    if($adm->installment_mode1=='CASH')
                    {
                        $value1=substr($adm->installment_date1,0,7);
                    if($value1==$mon)
                    {   
                        $sheet2->SetCellValue('A'.$number,$srno);
                        $sheet2->SetCellValue('B'.$number,$adm->receiptid1);
                        $sheet2->SetCellValue('C'.$number,$adm->installment_date1);
                        $sheet2->SetCellValue('D'.$number,$adm->studentname);
                        $sheet2->SetCellValue('E'.$number,$adm["1stinstallment"]);
                        $sheet2->SetCellValue('F'.$number,$adm["1gst"]);
                        $sheet2->SetCellValue('G'.$number,$adm["1total"]);
                        $sheet2->SetCellValue('H'.$number,$adm->installment_mode1);
                        $number++;
                        $srno++;
                    }
                        
                    }
                    if($adm->installment_mode2=='CASH')
                    {
                        $value1=substr($adm->installment_date2,0,7);
                    if($value1==$mon)
                    {   
                        $sheet2->SetCellValue('A'.$number,$srno);
                        $sheet2->SetCellValue('B'.$number,$adm->receiptid2);
                        $sheet2->SetCellValue('C'.$number,$adm->installment_date2);
                        $sheet2->SetCellValue('D'.$number,$adm->studentname);
                        $sheet2->SetCellValue('E'.$number,$adm["2ndinstallment"]);
                        $sheet2->SetCellValue('F'.$number,$adm["2gst"]);
                        $sheet2->SetCellValue('G'.$number,$adm["2total"]);
                        $sheet2->SetCellValue('H'.$number,$adm->installment_mode2);
                        $number++;
                        $srno++;
                    }
                    }
                    if($adm->installment_mode3=='CASH')
                    {
                        $value1=substr($adm->installment_date3,0,7);
                    if($value1==$mon)
                    {   
                        $sheet2->SetCellValue('A'.$number,$srno);
                        $sheet2->SetCellValue('B'.$number,$adm->receiptid3);
                        $sheet2->SetCellValue('C'.$number,$adm->installment_date3);
                        $sheet2->SetCellValue('D'.$number,$adm->studentname);
                        $sheet2->SetCellValue('E'.$number,$adm["3rdinstallment"]);
                        $sheet2->SetCellValue('F'.$number,$adm["3gst"]);
                        $sheet2->SetCellValue('G'.$number,$adm["3total"]);
                        $sheet2->SetCellValue('H'.$number,$adm->installment_mode3);
                        $number++;
                        $srno++;
                    }    
                    }
                    if($adm->installment_mode0=='CASH')
                    {
                        $value1=substr($adm->installment_date0,0,7);
                    if($value1==$mon)
                    {   
                        $sheet2->SetCellValue('A'.$number,$srno);
                        $sheet2->SetCellValue('B'.$number,$adm->receiptid0);
                        $sheet2->SetCellValue('C'.$number,$adm->installment_date0);
                        $sheet2->SetCellValue('D'.$number,$adm->studentname);
                        $sheet2->SetCellValue('E'.$number,$adm["totalinstallment"]);
                        $sheet2->SetCellValue('F'.$number,$adm["totalgst"]);
                        $sheet2->SetCellValue('G'.$number,$adm["totalfee"]);
                        $sheet2->SetCellValue('H'.$number,$adm->installment_mode0);
                        $number++;
                        $srno++;
                    }
                    }
                    
                    
               }
               

            });

            $excel->sheet('CASH-NEXT YEAR',function($sheet2) use($branch,$month_year,&$admission_nextyear,$mon)
            {
               /*$sheet1->mergeCells('A1:J1');
               $sheet1->setCellValueByColumnAndRow(2, 1, "Platforms");*/
               $sheet2->cell('A1:J1', function($cell) {
               $cell->setFontSize(40);
               
               });

               $sheet2->mergeCells('A1:K1');
               $sheet2->SetCellValue('A1','                   VIKRAM\'S ENGLISH ACADEMY');

               $sheet2->cell('A2:G2', function($cell) {
               $cell->setFontSize(20);
               
               });

               $sheet2->mergeCells('A2:B2');
               $sheet2->SetCellValue('A2','BRANCH :');
               $sheet2->mergeCells('C2:D2');
               $sheet2->SetCellValue('C2',$branch);
               $sheet2->mergeCells('E2:F2');
               $sheet2->SetCellValue('E2','MONTH :');
               $sheet2->mergeCells('G2:H2');
               $sheet2->SetCellValue('G2',$month_year);

               $sheet2->cell('A3:I3', function($cell) {
               $cell->setFontSize(15);
               });
               
               $sheet2->SetCellValue('A3','SR NO.');
               $sheet2->SetCellValue('B3','RECEIPT NUMBER');
               $sheet2->SetCellValue('C3','DATE OF RECEIPT');
               $sheet2->SetCellValue('D3','NAME OF STUDENTS');
               $sheet2->SetCellValue('E3','TUITION FEES RECEIVED TAXABLE AMT');
               $sheet2->SetCellValue('F3','GST 18%');
               $sheet2->SetCellValue('G3','TOTAL AMT');
               $sheet2->SetCellValue('H3','MODE OF PAYMENT');
               $sheet2->SetCellValue('I3','CHEQUE NO. / TRANSACTION ID');

               $number=4;
               $srno=1;
               foreach($admission_nextyear as $adm)
               {
                    if($adm->installment_mode1=='CASH')
                    {
                        $value1=substr($adm->installment_date1,0,7);
                    if($value1==$mon)
                    {   
                        $sheet2->SetCellValue('A'.$number,$srno);
                        $sheet2->SetCellValue('B'.$number,$adm->receiptid1);
                        $sheet2->SetCellValue('C'.$number,$adm->installment_date1);
                        $sheet2->SetCellValue('D'.$number,$adm->studentname);
                        $sheet2->SetCellValue('E'.$number,$adm["1stinstallment"]);
                        $sheet2->SetCellValue('F'.$number,$adm["1gst"]);
                        $sheet2->SetCellValue('G'.$number,$adm["1total"]);
                        $sheet2->SetCellValue('H'.$number,$adm->installment_mode1);
                        $number++;
                        $srno++;
                    }
                        
                    }
                    if($adm->installment_mode2=='CASH')
                    {
                        $value1=substr($adm->installment_date2,0,7);
                    if($value1==$mon)
                    {   
                        $sheet2->SetCellValue('A'.$number,$srno);
                        $sheet2->SetCellValue('B'.$number,$adm->receiptid2);
                        $sheet2->SetCellValue('C'.$number,$adm->installment_date2);
                        $sheet2->SetCellValue('D'.$number,$adm->studentname);
                        $sheet2->SetCellValue('E'.$number,$adm["2ndinstallment"]);
                        $sheet2->SetCellValue('F'.$number,$adm["2gst"]);
                        $sheet2->SetCellValue('G'.$number,$adm["2total"]);
                        $sheet2->SetCellValue('H'.$number,$adm->installment_mode2);
                        $number++;
                        $srno++;
                    }
                    }
                    if($adm->installment_mode3=='CASH')
                    {
                        $value1=substr($adm->installment_date3,0,7);
                    if($value1==$mon)
                    {   
                        $sheet2->SetCellValue('A'.$number,$srno);
                        $sheet2->SetCellValue('B'.$number,$adm->receiptid3);
                        $sheet2->SetCellValue('C'.$number,$adm->installment_date3);
                        $sheet2->SetCellValue('D'.$number,$adm->studentname);
                        $sheet2->SetCellValue('E'.$number,$adm["3rdinstallment"]);
                        $sheet2->SetCellValue('F'.$number,$adm["3gst"]);
                        $sheet2->SetCellValue('G'.$number,$adm["3total"]);
                        $sheet2->SetCellValue('H'.$number,$adm->installment_mode3);
                        $number++;
                        $srno++;
                    }    
                    }
                    if($adm->installment_mode0=='CASH')
                    {
                        $value1=substr($adm->installment_date0,0,7);
                    if($value1==$mon)
                    {   
                        $sheet2->SetCellValue('A'.$number,$srno);
                        $sheet2->SetCellValue('B'.$number,$adm->receiptid0);
                        $sheet2->SetCellValue('C'.$number,$adm->installment_date0);
                        $sheet2->SetCellValue('D'.$number,$adm->studentname);
                        $sheet2->SetCellValue('E'.$number,$adm["totalinstallment"]);
                        $sheet2->SetCellValue('F'.$number,$adm["totalgst"]);
                        $sheet2->SetCellValue('G'.$number,$adm["totalfee"]);
                        $sheet2->SetCellValue('H'.$number,$adm->installment_mode0);
                        $number++;
                        $srno++;
                    }
                    }
                    
                    
               }
               

            });

            $excel->sheet('CHEQUE-CURRENT YEAR',function($sheet2) use($branch,$month_year,&$admission_currentyear,$mon)
            {
               /*$sheet1->mergeCells('A1:J1');
               $sheet1->setCellValueByColumnAndRow(2, 1, "Platforms");*/
               $sheet2->cell('A1:J1', function($cell) {
               $cell->setFontSize(40);
               
               });

               $sheet2->mergeCells('A1:K1');
               $sheet2->SetCellValue('A1','                   VIKRAM\'S ENGLISH ACADEMY');

               $sheet2->cell('A2:G2', function($cell) {
               $cell->setFontSize(20);
               
               });

               $sheet2->mergeCells('A2:B2');
               $sheet2->SetCellValue('A2','BRANCH :');
               $sheet2->mergeCells('C2:D2');
               $sheet2->SetCellValue('C2',$branch);
               $sheet2->mergeCells('E2:F2');
               $sheet2->SetCellValue('E2','MONTH :');
               $sheet2->mergeCells('G2:H2');
               $sheet2->SetCellValue('G2',$month_year);

               $sheet2->cell('A3:I3', function($cell) {
               $cell->setFontSize(15);
               });
               
               $sheet2->SetCellValue('A3','SR NO.');
               $sheet2->SetCellValue('B3','RECEIPT NUMBER');
               $sheet2->SetCellValue('C3','DATE OF RECEIPT');
               $sheet2->SetCellValue('D3','NAME OF STUDENTS');
               $sheet2->SetCellValue('E3','TUITION FEES RECEIVED TAXABLE AMT');
               $sheet2->SetCellValue('F3','GST 18%');
               $sheet2->SetCellValue('G3','TOTAL AMT');
               $sheet2->SetCellValue('H3','MODE OF PAYMENT');
               $sheet2->SetCellValue('I3','CHEQUE NO. / TRANSACTION ID');

               $number=4;
               $srno=1;
               foreach($admission_currentyear as $adm)
               {
                    if($adm->installment_mode1=='CHEQUE' or $adm->installment_mode1=='ONLINEPAYMENT')
                    {
                        $value1=substr($adm->installment_date1,0,7);
                    if($value1==$mon)
                    {   
                        $sheet2->SetCellValue('A'.$number,$srno);
                        $sheet2->SetCellValue('B'.$number,$adm->receiptid1);
                        $sheet2->SetCellValue('C'.$number,$adm->installment_date1);
                        $sheet2->SetCellValue('D'.$number,$adm->studentname);
                        $sheet2->SetCellValue('E'.$number,$adm["1stinstallment"]);
                        $sheet2->SetCellValue('F'.$number,$adm["1gst"]);
                        $sheet2->SetCellValue('G'.$number,$adm["1total"]);
                        $sheet2->SetCellValue('H'.$number,$adm->installment_mode1);
                        if($adm->installment_mode1=='CHEQUE')
                        {
                            $sheet2->SetCellValue('I'.$number,$adm->chequeno1);
                        }
                        if($adm->installment_mode1=='ONLINEPAYMENT')
                        {
                            $sheet2->SetCellValue('I'.$number,$adm->transactionid1);
                        }
                        $number++;
                        $srno++;
                    }    
                    }

                    if($adm->installment_mode2=='CHEQUE' or $adm->installment_mode2=='ONLINEPAYMENT')
                    {
                    
                    $value1=substr($adm->installment_date2,0,7);
                    if($value1==$mon)
                    {   
                        $sheet2->SetCellValue('A'.$number,$srno);
                        $sheet2->SetCellValue('B'.$number,$adm->receiptid2);
                        $sheet2->SetCellValue('C'.$number,$adm->installment_date2);
                        $sheet2->SetCellValue('D'.$number,$adm->studentname);
                        $sheet2->SetCellValue('E'.$number,$adm["2ndinstallment"]);
                        $sheet2->SetCellValue('F'.$number,$adm["2gst"]);
                        $sheet2->SetCellValue('G'.$number,$adm["2total"]);
                        $sheet2->SetCellValue('H'.$number,$adm->installment_mode2);
                        if($adm->installment_mode1=='CHEQUE')
                        {
                            $sheet2->SetCellValue('I'.$number,$adm->chequeno2);
                        }
                        if($adm->installment_mode1=='ONLINEPAYMENT')
                        {
                            $sheet2->SetCellValue('I'.$number,$adm->transactionid2);
                        }
                        $number++;
                        $srno++;
                    }
                    }
                    if($adm->installment_mode3=='CHEQUE' or $adm->installment_mode3=='ONLINEPAYMENT')
                    { 
                    $value1=substr($adm->installment_date3,0,7);
                    if($value1==$mon)
                    {   
                        $sheet2->SetCellValue('A'.$number,$srno);
                        $sheet2->SetCellValue('B'.$number,$adm->receiptid3);
                        $sheet2->SetCellValue('C'.$number,$adm->installment_date3);
                        $sheet2->SetCellValue('D'.$number,$adm->studentname);
                        $sheet2->SetCellValue('E'.$number,$adm["3rdinstallment"]);
                        $sheet2->SetCellValue('F'.$number,$adm["3gst"]);
                        $sheet2->SetCellValue('G'.$number,$adm["3total"]);
                        $sheet2->SetCellValue('H'.$number,$adm->installment_mode3);
                        if($adm->installment_mode1=='CHEQUE')
                        {
                            $sheet2->SetCellValue('I'.$number,$adm->chequeno3);
                        }
                        if($adm->installment_mode1=='ONLINEPAYMENT')
                        {
                            $sheet2->SetCellValue('I'.$number,$adm->transactionid3);
                        }
                        $number++;
                        $srno++;
                    }
                    }
                    if($adm->installment_mode0=='CHEQUE' or $adm->installment_mode0=='ONLINEPAYMENT')
                    {
                    $value1=substr($adm->installment_date0,0,7);
                    if($value1==$mon)
                    {   
                        $sheet2->SetCellValue('A'.$number,$srno);
                        $sheet2->SetCellValue('B'.$number,$adm->receiptid0);
                        $sheet2->SetCellValue('C'.$number,$adm->installment_date0);
                        $sheet2->SetCellValue('D'.$number,$adm->studentname);
                        $sheet2->SetCellValue('E'.$number,$adm["totalinstallment"]);
                        $sheet2->SetCellValue('F'.$number,$adm["totalgst"]);
                        $sheet2->SetCellValue('G'.$number,$adm["totalfee"]);
                        $sheet2->SetCellValue('H'.$number,$adm->installment_mode0);
                        if($adm->installment_mode1=='CHEQUE')
                        {
                            $sheet2->SetCellValue('I'.$number,$adm->chequeno0);
                        }
                        if($adm->installment_mode1=='ONLINEPAYMENT')
                        {
                            $sheet2->SetCellValue('I'.$number,$adm->transactionid0);
                        }
                        $number++;
                        $srno++;
                    }
                    }
                    
               }
               

            });

            $excel->sheet('CHEQUE-NEXT YEAR',function($sheet2) use($branch,$month_year,&$admission_nextyear,$mon)
            {
               /*$sheet1->mergeCells('A1:J1');
               $sheet1->setCellValueByColumnAndRow(2, 1, "Platforms");*/
               $sheet2->cell('A1:J1', function($cell) {
               $cell->setFontSize(40);
               
               });

               $sheet2->mergeCells('A1:K1');
               $sheet2->SetCellValue('A1','                   VIKRAM\'S ENGLISH ACADEMY');

               $sheet2->cell('A2:G2', function($cell) {
               $cell->setFontSize(20);
               
               });

               $sheet2->mergeCells('A2:B2');
               $sheet2->SetCellValue('A2','BRANCH :');
               $sheet2->mergeCells('C2:D2');
               $sheet2->SetCellValue('C2',$branch);
               $sheet2->mergeCells('E2:F2');
               $sheet2->SetCellValue('E2','MONTH :');
               $sheet2->mergeCells('G2:H2');
               $sheet2->SetCellValue('G2',$month_year);

               $sheet2->cell('A3:I3', function($cell) {
               $cell->setFontSize(15);
               });
               
               $sheet2->SetCellValue('A3','SR NO.');
               $sheet2->SetCellValue('B3','RECEIPT NUMBER');
               $sheet2->SetCellValue('C3','DATE OF RECEIPT');
               $sheet2->SetCellValue('D3','NAME OF STUDENTS');
               $sheet2->SetCellValue('E3','TUITION FEES RECEIVED TAXABLE AMT');
               $sheet2->SetCellValue('F3','GST 18%');
               $sheet2->SetCellValue('G3','TOTAL AMT');
               $sheet2->SetCellValue('H3','MODE OF PAYMENT');
               $sheet2->SetCellValue('I3','CHEQUE NO. / TRANSACTION ID');

               $number=4;
               $srno=1;
               foreach($admission_nextyear as $adm)
               {
                    if($adm->installment_mode1=='CHEQUE' or $adm->installment_mode1=='ONLINEPAYMENT')
                    {
                        $value1=substr($adm->installment_date1,0,7);
                    if($value1==$mon)
                    {   
                        $sheet2->SetCellValue('A'.$number,$srno);
                        $sheet2->SetCellValue('B'.$number,$adm->receiptid1);
                        $sheet2->SetCellValue('C'.$number,$adm->installment_date1);
                        $sheet2->SetCellValue('D'.$number,$adm->studentname);
                        $sheet2->SetCellValue('E'.$number,$adm["1stinstallment"]);
                        $sheet2->SetCellValue('F'.$number,$adm["1gst"]);
                        $sheet2->SetCellValue('G'.$number,$adm["1total"]);
                        $sheet2->SetCellValue('H'.$number,$adm->installment_mode1);
                        if($adm->installment_mode1=='CHEQUE')
                        {
                            $sheet2->SetCellValue('I'.$number,$adm->chequeno1);
                        }
                        if($adm->installment_mode1=='ONLINEPAYMENT')
                        {
                            $sheet2->SetCellValue('I'.$number,$adm->transactionid1);
                        }
                        $number++;
                        $srno++;
                    }    
                    }

                    if($adm->installment_mode2=='CHEQUE' or $adm->installment_mode2=='ONLINEPAYMENT')
                    {
                    
                    $value1=substr($adm->installment_date2,0,7);
                    if($value1==$mon)
                    {   
                        $sheet2->SetCellValue('A'.$number,$srno);
                        $sheet2->SetCellValue('B'.$number,$adm->receiptid2);
                        $sheet2->SetCellValue('C'.$number,$adm->installment_date2);
                        $sheet2->SetCellValue('D'.$number,$adm->studentname);
                        $sheet2->SetCellValue('E'.$number,$adm["2ndinstallment"]);
                        $sheet2->SetCellValue('F'.$number,$adm["2gst"]);
                        $sheet2->SetCellValue('G'.$number,$adm["2total"]);
                        $sheet2->SetCellValue('H'.$number,$adm->installment_mode2);
                        if($adm->installment_mode1=='CHEQUE')
                        {
                            $sheet2->SetCellValue('I'.$number,$adm->chequeno2);
                        }
                        if($adm->installment_mode1=='ONLINEPAYMENT')
                        {
                            $sheet2->SetCellValue('I'.$number,$adm->transactionid2);
                        }
                        $number++;
                        $srno++;
                    }
                    }
                    if($adm->installment_mode3=='CHEQUE' or $adm->installment_mode3=='ONLINEPAYMENT')
                    { 
                    $value1=substr($adm->installment_date3,0,7);
                    if($value1==$mon)
                    {   
                        $sheet2->SetCellValue('A'.$number,$srno);
                        $sheet2->SetCellValue('B'.$number,$adm->receiptid3);
                        $sheet2->SetCellValue('C'.$number,$adm->installment_date3);
                        $sheet2->SetCellValue('D'.$number,$adm->studentname);
                        $sheet2->SetCellValue('E'.$number,$adm["3rdinstallment"]);
                        $sheet2->SetCellValue('F'.$number,$adm["3gst"]);
                        $sheet2->SetCellValue('G'.$number,$adm["3total"]);
                        $sheet2->SetCellValue('H'.$number,$adm->installment_mode3);
                        if($adm->installment_mode1=='CHEQUE')
                        {
                            $sheet2->SetCellValue('I'.$number,$adm->chequeno3);
                        }
                        if($adm->installment_mode1=='ONLINEPAYMENT')
                        {
                            $sheet2->SetCellValue('I'.$number,$adm->transactionid3);
                        }
                        $number++;
                        $srno++;
                    }
                    }
                    if($adm->installment_mode0=='CHEQUE' or $adm->installment_mode0=='ONLINEPAYMENT')
                    {
                    $value1=substr($adm->installment_date0,0,7);
                    if($value1==$mon)
                    {   
                        $sheet2->SetCellValue('A'.$number,$srno);
                        $sheet2->SetCellValue('B'.$number,$adm->receiptid0);
                        $sheet2->SetCellValue('C'.$number,$adm->installment_date0);
                        $sheet2->SetCellValue('D'.$number,$adm->studentname);
                        $sheet2->SetCellValue('E'.$number,$adm["totalinstallment"]);
                        $sheet2->SetCellValue('F'.$number,$adm["totalgst"]);
                        $sheet2->SetCellValue('G'.$number,$adm["totalfee"]);
                        $sheet2->SetCellValue('H'.$number,$adm->installment_mode0);
                        if($adm->installment_mode1=='CHEQUE')
                        {
                            $sheet2->SetCellValue('I'.$number,$adm->chequeno0);
                        }
                        if($adm->installment_mode1=='ONLINEPAYMENT')
                        {
                            $sheet2->SetCellValue('I'.$number,$adm->transactionid0);
                        }
                        $number++;
                        $srno++;
                    }
                    }
                    
               }
               

            });
            ob_end_clean();
        })->download("xlsx");

        return back();

    }
}
