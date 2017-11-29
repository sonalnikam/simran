@extends('layouts.app')
@section('breadcrumb')
<ol class="breadcrumb">
            
            <li class="breadcrumb-item active">Home</li>
        </ol>
@endsection
@section('content')
                <div class="card-group">
                    <div class="card">
                        <div class="card-block">
                            <div class="h1 text-muted text-xs-right mb-2">
                                <i class="icon-call-in"></i>
                            </div>
                            <div class="h4 mb-0">{{$enquirycount}}</div>
                            <small class="text-muted text-uppercase font-weight-bold">Enqiry</small>
                            <progress class="progress progress-xs progress-info mt-1 mb-0" value="25" max="100">25%</progress>

                        </div>
                    </div>
                    <div class="card">
                        <div class="card-block">
                            <div class="h1 text-muted text-xs-right mb-2">
                                <i class="icon-compass"></i>
                            </div>
                            <div class="h4 mb-0">{{$orientationcount}}</div>
                            <small class="text-muted text-uppercase font-weight-bold">Orientation</small>
                            <progress class="progress progress-xs progress-primary mt-1 mb-0" value="25" max="100">25%</progress>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-block">
                            <div class="h1 text-muted text-xs-right mb-2">
                                <i class="icon-home"></i>
                            </div>
                            <div class="h4 mb-0">{{$admissioncount}}</div>
                            <small class="text-muted text-uppercase font-weight-bold">Admissions</small>
                            <progress class="progress progress-xs progress-warning mt-1 mb-0" value="25" max="100">25%</progress>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-block">
                            <div class="h1 text-muted text-xs-right mb-2">
                                <i class="icon-people"></i>
                            </div>
                            <div class="h4 mb-0">{{$usercount}}</div>
                            <small class="text-muted text-uppercase font-weight-bold">Users</small>
                            <progress class="progress progress-xs progress-danger mt-1 mb-0" value="25" max="100">25%</progress>
                        </div>
                    </div>
                </div>
                <div class="card">
                            <table class="table table-hover table-outline mb-0 hidden-sm-down">
                                                <thead class="thead-default">
                                                <tr>
                                                    <th class="text-xs-center"><i class="icon-people"></i>
                                                    </th>
                                                    <th>Class</th>
                                                    <th>Date</th>
                                                    <th>Name</th>
                                                    <th>School</th>
                                                    <th>Father's No.</th>
                                                    <th>Mother's No.</th>
                                                    <th>Landline</th>
                                                    <th>Activity</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                             @foreach ($enquiry as $enq)
                                                <tr>
                                                    <td class="text-xs-center">
                                                        <div class="avatar">
                                                            <img src="{{ asset('img/logo-placeholder.png') }}" class="img-avatar" alt="Enquiry Logo">
                                                            
                                                        </div>
                                                    </td>
                                                    <td>
                                                        @foreach(App\Http\AcatUtilities\Standard::all() as $value => $code)
                                                            @if($enq->standard == $code)
                                                                <div>{{$value}}</div>
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                    <td>
                                                        <div>{{$enq->date}}</div>
                                                    </td>
                                                    <td>
                                                        <div>
                                                            <a href="{{ url('/enquiry/'.$enq->id.'/edit') }}">{{$enq->name}}</a>
                                                        </div>
                                                    </td>
                                                    <td>
                                                    @if($enq->school=='OTHERS')
                                                        <div>{{$enq->otherschool}}</div>
                                                    @else
                                                        @foreach(App\Http\AcatUtilities\Schools::all() as $value => $code)
                                                            @if($enq->school == $code)
                                                                <div>{{$value}}</div>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                    </td>
                                                    <td>
                                                        @if(empty($enq->fatherno))
                                                        <div><center>....</center></div>
                                                        @else
                                                        <div>{{$enq->fatherno}}</div>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if(empty($enq->motherno))
                                                        <div><center>....</center></div>
                                                        @else
                                                        <div>{{$enq->motherno}}</div>
                                                        @endif
                                                        
                                                    </td>
                                                    <td>
                                                        @if(empty($enq->landline))
                                                        <div><center>....</center></div>
                                                        @else
                                                        <div>{{$enq->landline}}</div>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <strong>{{$enq->updated_at->diffForHumans()}}</strong>
                                                    </td>
                                                    <td>
                                                   <div class="float-xs-right"> 
                                                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="window.location.href='{{ url('/enquiry/'.$enq->id.'/edit') }}'">Edit</button>
                                                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="javascript:confirmDelete('{{ url('/enquiry/'.$enq->id.'/delete') }}')">Delete</button>
                                                    </div>
                                                </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                </table>
                        </div>
                        <div class="row">
                                            <div  class="col-lg-12">
                                                <div class="card">
                                                            <table class="table table-hover table-outline mb-0 hidden-sm-down enquiry">
                                                                        <thead class="thead-default">
                                                                        <tr>
                                                                            <th>Class</th>
                                                                            <th>Date</th>
                                                                            <th>Name</th>
                                                                            <th>School</th>
                                                                            <th>F No.</th>
                                                                            <th>M No.</th>
                                                                            <th>Landline</th>
                                                                            <th>Orientation 1</th>
                                                                            <th>Orientation 2</th>
                                                                            <th>Orientation 3</th>
                                                                            <th>Activity</th>
                                                                            </tr>
                                                                    </thead>
                                                                    <tbody id="tasks-list">
                                                                     @foreach ($orientation as $enq)
                                                                        <tr>
                                                                            <td>
                                                                                @foreach(App\Http\AcatUtilities\Standard::all() as $value => $code)
                                                                                    @if($enq->standard == $code)
                                                                                        <div>{{$value}}</div>
                                                                                    @endif
                                                                                @endforeach
                                                                            </td>
                                                                            <td>
                                                                                <div>{{$enq->date}}</div>
                                                                            </td>
                                                                            <td>
                                                                                <div>
                                                                                    <a href="{{ url('/enquiry/'.$enq->id.'/edit') }}">{{$enq->name}}</a>
                                                                                </div>
                                                                            </td>
                                                                            <td>
                                                                            @if($enq->school=='OTHERS')
                                                                                <div>{{$enq->otherschool}}</div>
                                                                            @else
                                                                                @foreach(App\Http\AcatUtilities\Schools::all() as $value => $code)
                                                                                    @if($enq->school == $code)
                                                                                        <div>{{$value}}</div>
                                                                                    @endif
                                                                                @endforeach
                                                                            @endif
                                                                            </td>
                                                                            <td>
                                                                                @if(empty($enq->fatherno))
                                                                                <div><center>....</center></div>
                                                                                @else
                                                                                <div>{{$enq->fatherno}}</div>
                                                                                @endif
                                                                            </td>
                                                                            <td>
                                                                                @if(empty($enq->motherno))
                                                                                <div><center>....</center></div>
                                                                                @else
                                                                                <div>{{$enq->motherno}}</div>
                                                                                @endif
                                                                                
                                                                            </td>
                                                                            <td>
                                                                                @if(empty($enq->landline))
                                                                                <div><center>....</center></div>
                                                                                @else
                                                                                <div>{{$enq->landline}}</div>
                                                                                @endif
                                                                            </td>
                                                                            <td>
                                                                               <div><strong>Date:</strong> {{$enq->date1}}</div>
                                                                               <div><strong>Response:</strong> {{$enq->response1}}</div>
                                                                               <div><strong>Attendance:</strong> {{$enq->attendance1}}</div>
                                                                            </td>
                                                                            <td>
                                                                               <div><strong>Date:</strong> {{$enq->date2}}</div>
                                                                               <div><strong>Response:</strong> {{$enq->response2}}</div>
                                                                               <div><strong>Attendance:</strong> {{$enq->attendance2}}</div>
                                                                            </td>
                                                                            <td>
                                                                               <div><strong>Date:</strong> {{$enq->date3}}</div>
                                                                               <div><strong>Response:</strong> {{$enq->response3}}</div>
                                                                               <div><strong>Attendance:</strong> {{$enq->attendance3}}</div>
                                                                            </td>
                                                                            <td>
                                                                                <strong>{{$enq->updated_at->diffForHumans()}}</strong>
                                                                            </td>

                                                                        </tr>
                                                                    @endforeach
                                                                    </tbody>
                                                        </table>
                                        
                                        </div>
                                        </div>
                                </div>
                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="card">
                                                                    <table class="table table-hover table-outline mb-0 hidden-sm-down enquiry">
                                                                                <thead class="thead-default">
                                                                                <tr>
                                                                                    <th>Branch</th>
                                                                                    <th>Date</th>
                                                                                    <th>Name</th>
                                                                                    <th>School</th>
                                                                                    <th>F No.</th>
                                                                                    <th>M No.</th>
                                                                                    <th>Landline</th>
                                                                                    <th>Standard</th>
                                                                                    <th>Adm for Batch</th>
                                                                                    <th>Activity</th>
                                                                                    <th>Action</th>
                                                                                    </tr>
                                                                            </thead>
                                                                            <tbody id="tasks-list">
                                                                             @foreach ($admission as $adm)
                                                                                <tr>
                                                                                    <td>
                                                                                        @foreach(App\Http\AcatUtilities\Branch::all() as $value => $code)
                                                                                            @if($adm->branch == $code)
                                                                                                <div>{{$value}}</div>
                                                                                            @endif
                                                                                        @endforeach
                                                                                    </td>
                                                                                    
                                                                                    <td>
                                                                                        <div>{{$adm->date}}</div>
                                                                                    </td>
                                                                                    <td>
                                                                                        <div>
                                                                                            <a href="{{ url('/admission/'.$adm->id.'/list') }}">{{$adm->studentname }}</a>
                                                                                        </div>
                                                                                    </td>
                                                                                    <td>
                                                                                    @if($adm->school=='OTHERS')
                                                                                        <div>{{$adm->otherschool}}</div>
                                                                                    @else
                                                                                        @foreach(App\Http\AcatUtilities\Schools::all() as $value => $code)
                                                                                            @if($adm->school == $code)
                                                                                                <div>{{$value}}</div>
                                                                                            @endif
                                                                                        @endforeach
                                                                                    @endif
                                                                                    </td>
                                                                                    @php
                                                                                    $value=1;
                                                                                    $text=$adm->whatsappon;
                                                                                    @endphp
                                                                                    @if($text=='MCELL')
                                                                                    @php
                                                                                    $value=2;
                                                                                    @endphp
                                                                                    @elseif($text=='FCELL')
                                                                                    @php
                                                                                    $value=3;
                                                                                    @endphp
                                                                                    @endif
                                                                                    <td>
                                                                                        @if(empty($adm->fatherno))
                                                                                        <div><center>....</center></div>
                                                                                        @else
                                                                                        <div>{{$adm->fatherno}}@if($value==3)
                                                                                       <div>(Whatsapp)</div>
                                                                                        @endif
                                                                                        </div>
                                                                                        @endif
                                                                                    </td>
                                                                                    <td>
                                                                                        @if(empty($adm->motherno))
                                                                                        <div><center>....</center></div>
                                                                                        @else
                                                                                        <div>{{$adm->motherno}}
                                                                                        @if($value==2)
                                                                                        <div style="color:red;">(Whatsapp)</div>
                                                                                        @endif</div>
                                                                                        @endif
                                                                                        
                                                                                    </td>
                                                                                    <td>
                                                                                        @if(empty($adm->landline))
                                                                                        <div><center>....</center></div>
                                                                                        @else
                                                                                        <div>{{$adm->landline}}</div>
                                                                                        @endif
                                                                                    </td>
                                                                                    <td>
                                                                                        @foreach(App\Http\AcatUtilities\Standard::all() as $value => $code)
                                                                                            @if($adm->standard == $code)
                                                                                                <div>{{$value}}</div>
                                                                                            @endif
                                                                                        @endforeach
                                                                                    </td>
                                                                                    <td>
                                                                                        <div>{{$adm->admissionbatch}}</div>
                                                                                    </td>
                                                                                    <td>
                                                                                        <strong>{{$adm->updated_at->diffForHumans()}}</strong>
                                                                                    </td>
                                                                                    <td>
                                                                                   <div class="float-xs-right"> 
                                                                                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="window.location.href='{{ url('/admission/'.$adm->id.'/edit') }}'">Edit</button>
                                                                                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="javascript:confirmDelete('{{ url('/admission/'.$adm->id.'/delete') }}')">Delete</button>
                                                                                    </div>
                                                                                </td>
                                                                                   
                                                                                </tr>
                                                                            @endforeach
                                                                                
                                                                            </tbody>
                                                                </table>
                                                
                                                </div>
                                                </div>
                                        </div>
@endsection
@section('javascriptfunctions')
<script>
function confirmDelete(delUrl) {
  if (confirm("Are you sure you want to delete")) {
   document.location = delUrl;
  }
}
</script>
@endsection