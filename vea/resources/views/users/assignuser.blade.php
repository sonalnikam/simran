@extends('layouts.app')


@section('breadcrumb')
<ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
             <li class="breadcrumb-item"><a href="{{ url('/clients') }}">Clients</a>
            </li>
            <li class="breadcrumb-item"><a href="#">{{ $client->name }}</a>
            </li>
            
            <li class="breadcrumb-item active">Users</li>
        </ol>
@endsection

@section('content')
<div class="card">
    <div class="card-block">
        <div class="row">
            <div class="col-xs-5">
                  <h4 class="card-title">Assign Users</h4> 
            </div>
            <div class="col-xs-7">
                    <form action="{{ url('/clients/'.$client->id.'/users/associate/search')}}" method="get" id="frmserch">
                        {{ csrf_field() }}
                        
                        <input name ="srch" style="float: left; width: 90%;" type="text" class="form-control left" id="srch" placeholder="Search Users" value={{ old('srch') }}>
                        <button type="submit"  form="frmserch" class="btn btn-primary right" >Go</button>  
                    </form>
            </div>
        </div><!--END DIV ROW-->
    </div><!--END CARD-BLOCK-->
</div>
@php
    $url= url("/");                  
@endphp
<input type="hidden"  value="{{$url}}" id="url"/>
<div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                                <table class="table table-hover table-outline mb-0 hidden-sm-down">
                                            <thead class="thead-default">
                                                <tr>
                                                    <th class="text-xs-center"><i class="icon-people"></i>
                                                    </th>
                                                    <th>User</th>
                                                    <th>Select Role</th>
                                                </tr>
                                            </thead>
                                             
                                            <tbody>
                                                @foreach ($users as $user)
                                                <tr>
                                                    <td class="text-xs-center">
                                                        <div class="avatar">
                                                            <img src="{{ asset('img/user-icon.png') }}" class="img-avatar" alt="Client Logo">
                                                        </div>
                                                    </td>
                                                    <td>
                                                         <div><a href="{{ url('/users/'.$user->id.'/edit') }}">{{$user->name}}</a></div>
                                                    </td>
                                                    <td>

                                                    @if($user->id==$userid->id)
                                                       <select id="{{$user->id}}" name="user_list[]" class="form-control user_list"  size="1" disabled>
                                                    @else
                                                       <select id="{{$user->id}}" name="user_list[]" class="form-control user_list"  size="1">
                                                     @endif
                                                        <option value="">None</option>
                                                            @php
                                                                $found = 0;
                                                            @endphp
                                                            @foreach($roles as $role)
                                                                @foreach($user->roles as $selectedrole)
                                                                @if($role->id == $selectedrole->pivot->role_id)
                                                                    @php
                                                                        $found = 1;
                                                                    @endphp
                                                                @endif  
                                                            @endforeach 
                                                            @if($found == 1)
                                                                <option value="{{$role->id}}" selected>{{$role->label}}</option>
                                                             @else  
                                                                 <option value="{{$role->id}}">{{$role->label}}</option>
                                                            @endif    
                                                            @php
                                                                $found=0;
                                                            @endphp        
                                                            @endforeach 
                                                        </select>
                                                    
                                                    </td>
                                                </tr>
                                                @endforeach
                                        <input type="hidden" value="{{$client->id}}" id="client_id"/>
                                                <tr>
                                                    <td colspan="4" align="right">
                                                        <nav>
                                                            {{$users->links()}}
                                                        </nav>
                                                    </td>
                                                </tr>
                                            </tbody>
                                </table>
                                
                        </div>
                    </div>
                    <!--/col-->
                </div>
@endsection
@section('javascriptfunctions')
 <script>
$(document).ready(function()
{
            var url = $('#url').val();
            $('.user_list').on('change',function()
            { 
                var user_id=$(this).attr('id');
                var roleid = $(this).val();
                 var client_id =$('#client_id').val();
                if(roleid == null || roleid=="" )
                {
                   roleid=0;
                }
                $.ajax
                ({
                    type: "GET",
                    url: url + '/ajax/update/'+client_id+'/users/' + user_id+'/'+roleid,
                    
                    success: function (data) 
                    {
                      console.log(data);  
                    },
                    error: function (data) 
                    {
                        console.log('Error:', data);
                    }
                });
            });
    
});
</script>
@endsection 

 