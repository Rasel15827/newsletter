@extends('layouts.admin')
@section('content')
<div class="row h-80vh">
    <div class="row">
        <div class="col-lg-4 col-md-12 mt-4">
            <div class="card card-body">
                <h6 class="mb-0">Send Email</h6>
                <p class="text-sm mb-0">Send email to all the users</p>
                <hr class="horizontal dark my-3">
                <form id="form" class="form-control">
                    @csrf
                    <div class="row">
                        <div class="col-6">
                            <label class="form-label">Subject</label>
                            <input type="text" class="form-control" id="subject" name="subject" placeholder="Enter First Name" required>
                        </div>
                    </div>


                    <label class="form-label  mt-2">Message</label>
                    <textarea type="email" class="form-control" id="message" name="message" required>Type Your Message</textarea>
                    <div class="email-error text-danger"> </div>

                    <div class="row mt-4">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="queue" name="queue">
                            <label class="custom-control-label" for="queue">Queue On</label>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" id="submit_btn" name="button" class="btn bg-gradient-primary m-0 ms-2">Send Message</button>
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
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                        <tr>
                            <td class="text-md">{{ $user->first_name }} {{ $user->last_name }}</td>
                            <td class="text-md">{{ $user->email }}</td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
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
    $('#products-list').DataTable({
        pageLength: 50,
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
            url: "{{ route('admin.send.mail') }}",
            type: "POST",
            dataType: 'json',
            success: function(data) {
                $("#loader_btn").attr('hidden', true);
                $("#submit_btn").removeAttr('hidden');

                Swal.fire({
                    icon: 'success',
                    title: 'Successful',
                    text: 'Email Successfully Sent',
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
</script>
@endsection