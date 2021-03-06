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
                                    <a href="{{ url('download-pincodes')}}" >
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
                                            <th>Category Id</th>
                                            <th>Category Name</th>
                                            <th>Category Image</th>
                                            <th>Date</th>
                                            <th>Status</th>
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
<script type="text/javascript">

$(document).ready(function() {
    //========================== Datatable server side code ===================
    let _token   = $('meta[name="csrf-token"]').attr('content');

    $('.server-side').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                url: "{{route('category.getCategries')}}",
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