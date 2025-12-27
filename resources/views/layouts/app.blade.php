<!doctype html>
<html lang="en">
  @include('layouts.header')
  <!--begin::Body-->
  <body class="layout-fixed sidebar-expand-lg sidebar-open bg-body-tertiary">
    <!--begin::App Wrapper-->
    <div class="app-wrapper">
      <!--begin::Header-->
      
      <!--end::Header-->
      <!--begin::Sidebar-->
      @include('layouts.sidebar')
      <!--end::Sidebar-->
        @yield('content')

    <!--begin::Footer-->
    @include('layouts.footer')
      <!--end::Footer-->
    </div>
    <!--end::App Wrapper-->
    <!--begin::Script-->
    @include('layouts.script')
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    
    <!--end::Script-->
  </body>
  <!--end::Body-->
</html>



{{-- 
<html lang="en">
@include('layouts.header')
<!-- [Body] Start -->

<body data-pc-preset="preset-1" data-pc-direction="ltr" data-pc-theme="light">
  <!-- [ Pre-loader ] start -->
<div class="loader-bg">
  <div class="loader-track">
    <div class="loader-fill"></div>
  </div>
</div>
<!-- [ Pre-loader ] End -->
@include('layouts.sidebar')
@yield('dashboard')
@yield('content')
@include('layouts.footer')
@stack('scripts')
</body>
<!-- [Body] end -->

</html> --}}