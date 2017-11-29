@extends('layouts.app')


@section('breadcrumb')
<ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ url('/marks/'.$branch.'/create') }}">{{$branch}} - Batch List</a>
            </li>
            <li class="breadcrumb-item"><a href="{{ url('/marks/'.$branch.'/'.$batch->id.'/'.$standard.'/listmarks') }}">{{$standard}} - {{$batch->batchname}}</a></li>
            <li class="breadcrumb-item active">{{$marks->topic_name}}</li>
            <li class="breadcrumb-item active">Edit Marks</li>
</ol>
@endsection

@section('content')
<div class="card">
                            <form action="{{ url('/marks/'.$branch.'/'.$marks->id.'/'.$batch->id.'/'.$standard.'/editmarks') }}" method="post" name="editmarks" id="editmarks">
                                {{ csrf_field() }}
                            <div class="card-header">
                                <strong>Edit Marks Details</strong>
                            </div>
                            <div class="card-block">
                                    <div class="form-group">
                                    <label for="year">Academic Year</label><span style="color:red"> *</span>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-2">
                                            <input type="number" name="from_year" min="2015" class="form-control" max="2030" step="1" id="from_year" value="{{$marks->fromyear}}">
                                            <span style="color:red">{{ $errors->first('from_year') }}</span>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="number" name="to_year" id="to_year" min="2015" class="form-control" max="2030" step="1" value="{{$marks->toyear}}">
                                            <span style="color:red">{{ $errors->first('to_year') }}</span>
                                        </div>
                                        <div class="col-md-8">     
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="date">Date of Examination</label><span style="color:red"> *</span>
                                            <input name ="date" type="date" class="form-control" id="date"  placeholder="Date" value="{{ $marks->date }}" size="1">
                                            <span style="color:red">{{ $errors->first('date') }}</span>
                                        </div>
                                        </div>
                                        <div class="col-md-9">     
                                        </div>
                                    </div>
                                    <div class="form-group">
                                    <label for="branch">Branch</label> : 
                                        @foreach(App\Http\AcatUtilities\Branch::all() as $value => $code)
                                            @if($branch==$code)
                                            {{$value}}
                                            @endif
                                        @endforeach
                                    </div>
                                    <div class="form-group">
                                    <label for="standard">Standard</label> : {{$marks->standard}}
                                    </div>
                                    <div class="form-group">
                                        <label for="batch">Batch</label> : {{$marks->batch}} 
                                    </div>
                                    <div class="form-group">
                                        <label for="topic_name">Topic name</label> <span style="color:red"> *</span>
                                        <input name ="topic_name" type="text" class="form-control" id="topic_name" placeholder="Topic name" value="{{ $marks->topic_name }}">
                                        <span style="color:red">{{ $errors->first('topic_name') }}</span>
                                    </div>
                                    <div class="form-group">
                                        <label for="portion">Portion</label> <span style="color:red"> *</span>
                                        <input name ="portion" type="text" class="form-control" id="portion" placeholder="Portion" value="{{ $marks->portion }}">
                                        <span style="color:red">{{ $errors->first('portion') }}</span>
                                    </div>
                                    <div class="form-group">
                                        <label for="total_marks">Total marks</label> <span style="color:red"> *</span>
                                        <input name ="total_marks" type="text" class="form-control" id="total_marks" placeholder="Total marks" value="{{ $marks->total_marks }}">
                                        <span style="color:red">{{ $errors->first('total_marks') }}</span>
                                    </div>

                            </div>
                            <div class="card-footer">
                                <button type="submit" id="save" class="btn btn-primary">Save changes</button>
                                <a href="{{ URL::previous() }}" class="btn btn-default">Cancel</a> 
                            </div>
                            </form>
                        </div>
@endsection

@section('javascriptfunctions')
@endsection
