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
                                        <button type="button" class="btn btn-success waves-effect mb-2"><i class="fe-download mr-1"></i>Brands List</button>
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
                                        <form class="needs-validation" novalidate method="POST" action="{{ url('add-brand')}}" enctype="multipart/form-data">
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="validationCustom01">Brand name *</label>
                                                        <input type="text" class="form-control" id="validationCustom01" placeholder="Brand name" name="brand_name" value="{{ old('brand_name') }}" required>
                                                        
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="validationCustom02">Brand Email *</label>
                                                        <input type="text" class="form-control" id="validationCustom01" placeholder="Brand Email" name="brand_email" value="{{ old('brand_email') }}" required>
                                                       
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="validationCustom02">Brand Phone *</label>
                                                        <input type="text" class="form-control" id="validationCustom01" placeholder="Brand Phone" name="brand_phone" value="{{ old('brand_phone') }}" required>
                                                       
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="validationCustom01">Contact Person Name *</label>
                                                        <input type="text" class="form-control" id="validationCustom01" placeholder="Contact Person Name" name="contact_person" value="{{ old('contact_person') }}" required>
                                                        
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="validationCustom01">Brand *</label>
                                                        <select class="form-control select2" name="category">
                                                            <option>Select Category</option>
                                                           
                                                        </select>
                                                     </div>   
                                                </div>
                                            </div>

                                            
                                            <div class="row">                                            
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="validationCustom01">Brand Image</label>
                                                        <input type="file" name="brand_image" class="form-control" id="validationCustom02" placeholder="Last name" required>
                                                     </div>   
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="validationCustom01">Brand Icon *</label>
                                                        <input type="file" name="brand_icon" class="form-control" id="validationCustom02" placeholder="Last name" required>
                                                    </div>
                                                </div>
                                            </div>

                                            
                                            <div class="row">                                            
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="validationCustom01">Shipping Type *</label>
                                                        <select class="form-control" name="shipping_type">
                                                            <option>Select Product Type</option>
                                                            <option>Min Order</option>
                                                            <option>Fixed Price</option>
                                                        </select>
                                                     </div>   
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="validationCustom01">Min Order Value *</label>
                                                        <input type="text" class="form-control" id="validationCustom01" placeholder="Min Order Value" name="min_order_value" value="{{ old('min_order_value') }}" required>
                                                       
                                                    </div>
                                                </div>

                                                
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="validationCustom01">Shipping Charges *</label>
                                                        <input type="text" class="form-control" id="validationCustom01" placeholder="Shipping Charges" name="shipping_charges" value="{{ old('shipping_charges') }}" required>
                                                        
                                                    </div>
                                                </div>
                                            </div>

                                            
                                            <div class="row">                                            
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="validationCustom01">Address</label>
                                                        <textarea id="textarea" class="form-control" maxlength="225" rows="3" placeholder="Address." name="address"></textarea>
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