@extends('layouts.user')
@section('content')
<style>
.inmate{
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  max-width: 15ch;
}
</style>
    <div class="row h-80vh">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <!-- Card header -->
                    <div class="card-header pb-0">
                        <div class="d-lg-flex">
                            <div>
                                <h5 class="mb-0">Entries</h5>
                            </div>
                            <div class="ms-auto my-auto mt-lg-0 mt-4">
                                {{-- <div class="ms-auto my-auto">
                                    <button class="btn btn-outline-primary btn-sm mb-0 mt-sm-0 mt-1"
                                        onclick="ExportToExcel('xlsx')">Export</button>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                    <div class="card-header pb-0 col-md-4">
                        <div>
                            <h6 class="mb-3">Look up by Inmate ID or Full Name</h6>
                        </div>
                        <form id="filter" class="form-inline">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="end-date"> Inmate ID:</label>
                                        <input type="text" id="unique_id" name="unique_id" placeholder="Enter Inmate Id" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="end-date"> Name:</label>
                                        <input type="text" id="name" name="name" placeholder="Enter Name" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm">Search</button>
                        </form>
                    </div>
                    <div class="card-body px-4 pb-0">
                        <div class="table-responsive">
                            <div id="table_body">
                                <table class="table table-flush" id="data-list">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="date-head" hidden>Date Received</th>
                                            <th>Name</th>
                                            <th class="address-head" hidden>Address</th>
                                            <th class="state-head" hidden>State Incarcerated</th>
                                            <th>Inmate ID</th>
                                            <th>Request For</th>
                                            <th class="note-head" hidden>Note</th>
                                            <th>Tracking Number</th>
                                            <th class="priority-head" hidden>Priority</th>
                                            <th class="request-head" hidden>Request For</th>
                                            <th class="cstatus-head" hidden>Current Status</th>
                                            <th class="music-head" hidden>Music Count</th>
                                            <th class="receive-head" hidden>Received via</th>
                                            <th>Attachment</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>

    <!-- datatable -->
    <script>

        // if (document.getElementById('data-list')) {
        //     const dataTableSearch = new simpleDatatables.DataTable("#data-list", {
        //         searchable: true,
        //         fixedHeight: false,
        //         perPage: 10
        //     });
        // };
        // $('#data-list').DataTable({
        //     pageLength: 10,
        //     order: [],
        // });


        if (document.getElementById('editor')) {
            var quill = new Quill('#editor', {
                theme: 'snow' // Specify theme in configuration
            });
        }

        $("#filter").on('submit', function(e) {
            $("#reset-filter").removeAttr('hidden', true);
            $("#export-filter").removeAttr('hidden', true);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var data = $('#filter').serialize();
            e.preventDefault();
            $.ajax({
                data: data,
                url: "{{ route('user.search.data') }}",
                type: "GET",
                dataType: 'json',
                success: function(data) {
                    $("#table_body").html(data.data)
                },
                error: function(data) {

                }
            });
        });

        function add_address() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var data = $('#address-form').serialize();
            $.ajax({
                data: data,
                url: "{{ route('user.add.address') }}",
                type: "post",
                dataType: 'json',
                success: function(data) {
                    Swal.fire({
                            icon: 'success',
                            title: 'Successful',
                            text: 'Address Successfully Updated',
                        });
                },
                error: function(data) {

                }
            });
        }

        function create_address_form(id,address){
            $("#address_modal").modal('show');
            $("#id").val(id);
            $("#address").val(address);
        }
    </script>
@endsection
