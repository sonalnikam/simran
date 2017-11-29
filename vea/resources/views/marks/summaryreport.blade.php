@extends('layouts.app')
@section('breadcrumb')
<ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ url('/marks/'.$branch.'/create') }}">{{$branch}} - Batch List</a>
            </li>
            <li class="breadcrumb-item"><a href="{{ url('/marks/'.$branch.'/'.$batch->id.'/'.$standard.'/listmarks') }}">{{$standard}} - {{$batch->batchname}}</a></li>
            <li class="breadcrumb-item active">Summary Marks Report</li>
</ol>
@endsection
@section('content')
<div class="row">                       
        <div class="card">
            <table class="table table-bordered">
                                                <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    @foreach($marks as $mark)
                                                    <th>
                                                    {{$mark->topic_name}} - {{$mark->total_marks}}  
                                                    <div>{{$mark->date}}</div>
                                                    </th>
                                                    @endforeach
                                                    </tr>
                                            </thead>
                                            <tbody>
                                             @foreach ($admission as $adm)
                                                <tr>
                                                    <td>
                                                        <div>{{$adm->studentname}}</div>
                                                    </td>
                                                        @foreach($adm->marks as $ad)
                                                            @foreach($marks as $mark)
                                                            @if($ad->pivot->mark_id==$mark->id)
                                                            @if($ad->pivot->marks_obtained!="")
                                                                <td><div><strong>{{$ad->pivot->marks_obtained}}</strong></div></td>
                                                            @endif
                                                            @endif
                                                            @endforeach
                                                        @endforeach
                                                </tr>
                                            @endforeach
                                            </tbody>
                                </table>
                                
           
        </div>
    </div>     
@endsection
