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
                                        <label for="" class="card-title">Class</label>
                                    </div>
                                    <div class="col-lg-6 col-md-6 d-flex justify-content-end">
                                        <button type="button" class="btn btn-primary add-item" data-toggle="modal" data-target="#addModal">Add Class</button>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-lg-6 col-md-6">
                                        <form method="GET" action="{{ route('class') }}">
                                            <div class="input-group">
                                                <input type="text" name="search" class="form-control" placeholder="Search class..." value="{{ request()->query('search') }}">
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
                                                <td>{{ $item->code }}</td>
                                                <td>{{ $item->name }}</td>
                                                <td>{{ $item->description }}</td>
                                                <td>{{ $item->days }}</td>
                                                <td>{{ $item->time }}</td>
                                                <td>
                                                    <button type="button" class="btn btn-outline-secondary btn-rounded btn-icon edit-btn" data-toggle="modal" data-target="#editModal" data-id="{{ $item->id }}" data-code="{{ $item->code }}" data-name="{{ $item->name }}" data-description="{{ $item->description }}" data-days="{{ $item->days }}" data-time="{{ $item->time }}">
                                                        <i class="mdi mdi-lead-pencil text-primary"></i>
                                                    </button>
                                                    <!-- <form action="{{ route('class.updateStatus', $item->id) }}" method="POST">
                                                        @csrf
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox" id="statusSwitch{{ $item->id }}" name="status" value="inactive" {{ $item->status == 'active' ? 'checked' : '' }} onchange="this.form.submit()">
                                                            <input class="form-check-input" type="checkbox" id="statusSwitch{{ $item->id }}" name="status" value="active" {{ $item->status == 'active' ? 'checked' : '' }} onchange="this.form.submit()">
                                                        </div>
                                                    </form> -->

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
                    <h5 class="modal-title">Add Class</h5>

                </div>
                <div class="modal-body">
                    <form id="addForm">
                        @csrf
                        <div class="form-group">
                            <label for="code">Code:</label>
                            <input type="text" id="addCode" name="code" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="name">Class Name:</label>
                            <input type="text" id="addClassName" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Description:</label>
                            <input type="text" id="addDescription" name="description" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="days">Days:</label>
                            <input type="text" id="addDays" name="days" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="time">Time:</label>
                            <input type="email" id="addTime" name="time" class="form-control" required>
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
                            <label for="classCode">Class Code:</label>
                            <input type="text" id="classCode" name="code" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="className">Class Name:</label>
                            <input type="text" id="className" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="classDescription">Description:</label>
                            <input type="text" id="classDescription" name="description" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="classDay">Days:</label>
                            <input type="text" id="classDay" name="day" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="classTime">Time:</label>
                            <input type="text" id="classTime" name="time" class="form-control" required>
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

            let classCode = $(this).data('code');
            let className = $(this).data('name');
            let classDescription = $(this).data('description');
            let classDay = $(this).data('days');
            let classTime = $(this).data('time');


            $('#classCode').val(classCode);
            $('#className').val(className);
            $('#classDescription').val(classDescription);
            $('#classDay').val(classDay);
            $('#classTime').val(classTime);

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

                let classCode = document.getElementById('addCode').value;
                let className = document.getElementById('addClassName').value;
                let classDescription = document.getElementById('addDescription').value;
                let classDay = document.getElementById('addDays').value;
                let classTime = document.getElementById('addTime').value;

                // Log values to console




                $.post('/class_add', {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    code: classCode,
                    name: className,
                    description: classDescription,
                    days: classDay,
                    time: classTime,
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