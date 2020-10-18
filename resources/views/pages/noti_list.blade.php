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
                            
                                    <table id="datatable" class="table server-side table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                        <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>Message</th>
                                            <th>App</th>
                                            <th>Date</th>
                                            <th>Time</th>
                                            <th>Notification Read Status</th>
                                           
                                        </tr>
                                        </thead>
                                    </table>
    
                                </div>
                            </div>
                        </div> <!-- end col -->
                    </div> <!-- end row -->

                    <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title mt-0" id="myLargeModalLabel">Filters</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                        <form class="needs-validation" novalidate method="POST" action="{{ url('add-product')}}" enctype="multipart/form-data">
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="validationCustom01">Company </label>
                                                        <select class="form-control" name="company">
                                                            
                                                        </select>
                                                        
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="validationCustom02">Category </label>
                                                        <select class="form-control select2" name="category">
                                                            <option>Select Category</option>
                                                            
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="validationCustom01">Product Type </label>
                                                        <select class="form-control" name="product_type">
                                                            <option>Select Product Type</option>
                                                            <option>Veg</option>
                                                            <option>Non-Veg</option>
                                                        </select>
                                                        
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="validationCustom02">Status </label>
                                                        <select class="form-control" name="product_type">
                                                            <option>Select Product Status</option>
                                                            <option>Active</option>
                                                            <option>InActive</option>
                                                        </select>
                                                       
                                                    </div>
                                                </div>
                                            </div>

                                            <center>
                                                <button class="btn btn-success" type="submit" name="submit" value="submit"><i class="fab fa-get-pocket"></i> Get Data</button>
                                            </center>
                                        </form>    
                                </div>
                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->


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
                url: "{{route('noti_data')}}",
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