@extends('layouts.app')
@section('breadcrumb')
<ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ url('/schoolmarks/'.$branch.'/create') }}">{{$branch}} - Batch List</a>
            </li>
            <li class="breadcrumb-item"><a href="{{ url('/schoolmarks/'.$branch.'/'.$batch->id.'/'.$standard.'/listmarks') }}">{{$standard}} - {{$batch->batchname}}</a></li>
            <li class="breadcrumb-item active">{{$marks->topic_name}}</li>
            <li class="breadcrumb-item active">List Student Marks</li>
</ol>
@endsection
@section('content')
<div class="row">
    <div class="col-lg-12">                        
        <div class="card">
            <table class="table table-hover table-outline mb-0 hidden-sm-down enquiry">
                                                <thead class="thead-default">
                                                <tr>
                                                    <th>Student Name</th>
                                                    <th>Date of Examination</th>
                                                    <th>Topic Name</th>
                                                    <th>Total Marks</th>
                                                    <th>Marks Obtained</th>
                                                    </tr>
                                            </thead>
                                            <tbody id="tasks-list">
                                             @foreach ($admission as $adm)
                                                <tr>
                                                    <td>
                                                        <div>{{$adm->studentname}}</div>
                                                    </td>
                                                    <td>
                                                        <div>{{$marks->date}}</div>
                                                    </td>
                                                    <td>
                                                        <div>{{$marks->topic_name}}</div>
                                                    </td>
                                                    <td>
                                                    <div>{{$marks->total_marks}}</div>
                                                    </td>
                                                    <td>
                                                        

                                                        @php
                                                            $found=0;
                                                            $displayed=0;
                                                        @endphp
                                                        @foreach($adm->schoolmarks as $ad)
                                                            @if($ad->pivot->schoolmark_id==$marks->id)
                                                                    <div><strong>{{$ad->pivot->marks_obtained}}</strong></div>
                                                                    @php
                                                                    $displayed=1;
                                                                    @endphp
                                                            @endif
                                                            @php
                                                                $dislayed=0;
                                                            @endphp
                                                        @endforeach
                                                        @if($found==0 && $displayed!=1)
                                                             <div><strong> - </strong></div>
                                                        @endif
                                                        
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
<!-- <script>
$(".admission").on('focus',function() {
       var marks_id=$('#marks_id').val();
       console.log(marks_id+"marks id");
       var url = $('#url').val();
       console.log(url+"url id");
       var admission_id= $(this).attr('id');
       console.log(admission_id+"admission id");

       var marks_obtained=$(this).val();
       console.log(marks_obtained+"marks Obtained");

       if(marks_obtained!="")
       {

       

       $.ajax
                ({
                    type: "GET",
                    url: url + '/ajax/addstudentmarks/' + admission_id + '/'+ marks_id + '/'+marks_obtained,
                    success: function (data) 
                    {
                        console.log(data);
                    },
                    statusCode: 
                    {
                        401: function()
                        { 
                            window.location.href =url+'/login';
                        }
                    },
                    error: function (data) 
                    {
                        console.log('Error:', data);
                    }
                });
    }

    });

</script> -->
@endsection