@extends('layouts.admin')
@section('content')
@php
use Carbon\Carbon;
@endphp
<style>
    .table thead th{
        padding: unset;
    }
    .table td,
        .table th {
            white-space: unset;
            min-width: 120px !important;
        }
</style>
    <div class="row h-80vh">
        <div class="col-lg-8 col-12 mx-auto">
            <div class="card card-body mt-4">
                <h6 class="mb-0">Edit Entry</h6>
                {{-- <p class="text-sm mb-0">Create a new Entry</p> --}}
                <hr class="horizontal dark my-3">
                <form id="form" class="form-control">
                    @csrf

                    <label class="form-label">Name *</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter Full Name"
                        value="{{ $data->name }}" required>

                    <label class="form-label mt-3">Address</label>
                    <input type="text" class="form-control" id="address" name="address" placeholder="Enter address"
                        value="{{ $data->address }}">
                    <div class="row mt-3">
                        {{-- <div class="col-6">
                            <label class="form-label">Received Date *</label>
                            <input type="date" class="form-control" id="receive_date" name="receive_date" value="{{ $data->receive_date }}" required>
                        </div> --}}
                        <div class="col-6">
                            <div class="form-group">
                                <label for="state">State Incarcerated</label>
                                <select class="form-control" id="state" name="state" oninput="validate_inmate_id('{{$data->id}}')" >
                                    <option value="" disabled='disable'>Choose one</option>
                                    <option value="AL" @if ($data->state == 'AL') selected @endif>Alabama
                                    </option>
                                    <option value="AK" @if ($data->state == 'AK') selected @endif>Alaska</option>
                                    <option value="AZ" @if ($data->state == 'AZ') selected @endif>Arizona
                                    </option>
                                    <option value="AR" @if ($data->state == 'AR') selected @endif>Arkansas
                                    </option>
                                    <option value="CA" @if ($data->state == 'CA') selected @endif>California
                                    </option>
                                    <option value="CO" @if ($data->state == 'CO') selected @endif>Colorado
                                    </option>
                                    <option value="CT" @if ($data->state == 'CT') selected @endif>Connecticut
                                    </option>
                                    <option value="DE" @if ($data->state == 'DE') selected @endif>Delaware
                                    </option>
                                    <option value="DC" @if ($data->state == 'DC') selected @endif>District Of
                                        Columbia</option>
                                    <option value="FL" @if ($data->state == 'FL') selected @endif>Florida
                                    </option>
                                    <option value="GA" @if ($data->state == 'GA') selected @endif>Georgia
                                    </option>
                                    <option value="HI" @if ($data->state == 'HI') selected @endif>Hawaii
                                    </option>
                                    <option value="ID" @if ($data->state == 'ID') selected @endif>Idaho
                                    </option>
                                    <option value="IL" @if ($data->state == 'IL') selected @endif>Illinois
                                    </option>
                                    <option value="IN" @if ($data->state == 'IN') selected @endif>Indiana
                                    </option>
                                    <option value="IA" @if ($data->state == 'IA') selected @endif>Iowa</option>
                                    <option value="KS" @if ($data->state == 'KS') selected @endif>Kansas
                                    </option>
                                    <option value="KY" @if ($data->state == 'KY') selected @endif>Kentucky
                                    </option>
                                    <option value="LA" @if ($data->state == 'LA') selected @endif>Louisiana
                                    </option>
                                    <option value="ME" @if ($data->state == 'ME') selected @endif>Maine
                                    </option>
                                    <option value="MD" @if ($data->state == 'MD') selected @endif>Maryland
                                    </option>
                                    <option value="MA" @if ($data->state == 'MA') selected @endif>Massachusetts
                                    </option>
                                    <option value="MI" @if ($data->state == 'MI') selected @endif>Michigan
                                    </option>
                                    <option value="MN" @if ($data->state == 'MN') selected @endif>Minnesota
                                    </option>
                                    <option value="MS" @if ($data->state == 'MS') selected @endif>Mississippi
                                    </option>
                                    <option value="MO" @if ($data->state == 'MO') selected @endif>Missouri
                                    </option>
                                    <option value="MT" @if ($data->state == 'MT') selected @endif>Montana
                                    </option>
                                    <option value="NE" @if ($data->state == 'NE') selected @endif>Nebraska
                                    </option>
                                    <option value="NV" @if ($data->state == 'NV') selected @endif>Nevada
                                    </option>
                                    <option value="NH" @if ($data->state == 'NH') selected @endif>New Hampshire
                                    </option>
                                    <option value="NJ" @if ($data->state == 'NJ') selected @endif>New Jersey
                                    </option>
                                    <option value="NM" @if ($data->state == 'NM') selected @endif>New Mexico
                                    </option>
                                    <option value="NY" @if ($data->state == 'NY') selected @endif>New York
                                    </option>
                                    <option value="NC" @if ($data->state == 'NC') selected @endif>North
                                        Carolina</option>
                                    <option value="ND" @if ($data->state == 'ND') selected @endif>North Dakota
                                    </option>
                                    <option value="OH" @if ($data->state == 'OH') selected @endif>Ohio</option>
                                    <option value="OK" @if ($data->state == 'OK') selected @endif>Oklahoma
                                    </option>
                                    <option value="OR" @if ($data->state == 'OR') selected @endif>Oregon
                                    </option>
                                    <option value="PA" @if ($data->state == 'PA') selected @endif>Pennsylvania
                                    </option>
                                    <option value="RI" @if ($data->state == 'RI') selected @endif>Rhode Island
                                    </option>
                                    <option value="SC" @if ($data->state == 'SC') selected @endif>South
                                        Carolina</option>
                                    <option value="SD" @if ($data->state == 'SD') selected @endif>South Dakota
                                    </option>
                                    <option value="TN" @if ($data->state == 'TN') selected @endif>Tennessee
                                    </option>
                                    <option value="TX" @if ($data->state == 'TX') selected @endif>Texas
                                    </option>
                                    <option value="UT" @if ($data->state == 'UT') selected @endif>Utah</option>
                                    <option value="VT" @if ($data->state == 'VT') selected @endif>Vermont
                                    </option>
                                    <option value="VA" @if ($data->state == 'VA') selected @endif>Virginia
                                    </option>
                                    <option value="WA" @if ($data->state == 'WA') selected @endif>Washington
                                    </option>
                                    <option value="WV" @if ($data->state == 'WV') selected @endif>West Virginia
                                    </option>
                                    <option value="WI" @if ($data->state == 'WI') selected @endif>Wisconsin
                                    </option>
                                    <option value="WY" @if ($data->state == 'WY') selected @endif>Wyoming
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Traking Number</label>
                            <input type="text" class="form-control" id="traking_number" name="traking_number"
                                placeholder="Enter Tracking Number" value="{{ $data->traking_number }}">
                        </div>
                    </div>
                    <div class="row">
                        
                        <div class="col-6">
                            <label class="form-label">Device</label>
                            <select class="form-control" id="device" name="device" required>
                                <option value="No Device" @if ($data->device == 'No Device' || $data->device == Str::title('No Device')) selected @endif>No Device
                                </option>
                                <option value="JP5 Mini" @if ($data->device == 'JP5 Mini' || $data->device == Str::title('JP5 Mini')) selected @endif>JP5 Mini
                                </option>
                                <option value="JP5S" @if ($data->device == 'JP5S' || $data->device == Str::title('JP5S')) selected @endif>JP5S</option>
                                <option value="JP6S" @if ($data->device == 'JP6S' || $data->device == Str::title('JP6S')) selected @endif>JP6S</option>
                                <option value="Other Model" @if ($data->device == 'Other Model' || $data->device == Str::title('Other Model')) selected @endif>Other Model
                                </option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Inmate ID</label>
                            <input type="text" class="form-control" id="unique_id" name="unique_id" oninput="validate_inmate_id('{{$data->id}}')"
                                placeholder="Enter Inmate ID" value="{{ $data->unique_id }}">
                            <div class="text-danger inmate-error"></div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="unlock_status">Unlock Status</label>
                                <select class="form-control" id="unlock_status" name="unlock_status">
                                    <option value="Unlocked" @if ($data->unlock_status == 'Unlocked') selected @endif>Unlocked
                                    </option>
                                    <option value="Locked" @if ($data->unlock_status == 'Locked') selected @endif>Locked
                                    </option>
                                    <option value="Not Unlockable" @if ($data->unlock_status == 'Not Unlockable') selected @endif>Not
                                        Unlockable</option>
                                    <option value="N/A" @if ($data->unlock_status == 'N/A') selected @endif>N/A</option>
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
                                    <option value="0" @if ($data->priority == 0) selected @endif>Low</option>
                                    <option value="1" @if ($data->priority == 1) selected @endif>High
                                    </option>
                                    <option value="2" @if ($data->priority == 2) selected @endif>Urgent
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="priority">Request For</label>
                                <select class="form-control" id="requested_device" name="requested_device">
                                    <option value="" selected disabled>Choose One</option>
                                    <option value="Tablet" @if ($data->requested_device == 'Tablet' || $data->requested_device == Str::title('Tablet')) selected @endif>Tablet
                                    </option>
                                    <option value="USB Drive" @if ($data->requested_device == 'USB Drive' || $data->requested_device == Str::title('USB Drive')) selected @endif>USB Drive
                                    </option>
                                    <option value="Tablet & USB Drive" @if ($data->requested_device == 'Tablet & USB Drive' || $data->requested_device == Str::title('Tablet & USB Drive')) selected @endif>
                                        Tablet & USB Drive</option>
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
                                    <option value="Downloading Music" @if ($data->current_status == 'Downloading Music') selected @endif>
                                        Downloading Music</option>
                                    <option value="Awaiting On Physical Address"
                                        @if ($data->current_status == 'Awaiting On Physical Address') selected @endif>Awaiting On Physical Address
                                    </option>
                                    <option value="In Queue For Music Download"
                                        @if ($data->current_status == 'In Queue For Music Download') selected @endif>In Queue For Music Download
                                    </option>
                                    <option value="Resolved" @if ($data->current_status == 'Resolved') selected @endif>Resolved
                                    </option>
                                    <option value="In Process" @if ($data->current_status == 'In Process') selected @endif>In
                                        Process
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="priority">Music Count</label>
                                <input type="number" class="form-control" id="music_count" step="1"
                                    name="music_count" value="{{ $data->music_count }}" placeholder="Music Count">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="priority">Received via</label>
                                <select class="form-control" id="received_via" name="received_via">
                                    <option value="" selected disabled>Choose One</option>
                                    <option value="E-Mail" @if ($data->received_via == 'E-Mail') selected @endif>E-Mail
                                    </option>
                                    <option value="E-Letter" @if ($data->received_via == 'E-Letter') selected @endif>E-Letter
                                    </option>
                                    <option value="Smartsheet" @if ($data->received_via == 'Smartsheet') selected @endif>
                                        Smartsheet</option>
                                    <option value="Mail" @if ($data->received_via == 'Mail') selected @endif>
                                        Mail</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    
                    <div class="card-body px-0 pb-0">
                        {{-- <div class="table-responsive-xl px-3"> --}}
                        <h6>Note Log</h6>
                        <table class="table table-flush" id="products-list">
                            <thead class="thead-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Updated To</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($data->notes)
                                <tr>
                                    {{-- <td>{{ Carbon::parse($log->created_at)->diffForHumans() }}</td> --}}
                                    <td>
                                        @if (Carbon::parse($data->updated_at)->diffInDays() > 1)
                                            {{ Carbon::parse($data->updated_at)->format('dS M Y') }}
                                        @else
                                            {{ Carbon::parse($data->updated_at)->diffForHumans() }}
                                        @endif
                                    </td>
                                    <td>{{ $data->notes }}</td>
                                    <td>
                                        Current Note
                                    </td>
                                </tr>
                                @endif

                                @foreach ($noteLog as $log)
                                    <tr>
                                        {{-- <td>{{ Carbon::parse($log->created_at)->diffForHumans() }}</td> --}}
                                        <td>
                                            @if (Carbon::parse($log->created_at)->diffInDays() > 1)
                                                {{ Carbon::parse($log->created_at)->format('dS M Y') }}
                                            @else
                                                {{ Carbon::parse($log->created_at)->diffForHumans() }}
                                            @endif
                                        </td>
                                        <td>{{ $log->new }}</td>
                                        <td>
                                            <a href="javascript:;" onclick="delete_log('{{ $log->id }}')"><i
                                                    class="fas fa-trash text-secondary"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{-- </div> --}}
                    </div>

                    <div class="form-group mt-3">
                        <label for="note">Note</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Enter a note"></textarea>
                    </div>
                    <div class="d-flex justify-content-end mt-4">
                        <button type="button" name="button" onclick="window.location.href='{{ route('admin.data') }}'"
                            class="btn btn-light m-0">Cancel</button>
                        <button type="submit" id="submit_btn" name="button"
                            class="btn bg-gradient-primary m-0 ms-2">Update</button>

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
            $('.inmate-error').html('');

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
                url: "{{ route('admin.update.data', $data->id) }}",
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
                            text: 'Data Successfully Updated',
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

        function delete_log(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f0934A',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $(function() {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        $.ajax({
                            url: "{{ route('admin.delete.log') }}",
                            type: "DELETE",
                            data: {
                                id: id,
                            },
                            dataType: "json",
                            success: function(data) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Successful',
                                    text: 'Log Successfully Deleted',
                                });
                                setTimeout(() => window.location.reload(), 1000);
                            },
                        });
                    });
                }
            });
        }

        function validate_inmate_id(id) {
            $('.inmate-error').html('');
            var state =  $("#state").val();
            var unique_id =  $("#unique_id").val();

            // console.log('State is '+state+' and inmate id is '+unique_id);

            $.ajax({
                data: {
                    id:id,
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
