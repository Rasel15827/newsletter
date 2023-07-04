@extends('layouts.admin')
@section('content')
    <div class="row h-80vh">
        <div class="row">
            <div class="col-lg-4 col-md-12 mt-4">
                <div class="card card-body">
                    <h6 class="mb-0">New User</h6>
                    <p class="text-sm mb-0">Create a new user</p>
                    <hr class="horizontal dark my-3">
                    <form id="form" class="form-control">
                        @csrf
                        <div class="row">
                            <div class="col-6">
                                <label class="form-label">First Name *</label>
                                <input type="text" class="form-control" id="first_name" name="first_name"
                                    placeholder="Enter First Name" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label">Last Name *</label>
                                <input type="text" class="form-control" id="last_name" name="last_name"
                                    placeholder="Enter Last Name" required>
                            </div>
                        </div>

                        <label class="form-label  mt-2">Email *</label>
                        <input type="email" class="form-control" id="email" name="email"
                            placeholder="Enter A Valid Email" required>
                        <div class="email-error text-danger"> </div>

                        <div class="row mt-3">
                            <div class="col-6">
                                <label class="form-label">Password *</label>
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="Enter a password" required>
                                <div class="password-error text-danger"> </div>
                            </div>
                            <div class="col-6">
                                <label class="form-label">Confirm Password *</label>
                                <input type="password" class="form-control" id="password_confirmation"
                                    name="password_confirmation" placeholder="Confirm password" required>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end mt-4">
                            <button type="button" name="button"
                                onclick="window.location.href='{{ route('admin.users') }}'"
                                class="btn btn-light m-0">Cancel</button>
                            <button type="submit" id="submit_btn" name="button"
                                class="btn bg-gradient-primary m-0 ms-2">Create User</button>

                            <button id="loader_btn" class="btn bg-gradient-primary m-0 ms-2" type="button" disabled hidden>
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                Loading...
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-8 col-md-12 mt-4">
                <div class="card">
                    <!-- Card header -->
                    <div class="card-header pb-0">
                        <div class="d-lg-flex">
                            <div>
                                <h5 class="mb-0">All Users</h5>
                                <p class="text-sm mb-0">
                                    You can create edit and delete a user.
                                </p>
                            </div>
                            {{-- <div class="ms-auto my-auto mt-lg-0 mt-4">
                                <div class="ms-auto my-auto">
                                    <a href="{{ route('admin.create.user') }}"
                                        class="btn bg-gradient-primary btn-sm mb-0">+&nbsp; Create A User</a>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                    <div class="card-body px-4 pb-0">
                        <div class="table-responsive">
                            <table class="table table-flush" id="products-list">
                                <thead class="thead-light">
                                    <tr>
                                        <th>User's Name</th>
                                        <th>Email</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td class="text-md">{{ $user->first_name }} {{ $user->last_name }}</td>
                                            <td class="text-md">{{ $user->email }}</td>
                                            <td class="text-md">
                                                <a href="javascript:;" onclick="view_user('{{  $user->id }}')">
                                                    <i class="fas fa-eye text-secondary"></i>
                                                </a>
                                                <a onclick="delete_user({{ $user->id }})" href="javascript:;"
                                                    data-bs-toggle="tooltip" data-bs-original-title="Delete product">
                                                    <i class="fas fa-trash text-secondary"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                                <!-- Modal -->
                                <div class="modal fade" id="user-modal" tabindex="-1"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">User's Information</h5>
                                                <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal"
                                                    aria-label="Close"><i class="fas fa-times"></i></button>
                                            </div>
                                            <div class="modal-body">
                                                <p><strong>Name :</strong> <span id="user-name"></span></p>
                                                <p><strong>Email :</strong> <span id="user-email"></span></p>
                                                <p><strong>Password :</strong> <span id="user-password"></span></p>
                                            </div>
                                            <div class="modal-footer">
                                            </div>
                                        </div>
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
    <!-- datatable -->
    <script>
        // if (document.getElementById('products-list')) {
        //     const dataTableSearch = new simpleDatatables.DataTable("#products-list", {
        //         searchable: true,
        //         fixedHeight: false,
        //         perPage: 10
        //     });

        //     document.querySelectorAll(".export").forEach(function(el) {
        //         el.addEventListener("click", function(e) {
        //             var type = el.dataset.type;

        //             var data = {
        //                 type: type,
        //                 filename: "soft-ui-" + type,
        //             };

        //             if (type === "csv") {
        //                 data.columnDelimiter = "|";
        //             }

        //             dataTableSearch.export(data);
        //         });
        //     });
        // };
        $('#products-list').DataTable({
            pageLength: 10,
            order: [],
        });



        if (document.getElementById('editor')) {
            var quill = new Quill('#editor', {
                theme: 'snow' // Specify theme in configuration
            });
        }
        $("#form").on('submit', function(e) {
            $(".email-error").html('');
            $(".password-error").html('');
            $("#submit_btn").attr('hidden', true);
            $("#loader_btn").removeAttr('hidden');

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var data = $('#form').serialize();
            e.preventDefault();
            $.ajax({
                data: data,
                url: "{{ route('admin.user.create') }}",
                type: "POST",
                dataType: 'json',
                success: function(data) {
                    $("#loader_btn").attr('hidden', true);
                    $("#submit_btn").removeAttr('hidden');

                    Swal.fire({
                        icon: 'success',
                        title: 'Successful',
                        text: 'User Successfully Created',
                    });
                    setTimeout(() => window.location.href = "{{ route('admin.users') }}", 2000);

                },
                error: function(data) {
                    $("#loader_btn").attr('hidden', true);
                    $("#submit_btn").removeAttr('hidden');

                    console.log(data);
                    if (data.responseJSON.errors.email) {
                        $(".email-error").html(data.responseJSON.errors.email[0]);
                    }
                    if (data.responseJSON.errors.password) {
                        $(".password-error").html(data.responseJSON.errors.password[0]);
                    }
                }
            });
        });

        function delete_user(id) {
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
                            url: "{{ route('admin.delete.user') }}",
                            type: "DELETE",
                            data: {
                                id: id,
                            },
                            dataType: "json",
                            success: function(data) {
                                Swal.fire(
                                    'Deleted!',
                                    'The user has been successfully deleted.',
                                    'success'
                                )
                                setTimeout(() => window.location.reload(), 1000);

                            },
                        });
                    });
                }
            });
        }
        function view_user(id) {
            $("#user-modal").modal('show');
            $(function() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{ route('admin.get.user') }}",
                    type: "GET",
                    data: {
                        id: id,
                    },
                    dataType: "json",
                    success: function(data) {
                        $("#user-name").html(data['user_name']);
                        $("#user-email").html(data['user_email']);
                        $("#user-password").html(data['user_password']);
                    },
                });
            });
        }
    </script>
@endsection
