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
                                        <label for="" class="card-title">Examination Builder</label>
                                    </div>
                                    <div class="col-lg-6 col-md-6 d-flex justify-content-end">
                                        <button type="button" class="btn btn-primary add-item" data-toggle="modal" data-target="#addModal">Add Exam</button>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-lg-6 col-md-6">
                                        <form method="GET" action="{{ route('class') }}">
                                            <div class="input-group">
                                                <input type="text" name="search" class="form-control" placeholder="Search exam..." value="{{ request()->query('search') }}">
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
                                                <td>{{ $item->exam_title }}</td>
                                                <td>{{ $item->exam_body }}</td>
                                                <td>{{ $item->exam_type }}</td>
                                                <td>

                                                    <form action="{{ route('exam.updateStatus', $item->id) }}" method="POST">
                                                        @csrf
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox" id="statusSwitch{{ $item->id }}" name="status" value="inactive" {{ $item->status == 'active' ? 'checked' : '' }} onchange="this.form.submit()">
                                                            <input class="form-check-input" type="checkbox" id="statusSwitch{{ $item->id }}" name="status" value="active" {{ $item->status == 'active' ? 'checked' : '' }} onchange="this.form.submit()">
                                                        </div>
                                                    </form>

                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-outline-secondary btn-rounded btn-icon edit-btn" data-toggle="modal" data-target="#editModal" data-id="{{ $item->id }}" data-title="{{ $item->exam_title }}" data-body="{{ $item->exam_body }}" data-type="{{ $item->exam_type }}" data-classroom="{{ $item->classroom_id }}"">
                                                        <i class=" mdi mdi-lead-pencil text-primary"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-secondary btn-rounded btn-icon question-btn" data-toggle="modal" data-target="#questionbuilderModal" data-id="{{ $item->id }}">
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
                    <h5 class="modal-title">Add Exam</h5>

                </div>
                <div class="modal-body">
                    <form id="addForm">
                        @csrf
                        <div class="form-group">
                            <label for="exam_title">Exam Title:</label>
                            <input type="text" id="addExamTitle" name="exam_title" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="exam_body">Description:</label>
                            <input type="text" id="addExamBody" name="exam_body" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="exam_type">Type:</label>
                            <input type="text" id="addExamType" name="exam_type" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="time">Class:</label>
                            <select class="form-control" id="addClassroom" name="classroom_id" required>
                                <option value="">Select One</option>
                                @foreach($classrooms as $classroom)
                                <option value="{{ $classroom->id }}">{{ $classroom->id }}</option>
                                @endforeach
                            </select>
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
                        <input type="hidden" id="id" name="id">
                        <div class="form-group">
                            <label for="exam_title">Exam Title:</label>
                            <input type="text" id="title" name="exam_title" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="exam_body">Description:</label>
                            <input type="text" id="body" name="exam_body" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="exam_type">Type:</label>
                            <input type="text" id="type" name="exam_type" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="time">Class:</label>
                            <select class="form-control" id="classroom" name="classroom_id" required>
                                <option value="">Select One</option>
                                @foreach($classrooms as $classroom)
                                <option value="{{ $classroom->id }}">{{ $classroom->id }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary mt-4 edit-submit-btn">Submit</button>
                    </form>

                </div>
            </div>
        </div>
    </div>


    <div id="questionbuilderModal" class="modal fade" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-heade d-flex justify-content-center">
                    <h5 class="modal-title">Question Builder</h5>

                </div>
                <div class="modal-body">
                    <form id="questionForm">
                        @csrf
                        <div id="formElementsToClone" class="form-set">
                            <input type="hidden" name="id">
                            <div class="form-group">
                                <label for="exam_title">Title:</label>
                                <input type="text" name="exam_title" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="exam_body">Body:</label>
                                <input type="text" name="exam_body" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="typpe">Type:</label>
                                <select class="form-control typpe-select" name="type" required>
                                    <option value="" disabled selected>Select One</option>
                                    @foreach($types as $type)
                                    <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="option-div d-flex flex-row d-none" id="multiple">
                                <div class="col-4 d-flex align-items-center justify-content-center flex-column gap-2">
                                    <div class="circle">A</div>
                                    <input type="text" class="textbox form-control">
                                </div>
                                <div class="col-4 d-flex align-items-center justify-content-center flex-column gap-2">
                                    <div class="circle">B</div>
                                    <input type="text" class="textbox form-control">
                                </div>
                                <div class="col-4 d-flex align-items-center justify-content-center flex-column gap-2">
                                    <div class="circle">C</div>
                                    <input type="text" class="textbox form-control">
                                </div>
                            </div>
                            <div class="d-flex align-items-center justify-content-center option-div d-none" id="definition">
                                <textarea class="form-control textboxs"></textarea>
                            </div>
                            <div class="d-flex flex-row option-div d-none" id="boolean">
                                <div class="col-6 d-flex align-items-center justify-content-center flex-column gap-2">
                                    <div class="circle">True</div>
                                </div>
                                <div class="col-6 d-flex align-items-center justify-content-center flex-column gap-2">
                                    <div class="circle">False</div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="time">Exams:</label>
                                <select class="form-control" name="classroom_id" required>
                                    <option value="" disabled selected>Select One</option>
                                    <!-- Dynamically generated options -->
                                    @foreach($exams as $exam)
                                    <option value="{{ $exam->id }}">{{ $exam->exam_title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="correct">Answer:</label>
                                <input type="text" name="correct" class="form-control" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary mt-4 edit-submit-btn">Submit</button>
                        <button type="button" class="btn btn-success mt-4" onclick="addNewForm()">Add New Form</button>
                    </form>



                </div>
            </div>
        </div>
    </div>

</div>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>



<script src="{{ asset('js/jquery.cookie.js') }}"></script>

<script>
    document.getElementById('typpe').addEventListener('change', function() {
        // Get the selected option value
        var selectedOption = this.value;


        // Hide all divs initially
        var allDivs = document.querySelectorAll('.option-div');
        allDivs.forEach(function(div) {
            div.classList.add('d-none');
        });
        var selectedDiv = document.getElementById(selectedOption);
        if (selectedDiv) {
            var inputs = document.querySelectorAll('.textbox, .textboxs');
            inputs.forEach(function(input) {
                input.value = '';
            });
            selectedDiv.classList.remove('d-none');
        }
    });


    $(document).ready(function() {


        $('.add-item').click(function() {
            $('#addModal').modal('show');
        });

        $('.edit-btn').click(function() {
            let id = $(this).data('id');
            let title = $(this).data('title');
            let body = $(this).data('body');
            let type = $(this).data('type');
            let classroom = $(this).data('classroom');


            $('#id').val(id);
            $('#title').val(title);
            $('#body').val(body);
            $('#type').val(type);
            $('#classroom').val(classroom);

            $('#editModal').modal('show');
        });


        $('.question-btn').click(function() {
            $('#questionbuilderModal').modal('show');
        });



        $(document).ready(function() {
            $('.add-btn').click(function(e) {
                e.preventDefault();

                let addExamTitle = document.getElementById('addExamTitle').value;
                let addExamBody = document.getElementById('addExamBody').value;
                let addExamType = document.getElementById('addExamType').value;
                let addClassroom = document.getElementById('addClassroom').value;

                // Log values to console




                $.post('/exam_add', {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    exam_title: addExamTitle,
                    exam_body: addExamBody,
                    exam_type: addExamType,
                    classroom_id: addClassroom,

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

            let id = $('#id').val();
            let title = $('#title').val();
            let body = $('#body').val();
            let type = $('#type').val();
            let classroom = $('#classroom').val();

            $.post('/exam_edit', {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    id: id,
                    exam_title: title,
                    exam_body: body,
                    exam_type: type,
                    classroom_id: classroom,
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
            $('#questionForm')[0].reset();
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
                $('#questionForm')[0].reset();

            }
        });



    });


    // function addNewForm() {
    //     // Clone the form elements you want to duplicate
    //     var clonedFormElements = document.getElementById('formElementsToClone').innerHTML;

    //     // Create a new form group div
    //     var newFormGroup = document.createElement('div');
    //     newFormGroup.classList.add('form-group');
    //     newFormGroup.innerHTML = clonedFormElements;

    //     // Append the new form group to the modal body
    //     document.getElementById('formElementsToClone').appendChild(newFormGroup);
    // }

    function addNewForm() {
        // Clone the form elements you want to duplicate
        var formElementsToClone = document.getElementById('formElementsToClone');
        var clonedFormElements = formElementsToClone.cloneNode(true);

        // Ensure the ID is unique for the cloned form set
        clonedFormElements.removeAttribute('id');

        // Append the cloned form elements to the form
        document.getElementById('questionForm').appendChild(clonedFormElements);

        // Attach event listener to the newly added typpe-select element
        attachEventListenerToTyppeSelect(clonedFormElements.querySelector('.typpe-select'));
    }

    function attachEventListenerToTyppeSelect(selectElement) {
        selectElement.addEventListener('change', function() {
            var selectedOption = this.value;
            var formSet = this.closest('.form-set');

            var allDivs = formSet.querySelectorAll('.option-div');
            allDivs.forEach(function(div) {
                div.classList.add('d-none');
            });

            var selectedDiv = formSet.querySelector('#' + selectedOption);
            if (selectedDiv) {
                var inputs = formSet.querySelectorAll('.textbox, .textboxs');
                inputs.forEach(function(input) {
                    input.value = '';
                });
                selectedDiv.classList.remove('d-none');
            }
        });
    }
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.typpe-select').forEach(function(select) {
            attachEventListenerToTyppeSelect(select);
        });
    });
</script>
@endsection