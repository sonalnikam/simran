@extends('layouts.app')


@section('breadcrumb')
<ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ url('/enquiry') }}">Enquiry</a>
            </li>
            <li class="breadcrumb-item active">Add</li>
</ol>
@endsection

@section('content')
<div class="card">
                            <form action="{{ url('/enquiry/create') }}" method="post" name="addenquiry" id="addenquiry">
                                {{ csrf_field() }}
                            <div class="card-header">
                                <strong>New Enquiry</strong>
                            </div>
                            <div class="card-block">
                                    <div class="form-group">
                                    <label for="standard">Standard</label><span style="color:red"> *</span>
                                    <select id="standard" name="standard" class="form-control" size="1">
                                        <option value="">Please select</option>
                                        @foreach(App\Http\AcatUtilities\Standard::all() as $value => $code)
                                            <option value="{{$code}}" @if (old('standard') == $code) selected="selected" @endif>{{$value}}</option>
                                        @endforeach
                                    </select>
                                    <span style="color:red">{{ $errors->first('standard') }}</span>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="date">Date</label><span style="color:red"> *</span>
                                            <input name ="date" type="date" class="form-control" id="date"  placeholder="Date" value="{{ old('date') }}">
                                            <span style="color:red">{{ $errors->first('date') }}</span>
                                        </div>
                                        </div>
                                        <div class="col-md-9">     
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="name">Name</label> <span style="color:red"> *</span>
                                        <input name ="name" type="text" class="form-control" id="name" placeholder="Enquirer name" value="{{ old('name') }}">
                                        <span style="color:red">{{ $errors->first('name') }}</span>
                                    </div>
                                    <div class="form-group">
                                    <label for="school">School</label> <span style="color:red"> *</span>
                                    <select id="school" name="school" class="form-control" size="1">
                                        <option value="">Please select</option>
                                        @foreach(App\Http\AcatUtilities\Schools::all() as $value => $code)
                                            <option value="{{$code}}" @if (old('school') == $code) selected="selected" @endif>{{$value}}</option>
                                        @endforeach
                                    </select>
                                    <span style="color:red">{{ $errors->first('school') }}</span>
                                    </div>
                                    <div class="form-group" style="display: none;" id="other">
                                        <label for="otherschool">School Name</label> <span style="color:red"> *</span>
                                        <input name ="otherschool" type="text" class="form-control" id="otherschool" placeholder="School name" value="{{ old('otherschool') }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="fatherno">Father's Number</label> 
                                        <input name ="fatherno" type="tel" class="form-control" id="fatherno" placeholder="Father's Number" value="{{ old('fatherno') }}">
                                        <span style="color:red">{{ $errors->first('fatherno') }}</span>
                                    </div>
                                    <div class="form-group">
                                        <label for="motherno">Mother's Number</label>
                                        <input name ="motherno" type="tel" class="form-control" id="motherno" placeholder="Mother's Number" value="{{ old('motherno') }}">
                                        <span style="color:red">{{ $errors->first('motherno') }}</span>
                                    </div>
                                    <div class="form-group">
                                        <label for="landline">Landline</label>
                                        <input name ="landline" type="tel" class="form-control" id="landline" placeholder="Landline" value="{{ old('landline') }}">
                                        <span style="color:red">{{ $errors->first('landline') }}</span>
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

<script>
$("#school" ).change(function() 
  {
   
    var school = $("#school").val();
    if (school === 'OTHERS') {
         
           $('#other').show();
        } else {

            $('#other').hide();
        }
    
  });
$("#addenquiry").submit(function() {

  var fatherno=$('#fatherno').val();
  var motherno=$('#motherno').val();
  var landline=$('#landline').val();
  
  if(fatherno=="" & motherno=="" & landline=="")
  {
    alert("Please enter atlease one number.");
    return false;
  }
});
</script>
@endsection
