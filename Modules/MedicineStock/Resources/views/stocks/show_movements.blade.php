@extends('layouts.app')

@section('style')
<style type="text/css">
	#avatar{
		width: 150px;
	}
	#avatar:hover{
		width: 150px;
		cursor: pointer;
	}
</style>
@endsection

@section('content')
<div class="container-fluid">
<div class="card card-primary card-outline">

<div class="card-body">
	  <div class="row">
		  <div class="col-sm-12">
            <div class="card-header">
			        <h6 class="m-0" style="display: inline;">تحديثات<span class="text-primary"> {{$member->Company->name}} </span></h6>
            </div>
			      <div class="card-body">
              <table id="example1" class="table table-bordered table-hover table-striped">
                <thead>
                  <tr>
                  <th>#</th>

                  <th>السعر </th>
                  <th>مقدار التغير </th>
                  <th>اتجاه التغير </th>
                  <th>التاريخ</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($movement as $key => $value)
                  <tr>
                    <td>{{$key+1}}</td>
                    <td>{{ $value->price }}</td>
                    <td>{{ $value->change }}</td>
                    <td>
                      @if($value->status === 'up' )
                        <img class="image" src="https://upload.wikimedia.org/wikipedia/commons/f/fe/Green-Up-Arrow.svg" style="width:20px">
                      @endif
                      @if($value->status === 'down' )
                        <img class="image" src="https://freesvg.org/img/arrowdownred.png" style="width:20px">
                      @endif
                      @if($value->status === 'equal' )
                        <img class="image" src="https://www.pngrepo.com/png/106212/180/equal.png" style="width:20px">
                      @endif
                      @if($value->status == null )
                        {{$value->status}}
                      @endif
                    </td>
                    <td> <span class="badge badge-success">{{Date::parse($value->created_at)->format('h:m / Y-m-d')}}</span></td>
                  </tr>
                      
                  @endforeach
                </tbody>
              </table>
            </div>
	      	</div>
		    </div>
    </div>
 </div>
</div>
@endsection

@section('script')
<script type="text/javascript">
 
 
   
</script>
@endsection


