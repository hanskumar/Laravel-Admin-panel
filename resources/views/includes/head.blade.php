<!DOCTYPE html>
<html lang="en">
<head>  
        <meta charset="utf-8" />
        <title>{{ $title }}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
        <meta content="Themesbrand" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="assets/images/favicon.ico">

        <!-- Bootstrap Css -->
        <link href="{{asset('assets/css/bootstrap.min.css')}}" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="{{asset('assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="{{asset('assets/css/app.min.css')}}" id="app-style" rel="stylesheet" type="text/css" />

        <link href="{{asset('assets/libs/toastr/build/toastr.min.css')}}" rel="stylesheet" type="text/css">
        @php
        if( isset($inclusions['css']) ){
            foreach($inclusions['css'] as $file) {
                echo "<link rel='stylesheet' type='text/css' href='".url($file)."'/>\n";
            }
        }
        if( isset($inclusions['header_js']) ){
            foreach($inclusions['header_js'] as $header_js) {
                echo "<script type='text/javascript' src='".url($header_js)."'></script>\n";
            }
        }
        @endphp

        <!-- page specific Css -->
        @yield('custom_page_css')


    </head>