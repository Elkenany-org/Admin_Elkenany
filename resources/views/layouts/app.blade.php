<!DOCTYPE html>
<html >
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>لوحة التحكم</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

  <!-- Tempusdominus Bbootstrap 4 -->
  <link rel="stylesheet" href="{{asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
  <!-- iCheck -->
  <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
  <!-- JQVMap -->
  <link rel="stylesheet" href="{{asset('plugins/jqvmap/jqvmap.min.css')}}">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
  <!-- Toastr -->
  <link rel="stylesheet" href="{{asset('plugins/toastr/toastr.css')}}">
  <!-- DataTables -->
  <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="{{asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="{{asset('plugins/daterangepicker/daterangepicker.css')}}">
  <!-- summernote -->
  <link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.css')}}">
  {{-- animate css --}}
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.2/animate.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

    <!-- Bootstrap 4 RTL -->
  <link rel="stylesheet" href="https://cdn.rtlcss.com/bootstrap/v4.2.1/css/bootstrap.min.css">
 {{-- ckeditor --}}
  {{-- <script src="https://cdn.ckeditor.com/4.13.0/standard/ckeditor.js"></script> --}}
  <script src="{{asset('dist/ckeditor/ckeditor.js')}}"></script>

{{--  <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>--}}



  <!-- Custom style for RTL -->
  <link rel="stylesheet" href="{{asset('dist/css/custom.css')}}">

  @yield('style')
</head>
<body class="hold-transition sidebar-mini">

<!-- Site wrapper -->
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light" >

    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="{{route('home')}}" class="nav-link text-primary"> <i class="nav-icon fas fa-home"> </i> الرئيسيه </a>
      </li>
      {{NavbarMenu()}}
      <li class="nav-item d-none d-sm-inline-block">
        <a href="{{route('logout')}}" class="nav-link text-danger"><i class="fas fa-sign-out-alt text-danger"></i> تسجيل خروج </a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <img src="{{ asset('dist/img/app_logo.png') }}" style="width: 38px">
      </li>
    </ul>

  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  @include('../parts.sidebar')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

    <!-- Content Header (Page header) -->
     @include('../parts.page_header')
     @include('../parts.alert')
    
    {{-- loading --}}
    <section class="content loading" style="position: fixed;z-index: 999;width: 80%;margin-top: 5%">
      <div class="row">
        <div class="col-sm-12 text-center">
          <i class="fas fa-spinner fa-pulse text-primary" style="font-size: 60px"></i>
          <h4 style="margin-top: 10px">جاري تحميل البيانات</h4>
        </div>
      </div>
    </section>
     <!-- Main content -->
    <section class="content real_content" style="display: no.e;">
        @yield('content')
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer">
    <div class="float-right d-none d-sm-block">
      <a href="https://www.facebook.com/profile.php?id=100004970520126">Programmed by Mohamed Hamada</a>
    </div>
    <strong>Copyright &copy; 2020 </strong> All rights
    reserved.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- Toastr -->
<script src="{{asset('plugins/toastr/toastr.min.js')}}"></script>
<!-- DataTables -->
<script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('dist/js/adminlte.min.js')}}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{asset('dist/js/demo.js')}}"></script>
<!-- my code -->
<script src="{{asset('dist/js/my_code.js')}}"></script>

<script type="text/javascript">

  //fillter search arabic
  $(function(){
       // $(".dataTables_filter>label:after").text('color');
       // $(".dataTables_length>label > span:first-child ").html("عدد النتائج :");
       $(".dataTables_filter>label > input").attr("placeholder", "أكتب كلمة للبحث");
    })

    $('a[data-toggle="pill"]').on('shown.bs.tab', function (e) {
      localStorage.setItem('activeTab', $(e.target).attr('href'));
    });
    
    var activeTab = localStorage.getItem('activeTab');
    if(activeTab){
      $('.nav-tabs a[href="' + activeTab + '"]').tab('show');
    }
    $(document).ready(function(){
      $('a[data-toggle="pill"]').on('show.bs.tab', function(e) {
        localStorage.setItem('activeTab', $(e.target).attr('href'));
      });
      var activeTab = localStorage.getItem('activeTab');
      if(activeTab){
        $('#myTab a[href="' + activeTab + '"]').tab('show');
      }
    });     
</script>

 @yield('script')
</body>
</html>
