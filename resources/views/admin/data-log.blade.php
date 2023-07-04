@extends('layouts.admin')
@section('content')
    @php
        use Carbon\Carbon;
    @endphp
    <style>
        .inmate {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 15ch;
        }

        .table td,
        .table th {
            white-space: unset;
            min-width: 100px !important;
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
                                <h5 class="mb-0">Entry Log</h5>
                                <h6 class="mb-0">
                                    Entry Name: {{ $name }}
                                </h6>
                            </div>
                            <div class="ms-auto my-auto mt-lg-0 mt-4">
                                <div class="ms-auto my-auto">
                                    <a href="{{ route('admin.data') }}"
                                        class="btn btn-outline-primary btn-sm mb-0 mt-sm-0 mt-1">All Entries</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body px-0 pb-0">
                        <div class="table-responsive-xl px-3">
                            <table class="table table-flush" id="products-list">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Property</th>
                                        <th>Updated To</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dataLog as $log)
                                        <tr>
                                            {{-- <td>{{ Carbon::parse($log->created_at)->diffForHumans() }}</td> --}}
                                            <td>
                                                @if (Carbon::parse($log->created_at)->diffInDays() > 1)
                                                    {{ Carbon::parse($log->created_at)->format('dS M Y') }}
                                                @else
                                                    {{ Carbon::parse($log->created_at)->diffForHumans() }}
                                                @endif
                                            </td>
                                            <td>{{ $log->property }}</td>
                                            <td>{{ $log->new }}</td>
                                            <td>
                                                <a href="javascript:;" onclick="create_edit_form('{{ $log->id }}')"><i
                                                        class="fas fa-user-edit text-secondary"></i></a>
                                                <a href="javascript:;" onclick="delete_log('{{ $log->id }}')"><i
                                                        class="fas fa-trash text-secondary"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>


                    <div class="modal fade" id="log_modal" tabindex="-1" aria-labelledby="exampleModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-md modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="log_modal_head">Edit Log </h5>
                                    <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal"
                                        aria-label="Close"><i class="fas fa-times"></i></button>
                                </div>
                                <div class="modal-body" id="address_modal_body">
                                    <form id="log-form" class="form-control">
                                        @csrf
                                        <label class="form-label">Updated To</label>
                                        <input type="hidden" class="form-control" id="id" name="id" required>
                                        <input type="text" class="form-control" id="new" name="new" required>
                                        <button type="button" onclick="update_log()"
                                            class="mt-2 btn btn-primary">Update</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $('#products-list').DataTable({
            pageLength: 50,
            order: [],
        });

        function create_edit_form(id) {
            $('#log_modal').modal('show');
            $('#id').val(id);

            $(function() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{ route('admin.view.log') }}",
                    type: "GET",
                    data: {
                        id: id,
                    },
                    dataType: "json",
                    success: function(data) {
                        $('#log_modal_head').html("Edit " + data[0]['property'] + " Log");
                        $('#new').val(data[0]['new']);
                    },
                });
            });
        }

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

        function update_log() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var data = $('#log-form').serialize();
            $.ajax({
                data: data,
                url: "{{ route('admin.update.log') }}",
                type: "POST",
                dataType: 'json',
                success: function(data) {

                    Swal.fire({
                        icon: 'success',
                        title: 'Successful',
                        text: 'Log Successfully Imported',
                    });
                    setTimeout(() => window.location.reload(), 2000);
                },
                error: function(data) {

                }
            });
        }
    </script>
@endsection
