<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="{{route('home')}}" class="brand-link">
    <img src="{{asset('dist/img/app_logo.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
         style="opacity: .8">
    <span class="brand-text font-weight-light">لوحة التحكم</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image" style="width: 50px">
        <img src="{{asset('uploads/users/avatar/'.Auth::user()->avatar)}}" class="img-circle elevation-2" alt="User Image" style="width: 100%;border-radius: 30px;height: 34px">
      </div>
      <div class="info">
        @if(Auth::check())
          <a href="{{route('eidtuser',Auth::user()->id)}}" class="d-block">{{Auth::user()->name}}</a>
        @endif
      </div>
    </div>

    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        {{menu()}}
      </ul>
    </nav>
  </div>
</aside>