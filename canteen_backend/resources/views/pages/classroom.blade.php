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
                                        <label for="" class="card-title">Classrooms</label>
                                    </div>
                                    <div class="col-lg-6 col-md-6 d-flex justify-content-end">
                                        @if (auth()->user()->role == 'teacher')
                                        <button type="button" class="btn btn-primary add-item" data-toggle="modal" data-target="#addModal">Add Room</button>
                                        @else
                                        <button type="button" class="btn btn-primary add-room-btn" data-toggle="modal" data-target="#addRoomModal">Add Room</button>
                                        @endif
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-lg-6 col-md-6">
                                        <form method="GET" action="{{ route('room') }}">
                                            <div class="input-group">
                                                <input type="text" name="search" class="form-control" placeholder="Search rooms..." value="{{ request()->query('search') }}">
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
                                            @if (auth()->user()->role == 'teacher')
                                            <tr>
                                                @foreach($headers as $header)
                                                <th>{{ $header }}</th>
                                                @endforeach
                                            </tr>
                                            @else
                                            <tr>
                                                @foreach($headers_student as $header)
                                                <th>{{ $header }}</th>
                                                @endforeach
                                            </tr>
                                            @endif
                                        </thead>
                                        @if (auth()->user()->role == 'teacher')
                                        <tbody>
                                            @forelse ($items as $item)
                                            <tr data-id="{{ $item->id }}" class="table-row">
                                                <td>{{ $item->id }}</td>
                                                <td>{{ $item->schedule }}</td>
                                                <td>{{ $item->description }}</td>
                                                <td>
                                                    @if ($item->status == 'active')
                                                    <label class="badge badge-success">Active</label>
                                                    @else
                                                    <label class="badge badge-danger">Inactive</label>
                                                    @endif
                                                </td>
                                                <td class="d-flex flex-row gap-2 align-items-center">
                                                    <button type="button" class="btn btn-outline-secondary btn-rounded btn-icon edit-btn" data-toggle="modal" data-target="#editModal" data-id="{{ $item->id }}" data-schedule="{{ $item->schedule }}" data-description="{{ $item->description }}">
                                                        <i class=" mdi mdi-lead-pencil text-primary"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-secondary btn-rounded btn-icon delete-btn" data-toggle="modal" data-id="{{ $item->id }}">
                                                        <i class=" mdi mdi-delete text-danger"></i>
                                                    </button>
                                                    <form class="" action="{{ route('room.updateStatus', $item->id) }}" method="POST">
                                                        @csrf
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox" id="statusSwitch{{ $item->id }}" name="status" value="inactive" {{ $item->status == 'active' ? 'checked' : '' }} onchange="this.form.submit()">
                                                            <input class="form-check-input" type="checkbox" id="statusSwitch{{ $item->id }}" name="status" value="active" {{ $item->status == 'active' ? 'checked' : '' }} onchange="this.form.submit()">
                                                        </div>
                                                    </form>
                                                    <button type="button" class="btn btn-outline-secondary btn-rounded btn-icon" data-toggle="modal" data-id="{{ $item->id }}">
                                                        <i class=" mdi mdi-login text-success"></i>
                                                    </button>

                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="9" class="alert alert-info">No Items</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                        @else
                                        <tbody>
                                            @forelse ($available as $item)
                                            <tr data-id="{{optional($item->classrooms)->id }}" class="table-row">
                                                <td>{{ $item->id }}</td>
                                                @forelse ($item->classrooms->classSets as $classSet)
                                                <td>
                                                    {{$classSet->name}}

                                                </td>
                                                <td> {{$classSet->days}}</td>
                                                <td> {{$classSet->time}}</td>
                                                @empty
                                                @endforelse
                                                <td>
                                                    @if ($item->status == 'active')
                                                    <label class="badge badge-success">Active</label>
                                                    @else
                                                    <label class="badge badge-danger">Inactive</label>
                                                    @endif
                                                </td>
                                                <td class="d-flex flex-row gap-2 align-items-center">
                                                    <button type="button" class="btn btn-outline-secondary btn-rounded btn-icon" data-id="{{optional($item->classrooms)->id }}">
                                                        <i class=" mdi mdi-login text-success"></i>
                                                    </button>

                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="9" class="alert alert-info">No Items</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                        @endif
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
                    <h5 class="modal-title">Add New Room</h5>

                </div>
                <div class="modal-body">
                    <form id="addForm">
                        @csrf
                        <div class="form-group">
                            <label for="classSet">Select a Class Course/Subject:</label>
                                <select class="form-control" id="addClassSet" name="class_set_id" required>
                                    <option value="">Select One</option>
                                    @foreach($subject as $class)
                                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                                    @endforeach
                                </select>
                        </div>
                        <div class="form-group">
                            <label for="addRoomSchedule">Schedule:</label>
                            <input type="text" id="addRoomSchedule" name="schedule" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="addRoomDescription">Description:</label>
                            <input type="text" id="addRoomDescription" name="description" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary mt-4 add-btn">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div id="addRoomModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-center">
                    <h5 class="modal-title">Add New Room</h5>

                </div>
                <div class="modal-body">
                    <form id="addForm">
                        @csrf

                        <div class="form-group">
                            <label for="addRoomCode">Enter a Class Code:</label>
                            <input type="text" id="addRoomCode" name="room_code" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary mt-4 add-room">Submit</button>
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
                        <input type="hidden" id="roomId" name="id">
                        <div class="form-group">
                            <label for="roomSchedule">Schedule: </label>
                            <input type="text" id="roomSchedule" name="schedule" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="roomDescription">Description:</label>
                            <input type="text" id="roomDescription" name="description" class="form-control" required>
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
        $('.add-room-btn').click(function() {
            $('#addModalRoom').modal('show');
        });

        $('.edit-btn').click(function() {


            let roomId = $(this).data('id');
            let roomSchedule = $(this).data('schedule');
            let roomDescription = $(this).data('description');

            $('#roomId').val(roomId);
            $('#roomSchedule').val(roomSchedule);
            $('#roomDescription').val(roomDescription);

            $('#editModal').modal('show');
        });

        $(document).ready(function() {
            $('.add-btn').click(function(e) {
                e.preventDefault();


                let roomSubject = document.getElementById('addClassSet').value;
                let roomSchedule = document.getElementById('addRoomSchedule').value;
                let roomDescription = document.getElementById('addRoomDescription').value;

                // Log values to console
                // return console.log(roomSubject, roomSchedule, roomDescription)


                $.post('/room_add', {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    class_set_id: roomSubject,
                    schedule: roomSchedule,
                    description: roomDescription,
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

        $('.add-room').click(function(e) {
            e.preventDefault();


            let roomCode = document.getElementById('addRoomCode').value;


            $.post('/user_class/add', {
                _token: $('meta[name="csrf-token"]').attr('content'),
                classroom_id: roomCode,
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
                        text: "The Code you entered is not valid" || "An error occurred",
                        confirmButtonText: 'OK'
                    });
                    console.error(err);
                }
            });
        });







        // // Handle Edit Form Submission
        $('.edit-submit-btn').click(function(event) {
            event.preventDefault();

            let roomId = $('#roomId').val();
            let roomSchedule = $('#roomSchedule').val();
            let roomDescription = $('#roomDescription').val();

            $.post('/room_edit', {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    id: roomId,
                    schedule: roomSchedule,
                    description: roomDescription,

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

        $('.delete-btn').click(function() {
            Swal.fire({
                title: `Do you want to delete this item ?`,
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: "Yes",
                denyButtonText: `No`
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    $.post('/room_delete', {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        id: $(this).attr('data-id')
                    }, function(res) {
                        Swal.fire("Deleted!", "", "success");
                        setInterval(() => {
                            window.location.reload();
                        }, 2000);
                    })
                } else if (result.isDenied) {
                    Swal.fire("Deletion Cancelled", "", "info");
                }
            });
        })
        $('.table-row').click(function() {
            let itemId = $(this).data('id');
            window.location.href = '/room/' + itemId;
        });
    });
</script>
@endsection