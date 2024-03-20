@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="row">
      @foreach(Home() as $h)
        <div class="col-lg-3 col-6">
          <div class="small-box {{$h['color']}}">
            <div class="inner">
              <h3>{{$h['count']}}</h3>

              <p>{{$h['title']}}</p>
            </div>
            <div class="icon">
              {!!$h['icon']!!}
            </div>
            <a href="{{route($h['route'])}}" class="small-box-footer">عرض المزيد</a>
          </div>
        </div>
      @endforeach
    </div>

    <div class="row">
      <div class="col-lg-6 col-13">
        {{-- <div class="card-footer">
          <div class="row">
            <div class="col-sm-3 col-6">
              <div class="description-block border-right">
                <span class="description-percentage text-success"><i class="fas fa-caret-up"></i> 17%</span>
                <h5 class="description-header">$35,210.43</h5>
                <span class="description-text">TOTAL REVENUE</span>
              </div>
              <!-- /.description-block -->
            </div>
            <!-- /.col -->
            <div class="col-sm-3 col-6">
              <div class="description-block border-right">
                <span class="description-percentage text-warning"><i class="fas fa-caret-left"></i> 0%</span>
                <h5 class="description-header">$10,390.90</h5>
                <span class="description-text">TOTAL COST</span>
              </div>
              <!-- /.description-block -->
            </div>
            <!-- /.col -->
            <div class="col-sm-3 col-6">
              <div class="description-block border-right">
                <span class="description-percentage text-success"><i class="fas fa-caret-up"></i> 20%</span>
                <h5 class="description-header">$24,813.53</h5>
                <span class="description-text">TOTAL PROFIT</span>
              </div>
              <!-- /.description-block -->
            </div>
            <!-- /.col -->
            <div class="col-sm-3 col-6">
              <div class="description-block">
                <span class="description-percentage text-danger"><i class="fas fa-caret-down"></i> 18%</span>
                <h5 class="description-header">1200</h5>
                <span class="description-text">GOAL COMPLETIONS</span>
              </div>
              <!-- /.description-block -->
            </div>
          </div>
          <!-- /.row -->
        </div> --}}
        {{-- <div class="card">
          <div class="card-header">
            <h3 class="card-title">Latest Members</h3>

            <div class="card-tools">
              <span class="badge badge-danger">8 New Members</span>
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
              </button>
              <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i>
              </button>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body p-0">
            <ul class="users-list clearfix">
              <li>
                <img src="dist/img/user1-128x128.jpg" alt="User Image">
                <a class="users-list-name" href="#">Alexander Pierce</a>
                <span class="users-list-date">Today</span>
              </li>
              <li>
                <img src="dist/img/user8-128x128.jpg" alt="User Image">
                <a class="users-list-name" href="#">Norman</a>
                <span class="users-list-date">Yesterday</span>
              </li>
              <li>
                <img src="dist/img/user7-128x128.jpg" alt="User Image">
                <a class="users-list-name" href="#">Jane</a>
                <span class="users-list-date">12 Jan</span>
              </li>
              <li>
                <img src="dist/img/user6-128x128.jpg" alt="User Image">
                <a class="users-list-name" href="#">John</a>
                <span class="users-list-date">12 Jan</span>
              </li>
              <li>
                <img src="dist/img/user2-160x160.jpg" alt="User Image">
                <a class="users-list-name" href="#">Alexander</a>
                <span class="users-list-date">13 Jan</span>
              </li>
              <li>
                <img src="dist/img/user5-128x128.jpg" alt="User Image">
                <a class="users-list-name" href="#">Sarah</a>
                <span class="users-list-date">14 Jan</span>
              </li>
              <li>
                <img src="dist/img/user4-128x128.jpg" alt="User Image">
                <a class="users-list-name" href="#">Nora</a>
                <span class="users-list-date">15 Jan</span>
              </li>
              <li>
                <img src="dist/img/user3-128x128.jpg" alt="User Image">
                <a class="users-list-name" href="#">Nadia</a>
                <span class="users-list-date">15 Jan</span>
              </li>
            </ul>
            <!-- /.users-list -->
          </div>
          <!-- /.card-body -->
          <div class="card-footer text-center">
            <a href="javascript::">View All Users</a>
          </div>
          <!-- /.card-footer -->
        </div> --}}
      </div>
    </div>
</div>
@endsection