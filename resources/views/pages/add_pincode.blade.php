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
                                    <a href="{{ url('pincode-list')}}" >
                                        <button type="button" class="btn btn-success waves-effect mb-2"><i class="fe-download mr-1"></i>Pincode List</button>
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
                                        <form class="needs-validation" novalidate method="POST" action="{{ url(url('add-pincode'))}}">
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="validationCustom01">Zone *</label>
                                                        <select class="form-control select2" name="zone" id="zone">
                                                            <option>Select Zone</option>
                                                            <option>North</option>
                                                            <option>East</option>
                                                            <option>West</option>
                                                            <option>South</option>
                                                        </select>
                                                        
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="validationCustom02">State</label>
                                                        <select class="form-control select2" name="state" id="state"></select>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="validationCustom01">City *</label>
                                                        <select class="form-control select2" name="city" id="city">
                                                        </select>                                                        
                                                     </div>   
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="pincode">Pincode *</label>
                                                        <input type="text" class="form-control" id="pincode" placeholder="Pincode" name="pincode" value="{{ old('pincode') }}" required>
                                                        
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="validationCustom01">Address *</label>
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

        //============= Filters ajax ==============
		$('#zone').change(function(event) {
			var zone = $(this).val();
            let _token   = $('meta[name="csrf-token"]').attr('content');
			
			$.ajax({
				url: "/get_state",
				type: 'POST',
				data: {
					zone : zone,
                    _token: _token
				},
				success: function(response){
                    $("#state").empty();
                    $("#city").empty();
					
					$("#state").html("<option value='' disabled>Select State</option>");

					for(i = 0; i < response.length; i++){
						$("#state").append("<option value='"+response[i].statename+"'>"+response[i].statename+"</option>");
					}
				}
			});
		});


        $('#state').change(function(event) {
			var state = $(this).val();
			let _token   = $('meta[name="csrf-token"]').attr('content');
            
			$.ajax({
				url: '/get_city',
				type: 'POST',
				data: {
                    _token: _token,
					state : state
				},
				success: function(response){

					$("#city").html("<option value='' disabled>Select City</option>");
					for(i = 0; i < response.length; i++){
						$("#city").append("<option value='"+response[i].cityname+"'>"+response[i].cityname+"</option>");
					}
				}
		    });
        });    

</script>    
 @stop   