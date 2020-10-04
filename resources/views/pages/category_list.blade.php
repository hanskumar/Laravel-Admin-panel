@extends('layout.master', ['title' => $title, 'inclusions' => inclusions($includes)])
@section('content')

    <div class="page-content">
            <div class="container-fluid">
                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-flex align-items-center justify-content-between">
                                <h4 class="mb-0 font-size-18">{{ $title }}</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Tables</a></li>
                                        <li class="breadcrumb-item active">Data Tables</li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                            
                                    <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                        <thead>
                                        <tr>
                                            <th>Category Id</th>
                                            <th>Category Name</th>
                                            <th>Category Image</th>
                                            <th>date</th>
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
 <!-- Script -->
 <script type="text/javascript">
    $(document).ready(function(){

      // DataTable
      $('#datatable').DataTable({
        
         processing: true,
         serverSide: true,
         ajax: "{{route('category.getCategries')}}",
         columns: [
            { data: 'category_id' },
            { data: 'category_name' },
            { data: 'category_image' },
            { data: 'category_status' },
         ]
      });

    });
    </script>
@stop    