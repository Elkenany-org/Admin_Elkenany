    <section class="content-header" style="border-bottom: 1px solid #cdcdcd;padding: 13px 0 0px 0;margin-bottom: 20px">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>{{currentRoute()}}</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><i class="nav-icon fas fa-home text-primary"> </i><a href="{{route('home')}}"> الرئيسيه </a></li>
                <li class="breadcrumb-item active">{{currentRoute()}}</li>
              </ol>
            </div>
          </div>
        </div><!-- /.container-fluid -->
    </section>