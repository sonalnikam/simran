@extends('layouts.app')


@section('breadcrumb')
<ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
            <li class="breadcrumb-item active">Export</li>
</ol>
@endsection

@section('content')
<div class="card">
                            <form action="{{ url('/export/create') }}" method="post" name="export" id="export">
                                {{ csrf_field() }}
                            <div class="card-header">
                                <strong>Export</strong>
                            </div>
                            <div class="card-block">
                                    <div class="form-group">
                                    <label for="branch">Branch</label><span style="color:red"> * </span>
                                    <select id="branch" name="branch" class="form-control" size="1">
                                        <option value="">Please select</option>
                                        @foreach(App\Http\AcatUtilities\Branch::all() as $value => $code)
                                            <option value="{{$code}}" @if (old('branch') == $code) selected="selected" @endif>{{$value}}</option>
                                        @endforeach
                                    </select>
                                    <span style="color:red">{{ $errors->first('branch') }}</span>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="Month">Month</label><span style="color:red"> *</span>
                                            <input name ="month" type="month" class="form-control" id="month"  placeholder="Month" value="{{ old('month') }}">
                                            <span style="color:red">{{ $errors->first('month') }}</span>
                                        </div>
                                        </div>
                                        <div class="col-md-9">     
                                        </div>
                                    </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" id="save" class="btn btn-primary">Export</button>
                                <a href="{{ URL::previous() }}" class="btn btn-default">Cancel</a> 
                            </div>
                            </form>
                        </div>
@endsection

