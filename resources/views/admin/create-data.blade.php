@extends('layouts.admin')
@section('content')
    <div class="row h-80vh">
        <div class="col-lg-6 col-12 mx-auto">
            <div class="card card-body mt-4">
                <h6 class="mb-0">New Entry</h6>
                <p class="text-sm mb-0">Create a new Entry</p>
                <hr class="horizontal dark my-3">
                <form id="form" class="form-control" multipart>
                    @csrf

                    <label class="form-label">Name *</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter Full Name"
                        required>

                    <label class="form-label mt-3">Address</label>
                    <input type="text" class="form-control" id="address" name="address" placeholder="Enter address">
                    <div class="row mt-3">
                        {{-- <div class="col-6">
                            <label class="form-label">Received Date *</label>
                            <input type="date" class="form-control" id="receive_date" name="receive_date" required>
                        </div> --}}
                        <div class="col-6">
                            <div class="form-group">
                                <label for="state">State Incarcerated</label>
                                <select class="form-control" id="state" name="state"  oninput="validate_inmate_id()">
                                    <option value="" disabled='disable' selected>Choose one</option>
                                    <option value="AL">Alabama</option>
                                    <option value="AK">Alaska</option>
                                    <option value="AZ">Arizona</option>
                                    <option value="AR">Arkansas</option>
                                    <option value="CA">California</option>
                                    <option value="CO">Colorado</option>
                                    <option value="CT">Connecticut</option>
                                    <option value="DE">Delaware</option>
                                    <option value="DC">District Of Columbia</option>
                                    <option value="FL">Florida</option>
                                    <option value="GA">Georgia</option>
                                    <option value="HI">Hawaii</option>
                                    <option value="ID">Idaho</option>
                                    <option value="IL">Illinois</option>
                                    <option value="IN">Indiana</option>
                                    <option value="IA">Iowa</option>
                                    <option value="KS">Kansas</option>
                                    <option value="KY">Kentucky</option>
                                    <option value="LA">Louisiana</option>
                                    <option value="ME">Maine</option>
                                    <option value="MD">Maryland</option>
                                    <option value="MA">Massachusetts</option>
                                    <option value="MI">Michigan</option>
                                    <option value="MN">Minnesota</option>
                                    <option value="MS">Mississippi</option>
                                    <option value="MO">Missouri</option>
                                    <option value="MT">Montana</option>
                                    <option value="NE">Nebraska</option>
                                    <option value="NV">Nevada</option>
                                    <option value="NH">New Hampshire</option>
                                    <option value="NJ">New Jersey</option>
                                    <option value="NM">New Mexico</option>
                                    <option value="NY">New York</option>
                                    <option value="NC">North Carolina</option>
                                    <option value="ND">North Dakota</option>
                                    <option value="OH">Ohio</option>
                                    <option value="OK">Oklahoma</option>
                                    <option value="OR">Oregon</option>
                                    <option value="PA">Pennsylvania</option>
                                    <option value="RI">Rhode Island</option>
                                    <option value="SC">South Carolina</option>
                                    <option value="SD">South Dakota</option>
                                    <option value="TN">Tennessee</option>
                                    <option value="TX">Texas</option>
                                    <option value="UT">Utah</option>
                                    <option value="VT">Vermont</option>
                                    <option value="VA">Virginia</option>
                                    <option value="WA">Washington</option>
                                    <option value="WV">West Virginia</option>
                                    <option value="WI">Wisconsin</option>
                                    <option value="WY">Wyoming</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Tracking Number</label>
                            <input type="text" class="form-control" id="traking_number" name="traking_number"
                                placeholder="Enter Tracking Number">
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-6">
                            <label class="form-label">Device</label>
                            {{-- <input type="text" class="form-control" id="device" name="device"
                                placeholder="Enter Device"> --}}
                            <select class="form-control" id="device" name="device" required>
                                <option value="No Device" selected>No Device</option>
                                <option value="JP5S">JP5S</option>
                                <option value="JP5 Mini">JP5 Mini</option>
                                <option value="JP6S">JP6S</option>
                                <option value="Other Model">Other Model</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Inmate ID</label>
                            <input type="text" class="form-control" id="unique_id" name="unique_id"
                                placeholder="Enter Inmate ID" oninput="validate_inmate_id()">
                            <div class="text-danger inmate-error"></div>
                        </div>
                    </div>
                    <div class="row mt-3">

                        <div class="col-6">
                            <div class="form-group">
                                <label for="unlock_status">Unlock Status</label>
                                <select class="form-control" id="unlock_status" name="unlock_status">
                                    <option value="Unlocked" selected>Unlocked</option>
                                    <option value="Locked">Locked</option>
                                    <option value="Not Unlockable">Not Unlockable</option>
                                    <option value="N/A">N/A</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="note">Attachment</label>
                                <input type="file" class="form-control" id="attachment" name="attachment"
                                    rows="3" accept=".jpg,.jpeg,.png">
                                <div class="text-danger" id="image-error"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="priority">Priority</label>
                                <select class="form-control" id="priority" name="priority">
                                    <option value="0" selected>Low</option>
                                    <option value="1">High</option>
                                    <option value="2">Urgent</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="priority">Request For</label>
                                <select class="form-control" id="requested_device" name="requested_device">
                                    <option value="" selected disabled>Choose One</option>
                                    <option value="Tablet">Tablet</option>
                                    <option value="USB Drive">USB Drive</option>
                                    <option value="Tablet & USB Drive">Tablet & USB Drive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="priority">Current Status</label>
                                <select class="form-control" id="current_status" name="current_status">
                                    <option value="" selected disabled>Choose One</option>
                                    <option value="Downloading Music">Downloading Music</option>
                                    <option value="Awaiting On Physical Address">Awaiting On Physical Address</option>
                                    <option value="In Queue For Music Download">In Queue For Music Download</option>
                                    <option value="Resolved">Resolved</option>
                                    <option value="In Process">In Process</option>
                                
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="priority">Music Count</label>
                                <input type="number" class="form-control" id="music_count" step="1"
                                    name="music_count" placeholder="Music Count">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="priority">Received via</label>
                                <select class="form-control" id="received_via" name="received_via">
                                    <option value="" selected disabled>Choose One</option>
                                    <option value="E-Mail">E-Mail</option>
                                    <option value="E-Letter">E-Letter</option>
                                    <option value="Smartsheet">Smartsheet</option>
                                    <option value="Mail">Mail</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <label for="note">Note</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Enter a note"></textarea>
                    </div>
                    <div class="d-flex justify-content-end mt-4">
                        <button type="button" name="button" onclick="window.location.href='{{ route('admin.data') }}'"
                            class="btn btn-light m-0">Cancel</button>
                        <button type="submit" id="submit_btn" name="button"
                            class="btn bg-gradient-primary m-0 ms-2">Create</button>

                        <button id="loader_btn" class="btn bg-gradient-primary m-0 ms-2" type="button" disabled hidden>
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Loading...
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <!-- datatable -->
    <script>
        if (document.getElementById('products-list')) {
            const dataTableSearch = new simpleDatatables.DataTable("#products-list", {
                searchable: true,
                fixedHeight: false,
                perPage: 10
            });

            document.querySelectorAll(".export").forEach(function(el) {
                el.addEventListener("click", function(e) {
                    var type = el.dataset.type;

                    var data = {
                        type: type,
                        filename: "soft-ui-" + type,
                    };

                    if (type === "csv") {
                        data.columnDelimiter = "|";
                    }

                    dataTableSearch.export(data);
                });
            });
        };



        if (document.getElementById('editor')) {
            var quill = new Quill('#editor', {
                theme: 'snow' // Specify theme in configuration
            });
        }

        $("#form").on('submit', function(e) {
            $("#submit_btn").attr('hidden', true);
            $("#loader_btn").removeAttr('hidden');
            $('.inmate-error').html();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            // var data = $('#form').serialize();
            var data = new FormData(document.getElementById('form'));
            e.preventDefault();
            $.ajax({
                data: data,
                url: "{{ route('admin.store.data') }}",
                type: "POST",
                // dataType: 'json',
                processData: false,
                contentType: false,
                success: function(data) {
                    $("#loader_btn").attr('hidden', true);
                    $("#submit_btn").removeAttr('hidden');
                    
                    data = JSON.parse(data);

                    if (data.message == "Inmate Id must be unique to state.") {
                        $('.inmate-error').html(data["message"]);
                    } else {
                        Swal.fire({
                            icon: 'success',
                            title: 'Successful',
                            text: 'Data Successfully Created',
                        });
                        setTimeout(() => window.location.href = "{{ route('admin.data') }}", 2000);
                    }


                },
                error: function(data) {
                    $("#loader_btn").attr('hidden', true);
                    $("#submit_btn").removeAttr('hidden');
                    if (data.responseJSON.errors.attachment[0]) {
                        $("#image-error").html(data.responseJSON.errors.attachment[0]);
                    }

                }
            });
        });
        function validate_inmate_id() {
            $('.inmate-error').html('');
 
            var state =  $("#state").val();
            var unique_id =  $("#unique_id").val();

            // console.log('State is '+state+' and inmate id is '+unique_id);

            $.ajax({
                data: {
                    state:state,
                    unique_id:unique_id,
                },
                url: "{{ route('admin.validate_unique_id') }}",
                type: "GET",
                dataType: 'json',
                success: function(response) {
                    console.log(response.message);
                },
                error: function(response) {

                    console.log(response.responseJSON.message);
                    if(response.responseJSON.message == "Invalid Inmate Id"){
                        $('.inmate-error').html(response.responseJSON.message);
                    }
                }
            });
        }
    </script>
@endsection
