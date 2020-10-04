
<!-- Right bar overlay-->
<div class="rightbar-overlay"></div>

<!-- JAVASCRIPT -->
<script src="{{asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script src="{{asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('assets/libs/metismenu/metisMenu.min.js')}}"></script>
<script src="{{asset('assets/libs/simplebar/simplebar.min.js')}}"></script>
<script src="{{asset('assets/libs/node-waves/waves.min.js')}}"></script>

 <script src="{{asset('assets/js/pages/dashboard.init.js')}}"></script>
 
<!-- App js')}} -->
<script src="{{asset('assets/js/app.js')}}"></script>

@php
    if( isset($inclusions['js']) ){
        foreach($inclusions['js'] as $js) {
            echo "<script type='text/javascript' src='".url($js)."'></script>\n";
        }
    }
@endphp

<!-- page specific Js -->
@yield('custom_page_js')