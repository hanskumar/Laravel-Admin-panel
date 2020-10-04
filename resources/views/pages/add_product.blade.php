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
                                    <a href="{{ url('product-list')}}" >
                                        <button type="button" class="btn btn-success waves-effect mb-2"><i class="fe-download mr-1"></i>Product List</button>
                                    </a>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

                        <div class="row">
                            <div class="col-xl-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title"></h4>
                                        <form class="needs-validation" novalidate method="POST" action="{{ url(url('add-product'))}}" enctype="multipart/form-data">
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="validationCustom01">Product name *</label>
                                                        <input type="text" class="form-control" id="validationCustom01" placeholder="Product name" name="product_name" value="{{ old('product_name') }}" required>
                                                        
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="validationCustom02">Product Image</label>
                                                        <input type="file" name="product_image" class="form-control" id="validationCustom02" placeholder="Last name" required>
                                                       
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="validationCustom01">Product Descirption *</label>
                                                        <input type="text" class="form-control" id="validationCustom01" placeholder="Product Descirption" name="product_description" value="{{ old('product_description') }}" required>
                                                        
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="validationCustom01">Category *</label>
                                                        <select class="form-control select2" name="category">
                                                            <option>Select Category</option>
                                                            @foreach ($categories as $ctr)
                                                                <option value="{{ $ctr->category_id }}">{{ $ctr->category_name }}</option>
                                                            @endforeach
                                                        </select>
                                                     </div>   
                                                </div>
                                            </div>

                                            
                                            <div class="row">                                            
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="validationCustom01">Company *</label>
                                                        <select class="form-control" name="company">
                                                            <option>Select Company</option>
                                                            @foreach ($companies as $cpm)
                                                                <option value="{{ $cpm->company_id }}">{{ $cpm->company_name }}</option>
                                                            @endforeach
                                                            
                                                        </select>
                                                     </div>   
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="validationCustom01">Price *</label>
                                                        <input type="text" class="form-control" id="validationCustom01" placeholder="Product price" name="price" value="{{ old('price') }}" required>
                                                        <div class="valid-feedback">
                                                            Looks good!
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            
                                            <div class="row">                                            
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="validationCustom01">Product Type *</label>
                                                        <select class="form-control" name="product_type">
                                                            <option>Select Product Type</option>
                                                            <option>Veg</option>
                                                            <option>Non-Veg</option>
                                                        </select>
                                                     </div>   
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="validationCustom01">Flags *</label>
                                                        <input type="text" class="form-control" id="validationCustom01" placeholder="Product flags" name="flags" value="{{ old('flags') }}" required>
                                                        <div class="valid-feedback">
                                                            Looks good!
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            
                                            <div class="row">                                            
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="validationCustom01">Product Overview</label>
                                                        <textarea id="textarea" class="form-control" maxlength="225" rows="3" placeholder="product overview." name="product_overview"></textarea>
                                                     </div>   
                                                </div>
                                                
                                            </div>
                                            <center>
                                                <button class="btn btn-primary" type="submit" name="submit" value="submit">Submit</button>
                                            </center>
                                        </form>
                                    </div>
                                </div>
                                <!-- end card -->
                            </div> <!-- end col -->
					</div>
			</div>
		</div>
@endsection       

@section('custom_page_js')
<script src="{{asset('assets/libs/toastr/build/toastr.min.js')}}"></script>
<script>
    <?php echo get_flashdata('message'); ?>  
</script>
 @stop   