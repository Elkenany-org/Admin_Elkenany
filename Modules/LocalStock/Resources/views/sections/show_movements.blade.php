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
			        <h6 class="m-0" style="display: inline;">تحديثات<span class="text-primary"> {{$member->name}} </span></h6>
            </div>
			      <div class="card-body">
              <table id="example1" class="table table-bordered table-hover table-striped">
                <thead>
                  <tr>
                  <th>#</th>
                    @if(!empty($member->Section))
                      @foreach($member->Section->LocalStockColumns as $column)
                        <th>
                          {{ $column->name }}
                        </th>
                      @endforeach
                    @endif
                  <th>التاريخ</th>
                  </tr>
                </thead>
                <tbody>
                @if(!empty($movement))
                  @foreach($movement as $key => $value)
                  <td>{{$key+1}}</td>
                    @if(!empty($value->LocalStockDetials))
                    @foreach($value->LocalStockDetials as $Mco)

                        @if($Mco->LocalStockColumns->type == 'price' )
                          <td>
                            {{ $Mco->value }}
                          </td>
                        @endif
                        @if($Mco->LocalStockColumns->type == 'change' )
                          <td>
                            {{ $Mco->value }}
                          </td>
                        @endif
                        @if($Mco->LocalStockColumns->type == 'state' )
                          <td>
                            @if($Mco->value === 'up' )
                            <img class="image" src="https://elkenany.com/uploads/full_images/arrows3-01.png" style="width:20px">
                            @endif
                            @if($Mco->value === 'down' )
                            <img class="image" src="https://elkenany.com/uploads/full_images/arrows3-02.png" style="width:20px">
                            @endif
                            @if($Mco->value === 'equal' )
                            <img class="image" src="https://elkenany.com/uploads/full_images/arrows3-03.png" style="width:20px">
                            @endif
                          </td>
                        @endif
                        @if($Mco->LocalStockColumns->type == null )
                          <td>
                          {{ $Mco->value }}
                          </td>
                        @endif
                      @endforeach
                      @endif
                        <td> <span class="badge badge-success">{{Date::parse($value->created_at)->format('h:m / Y-m-d')}}</span></td>
                      </tr>
                      
                  @endforeach
                  @endif
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


