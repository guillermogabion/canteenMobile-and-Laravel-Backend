@extends('layouts.app')

@section('content')
<div class="container-scroller">
    <div class="container-fluid page-body-wrapper">
        <div class="main-panel">
            <div class="content-wrapper">
                <div class="row mt-4">
                    <div class="grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-lg-6 col-md-6">
                                        <label for="" class="card-title">Users</label>
                                    </div>
                                    <div class="col-lg-6 col-md-6 d-flex justify-content-end">
                                        <button type="button" class="btn btn-primary add-item" data-toggle="modal" data-target="#addModal">Add User</button>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-lg-6 col-md-6">
                                        <form method="GET" action="{{ route('users') }}">
                                            <div class="input-group">
                                                <input type="text" name="search" class="form-control" placeholder="Search users..." value="{{ request()->query('search') }}">
                                                <span class="input-group-append">
                                                    <button class="btn btn-outline-secondary d-none" type="submit">Search</button>
                                                </span>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                @foreach($headers as $header)
                                                <th>{{ $header }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($items as $item)
                                            <tr>
                                                <td>{{ $item->id }}</td>
                                                <td>{{ $item->firstName }} {{ $item->lastName }}</td>
                                                <td>{{ $item->name }}</td>
                                                <td>{{ $item->address }}</td>
                                                <td>{{ ucfirst($item->role) }}</td>
                                                <td>
                                                    @if ($item->status == 'approved')
                                                    <label class="badge badge-success">Approved</label>
                                                    @else
                                                    <label class="badge badge-danger">Pending</label>
                                                    @endif
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-outline-secondary btn-rounded btn-icon edit-btn" data-toggle="modal" data-target="#editModal" data-id="{{ $item->id }}" data-name="{{ $item->name }}" data-firstName="{{ $item->firstName }}" data-lastName="{{ $item->lastName }}" data-address="{{ $item->address }}" data-role="{{ $item->role }}" data-status="{{ $item->status }}">
                                                        <i class="mdi mdi-lead-pencil text-primary"></i>
                                                    </button>
                                                    <form action="{{ route('users.updateStatus', $item->id) }}" method="POST">
                                                        @csrf
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox" id="statusSwitch{{ $item->id }}" name="status" value="pending" {{ $item->status == 'approved' ? 'checked' : '' }} onchange="this.form.submit()">
                                                            <input class="form-check-input" type="checkbox" id="statusSwitch{{ $item->id }}" name="status" value="approved" {{ $item->status == 'approved' ? 'checked' : '' }} onchange="this.form.submit()">
                                                        </div>
                                                    </form>

                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="9" class="alert alert-info">No Items</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div class="d-flex justify-content-end">
                                    {{ $items->appends(request()->query())->links('vendor.pagination.bootstrap-4') }}
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <div id="addModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-center">
                    <h5 class="modal-title">Add User</h5>

                </div>
                <div class="modal-body">
                    <form id="addForm">
                        @csrf
                        <div class="form-group">
                            <label for="firstName">First Name:</label>
                            <input type="text" id="addFirstName" name="firstName" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="addLastName">Last Name:</label>
                            <input type="text" id="addLastName" name="lastName" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="addUserName">Username:</label>
                            <input type="text" id="addUserName" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="addAddress">Address:</label>
                            <input type="text" id="addAddress" name="address" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="addEmail">Email:</label>
                            <input type="email" id="addEmail" name="email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="addRole">Select a Role:</label>
                            <select class="form-control" id="addRole" name="role" required>
                                <option value="">Select a role</option>
                                <option value="student">Student</option>
                                <option value="teacher">Teacher</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="addPassword">Password:</label>
                            <input type="password" id="addPassword" name="password" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="addPasswordConfirmation">Confirm Password:</label>
                            <input type="password" id="addPasswordConfirmation" name="password_confirmation" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary mt-4 add-btn">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <!-- Edit Modal -->
    <div id="editModal" class="modal fade" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-heade d-flex justify-content-center">
                    <h5 class="modal-title">Update Item</h5>

                </div>
                <div class="modal-body">
                    <form id="editForm">
                        @csrf
                        <input type="hidden" id="userId" name="id">
                        <div class="form-group">
                            <label for="userFirstName">First Name:</label>
                            <input type="text" id="userFirstName" name="firstName" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="userLastName">Last Name:</label>
                            <input type="text" id="userLastName" name="type" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="userAddress">Address:</label>
                            <input type="text" id="userAddress" name="address" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="userRole">Select a Role:</label>
                            <select class="form-select" id="userRole" name="role" required>
                                <option value="">Select a role</option>
                                <option value="student">Student</option>
                                <option value="teacher">Teacher</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary mt-4 edit-submit-btn">Submit</button>
                    </form>

                </div>
            </div>
        </div>
    </div>

</div>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>



<script src="{{ asset('js/jquery.cookie.js') }}"></script>

<script>
    $(document).ready(function() {
        $('.add-item').click(function() {
            $('#addModal').modal('show');
        });

        $('.edit-btn').click(function() {
            // let userId = this.getAttribute('data-id');
            // let userName = this.getAttribute('data-name');
            // let userFirstName = this.getAttribute('data-firstName');
            // let userLastName = this.getAttribute('data-lastName');
            // let userAddress = this.getAttribute('data-address');
            // let userRole = this.getAttribute('data-role');
            // let userStatus = this.getAttribute('data-status');

            let userId = $(this).data('id');
            let userName = $(this).data('name');
            let userFirstName = $(this).data('firstname');
            let userLastName = $(this).data('lastname');
            let userAddress = $(this).data('address');
            let userRole = $(this).data('role');
            let userStatus = $(this).data('status');

            console.log(userStatus, userRole)
            $('#userId').val(userId);
            $('#userName').val(userName);
            $('#userFirstName').val(userFirstName);
            $('#userLastName').val(userLastName);
            $('#userAddress').val(userAddress);
            $('#userRoleDisable').val(userRole);
            $('#userRole').val(userRole);
            $('#userStatusSelect').val(userStatus);

            $('#editModal').modal('show');
        });





        // $(document).ready(function() {
        //     $('.add-btn').click(function(e) {
        //         e.preventDefault();

        //         // return console.log('dawdwa')

        //         let userFirstName = document.getElementById('addFirstName').value;
        //         let userLastName = document.getElementById('addLastName').value;
        //         let userUserName = document.getElementById('addUserName').value;
        //         let userAddress = document.getElementById('addAddress').value;
        //         let userRole = document.getElementById('addRole').value;
        //         let userEmail = document.getElementById('addEmail').value;

        //         $.post('/user_add', {
        //             _token: $('meta[name="csrf-token"]').attr('content'),
        //             firstName: userFirstName,
        //             lastName: userLastName,
        //             name: userUserName,
        //             role: userRole,
        //             email: userEmail,
        //             address: userAddress,
        //         }).done(function(res) {
        //             Swal.fire({
        //                 title: 'Success!',
        //                 text: 'Saving Success',
        //                 icon: 'success',
        //                 confirmButtonText: 'OK'
        //             }).then((result) => {
        //                 if (result.isConfirmed) {
        //                     // Close the modal
        //                     window.location.reload();
        //                 }
        //             });
        //         }).fail(function(err) {
        //             Swal.fire({
        //                 icon: "error",
        //                 title: "Oops...",
        //                 text: err.responseJSON.message || "An error occurred",
        //                 confirmButtonText: 'OK'
        //             });
        //             console.error(err);
        //         });
        //     });
        // });

        $(document).ready(function() {
            $('.add-btn').click(function(e) {
                e.preventDefault();

                let userFirstName = document.getElementById('addFirstName').value;
                let userLastName = document.getElementById('addLastName').value;
                let userUserName = document.getElementById('addUserName').value;
                let userAddress = document.getElementById('addAddress').value;
                let userRole = document.getElementById('addRole').value;
                let userEmail = document.getElementById('addEmail').value;
                let userPassword = document.getElementById('addPassword').value;
                let userPasswordConfirmation = document.getElementById('addPasswordConfirmation').value;

                // Log values to console
                console.log("First Name: " + userFirstName);
                console.log("Last Name: " + userLastName);
                console.log("Username: " + userUserName);
                console.log("Address: " + userAddress);
                console.log("Role: " + userRole);
                console.log("Email: " + userEmail);
                console.log("Password: " + userPassword);
                console.log("Password Confirmation: " + userPasswordConfirmation);

                if (userPassword !== userPasswordConfirmation) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Passwords do not match',
                        text: 'Please make sure the passwords match.',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                $.post('/user_add', {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    firstName: userFirstName,
                    lastName: userLastName,
                    name: userUserName,
                    role: userRole,
                    email: userEmail,
                    address: userAddress,
                    password: userPassword,
                    password_confirmation: userPasswordConfirmation,
                }).done(function(res) {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Saving Success',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.reload();
                        }
                    });
                }).fail(function(err) {
                    if (err.status === 422) {
                        let errors = err.responseJSON.errors;
                        for (let key in errors) {
                            if (errors.hasOwnProperty(key)) {
                                console.error(key + ": " + errors[key]);
                            }
                        }
                        Swal.fire({
                            icon: "error",
                            title: "Validation Error",
                            text: "Please check the input data.",
                            confirmButtonText: 'OK'
                        });
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: err.responseJSON.message || "An error occurred",
                            confirmButtonText: 'OK'
                        });
                        console.error(err);
                    }
                });
            });
        });







        // // Handle Edit Form Submission
        $('.edit-submit-btn').click(function(event) {
            event.preventDefault();

            let userId = $('#userId').val();
            let userFirstName = $('#userFirstName').val();
            let userLastName = $('#userLastName').val();
            let userAddress = $('#userAddress').val();
            let userRole = $('#userRole').val();

            $.post('/user_update', {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    id: userId,
                    firstName: userFirstName,
                    lastName: userLastName,
                    address: userAddress,
                    role: userRole,
                })
                .done(function(res) {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Update Success',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Close the modal and reload the page
                            window.location.reload();
                        }
                    });
                })
                .fail(function(err) {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: err.responseJSON.message || "An error occurred",
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Close the modal and reload the page
                            window.location.reload();
                        }
                    });
                    console.error(err);
                });
        });

        $('.close-add, .close-edit').click(function() {
            event.preventDefault();

            $('#addForm')[0].reset();
            $('#editForm')[0].reset();
            $('#addModal').modal('hide');
            $('#editModal').modal('hide');

        });

        $(window).click(function(event) {
            if ($(event.target).hasClass('modal')) {
                event.preventDefault();

                $('#addModal').modal('hide');
                $('#editModal').modal('hide');
                $('#addForm')[0].reset();
                $('#editForm')[0].reset();

            }
        });
    });
</script>

@endsection