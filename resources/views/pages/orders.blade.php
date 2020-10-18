@extends('layout.master', ['title' => $title, 'inclusions' => inclusions($includes)])
@section('content')

    <div class="page-content">
            <div class="container-fluid" style="max-width: 95%;">
                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-flex align-items-center justify-content-between">
                                <h4 class="mb-0 font-size-18">{{ $title }}</h4>
                                <div class="page-title-right">
                                    <a href="{{ url('download-product')}}" >
                                        <button type="button" class="btn btn-success waves-effect mb-2"><i class="fas fa-download"></i> Download</button>
                                    </a>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                            
                                    <table id="datatable" class="table table-responsive server-side table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                        <thead>
                                        <tr>
                                            <th>Order ID</th>
                                            <th>User's Name</th>
                                            <th>User's ID</th>
                                            <th>Order Amount </th>
                                            <th>Discounted amount</th>
                                            <th>Coupen Applied</th>
                                            <th>Order Status</th>
                                            <th>Payment Status</th>
                                            <th>Date</th>
                                        </tr>
                                        </thead>
                                    </table>
    
                                </div>
                            </div>
                        </div> <!-- end col -->
                    </div> <!-- end row -->
            </div>
    </div>               
@endsection 

@section('custom_page_js') 
 <!-- Script -->

<script type="text/javascript">
$(document).ready(function() {

    //========================== Datatable server side code ===================
    //var start_date = '<?php //echo @$_POST['start_date']; ?>';
    //var end_date = '<?php //echo @$_POST['end_date']; ?>';
    //var channel_type_sub = '<?php //echo @implode(',', $_POST['channel_type_sub']); ?>';
    //var filter = '<?php //echo @$_POST['filter']; ?>';
    let _token   = $('meta[name="csrf-token"]').attr('content');

    $('.server-side').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                url: "{{route('order_data')}}",
                type: "POST",
                data:{
                    _token: _token,
                    'start_date': '',
                    'end_date' : '',
                    'filter' : ''
                }
            },
            "order":[],
            "language": {
                "emptyTable": "Please select criteria from filter"
            }
        });
    });
</script>
@stop    