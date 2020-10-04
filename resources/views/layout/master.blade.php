@include('includes.head')

    @include('includes.header')

        @include('includes.menu')

    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
            <div class="main-content">
                        <!-- main content -->
                        @yield('content')

                        @include('includes.footer')               
            </div>     

    @include('includes.footer_script')
    </body>
    </html>