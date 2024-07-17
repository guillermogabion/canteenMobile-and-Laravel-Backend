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
                                <div class="d-flex flex-column">
                                    <label for="">Available Meals today</label>
                                    <div class="d-flex flex-row gap-3 mt-3">
                                        @foreach($todays as $today)
                                        @php
                                        $imageUrl = url('meal/' . $today->todaymeal->image);
                                        @endphp
                                        <div class="meal-item">
                                            <div class="">
                                                <img src="{{ $imageUrl }}" class="today-menu" alt="{{ $today->todaymeal->name }}" />
                                            </div>
                                            <div class="d-flex justify-content-center mt-2">
                                                <p>{{ $today->todaymeal->name }}</p>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-lg-6 col-md-6">
                                        <label for="" class="card-title">Meals</label>
                                    </div>
                                    <div class="col-lg-6 col-md-6 d-flex justify-content-end">
                                        <button type="button" class="btn btn-primary add-item" data-toggle="modal" data-target="#addModal">Add Meal</button>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-lg-6 col-md-6">
                                        <form method="GET" action="{{ route('meal') }}">
                                            <div class="input-group">
                                                <input type="text" name="search" class="form-control" placeholder="Search file..." value="{{ request()->query('search') }}">
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
                                                <td>{{ $item->name }}</td>
                                                <td>{{ $item->description }}</td>
                                                <td>{{ $item->price }}</td>
                                                <td>
                                                    <button type="button" class="btn btn-outline-secondary btn-rounded btn-icon edit-btn" data-toggle="modal" data-target="#editModal" data-id="{{ $item->id }}" data-name="{{ $item->name }}" data-description="{{ $item->description }}" data-image="{{ $item->image }}" data-price="{{$item->price}}">
                                                        <i class="mdi mdi-lead-pencil text-primary"></i>
                                                    </button>
                                                    {{-- <form action="{{ route('class.updateStatus', $item->id) }}" method="POST">
                                                    @csrf
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" id="statusSwitch{{ $item->id }}" name="status" value="inactive" {{ $item->status == 'active' ? 'checked' : '' }} onchange="this.form.submit()">
                                                        <input class="form-check-input" type="checkbox" id="statusSwitch{{ $item->id }}" name="status" value="active" {{ $item->status == 'active' ? 'checked' : '' }} onchange="this.form.submit()">
                                                    </div>
                                                    </form> --}}
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
                    <h5 class="modal-title">Add Meal</h5>
                </div>
                <div class="modal-body">
                    <form id="addForm" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <div class="form-group">
                                <label for="addMealImageInput" id="addMealImageLabel" class="custom-file-upload" style="display: block; width: 100%; height: 200px; cursor: pointer; background-size: contain; background-repeat: no-repeat; background-position: center; background-image: url('/images/meal_logo.png');">
                                    <input type="file" class="form-control-file" name="image" id="addMealImageInput" required onchange="previewAddImage(event)" style="display: none;">
                                    <span id="addImageLabelText" style="display: block; text-align: center; line-height: 200px; color: #6c757d;">Choose File</span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="addMealName">Meal Name</label>
                            <input type="text" class="form-control" name="name" id="addMealName" required>
                        </div>
                        <div class="form-group">
                            <label for="addMealDescription">Description</label>
                            <textarea class="form-control" name="description" id="addMealDescription" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="addMealPrice">Price</label>
                            <input class="form-control" name="price" id="addMealPrice" required></input>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Meal</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>



<!-- Edit Modal -->
<div id="editModal" class="modal fade" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-center">
                <h5 class="modal-title">Update Meal</h5>
            </div>
            <div class="modal-body">
                <form id="editForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="mealId" name="id">
                    <div class="form-group">
                        <input type="file" class="form-control-file" name="image" id="updateMealImageInput" onchange="previewUpdateImage(event)" style="display: none;">
                        <label for="updateMealImageInput" id="updateMealImageLabel" class="custom-file-upload" style="display: block; width: 100%; height: 200px; cursor: pointer; background-size: contain; background-repeat: no-repeat; background-position: center;">
                            <span id="updateImageLabelText" style="display: block; text-align: center; line-height: 200px; color: #6c757d;">Choose File</span>
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="updateMealName">Meal Name</label>
                        <input type="text" class="form-control" name="name" id="updateMealName" required>
                    </div>
                    <div class="form-group">
                        <label for="updateMealDescription">Description</label>
                        <textarea class="form-control" name="description" id="updateMealDescription" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="updateMealPrice">Price</label>
                        <input type="text" class="form-control" name="price" id="updateMealPrice" required>
                    </div>
                    <button type="submit" class="btn btn-primary edit-submit-btn">Update Meal</button>
                </form>
            </div>
        </div>
    </div>
</div>




</div>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>



<script src="{{ asset('js/jquery.cookie.js') }}"></script>

<script>
    function previewAddImage(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('addMealImageLabel');
            var labelText = document.getElementById('addImageLabelText');
            output.style.backgroundImage = 'url(' + reader.result + ')';
            labelText.style.display = 'none';
        }
        reader.readAsDataURL(event.target.files[0]);
    }

    function resetAddImagePreview() {
        var output = document.getElementById('addMealImageLabel');
        var labelText = document.getElementById('addImageLabelText');
        var inputFile = document.getElementById('addMealImageInput');

        output.style.backgroundImage = 'url(/images/meal_logo.png)';
        labelText.style.display = 'block';
        inputFile.value = '';
    }

    // Attach event listener to reset the add modal on close
    $('#addModal').on('hidden.bs.modal', function() {
        resetAddImagePreview();
    });

    function previewUpdateImage(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('updateMealImageLabel');
            var labelText = document.getElementById('updateImageLabelText');
            output.style.backgroundImage = 'url(' + reader.result + ')';
            labelText.style.display = 'none';
        }
        reader.readAsDataURL(event.target.files[0]);
    }

    $('.edit-btn').click(function() {
        let mealId = $(this).data('id');
        let mealName = $(this).data('name');
        let mealDescription = $(this).data('description');
        let mealPrice = $(this).data('price');
        let mealImage = $(this).data('image');

        $('#mealId').val(mealId);
        $('#updateMealName').val(mealName);
        $('#updateMealPrice').val(mealPrice);
        $('#updateMealDescription').val(mealDescription);

        var output = document.getElementById('updateMealImageLabel');
        var labelText = document.getElementById('updateImageLabelText');

        if (mealImage) {
            let imageUrl = '/meal/' + mealImage;
            output.style.backgroundImage = 'url(' + imageUrl + ')';
            labelText.style.display = 'none';
        } else {
            output.style.backgroundImage = 'url(/images/meal_logo.png)';
            labelText.style.display = 'block';
        }

        $('#editModal').modal('show');
    });


    $(document).ready(function() {
        $('.add-item').click(function() {
            $('#addModal').modal('show');
        });



        $(document).ready(function() {
            $('#addForm').submit(function(e) {
                e.preventDefault();

                let mealName = $('#addMealName').val();
                let mealDescription = $('#addMealDescription').val();
                let mealPrice = $('#addMealPrice').val();
                let mealImage = $('#addMealImageInput').prop('files')[0]; // Get the selected file

                let formData = new FormData();
                formData.append('name', mealName);
                formData.append('description', mealDescription);
                formData.append('price', mealPrice);
                formData.append('image', mealImage);

                $.post({
                    url: '{{ route("meal.store") }}',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Swal.fire({
                            title: 'Success!',
                            text: 'Meal added successfully!',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.reload();
                            }
                        });
                    },
                    error: function(xhr, status, error) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
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
                                text: xhr.responseJSON.message || "An error occurred",
                                confirmButtonText: 'OK'
                            });
                            console.error(xhr);
                        }
                    }
                });
            });
        });








        // // Handle Edit Form Submission
        $(document).ready(function() {
            $('.edit-submit-btn').click(function(event) {
                event.preventDefault();

                let formData = new FormData($('#editForm')[0]);

                $.ajax({
                    url: '/meal_update',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(res) {
                        Swal.fire({
                            title: 'Success!',
                            text: 'Meal updated successfully!',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.reload();
                            }
                        });
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: xhr.responseJSON.message || "An error occurred",
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.reload();
                            }
                        });
                        console.error(xhr);
                    }
                });
            });
        });

        $('.close-add, .close-edit').click(function() {
            event.preventDefault();

            $('#addForm')[0].reset();
            $('#editForm')[0].reset();
            $('#addModal').modal('hide');
            $('#editModal').modal('hide');
            $('#addMealImageInput').val('');
            $('#addMealImageLabel').text('');


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

    function showFileTypeIndicator() {
        var fileInput = document.getElementById('fileInput');
        var fileTypeIndicator = document.getElementById('fileTypeIndicator');
        var file = fileInput.files[0];
        var fileNameDiv = document.getElementById('fileName');
        var filePreview = document.getElementById('filePreview')

        if (file) {
            var fileName = file.name;
            fileNameDiv.textContent = fileName;
            var fileExtension = fileName.split('.').pop().toLowerCase();

            var fileIcons = {
                'image': ['jpg', 'jpeg', 'png', 'gif'],
                'document': ['doc', 'docx', 'pdf', 'txt'],
                'excel': ['xls', 'xlsx', 'xlsm'],
                'executable': ['exe', 'apk', 'bat', 'cmd'],
                'comp': ['zip ', 'rar '],
            };

            var iconHtml = '';

            if (fileIcons.image.includes(fileExtension)) {
                iconHtml = '<img src="http://127.0.0.1:8000/icons/picture-com.svg" alt="Image File" style="width: 50px;">';


            } else if (fileIcons.document.includes(fileExtension)) {
                iconHtml = '<img src="http://127.0.0.1:8000/icons/file-com.svg" alt="Document File" style="width: 100px;">';
            } else if (fileIcons.excel.includes(fileExtension)) {
                iconHtml = '<img src="http://127.0.0.1:8000/icons/excel-com.svg" alt="Document File" style="width: 100px;">';
            } else if (fileIcons.excel.includes(fileExtension)) {
                iconHtml = '<img src="http://127.0.0.1:8000/icons/exe.svg" alt="Document File" style="width: 100px;">';
            } else if (fileIcons.comp.includes(fileExtension)) {
                iconHtml = '<img src="http://127.0.0.1:8000/icons/comp.svg" alt="Document File" style="width: 100px;">';
            } else {
                iconHtml = '<img src="http://127.0.0.1:8000/icons/default.svg" alt="File" style="width: 100px;">';
            }

            fileTypeIndicator.innerHTML = iconHtml;
        } else {
            fileNameDiv.textContent = '';
            fileTypeIndicator.innerHTML = '';
            filePreview.innerHTML = '';
        }
    }

    const deleteButtons = document.querySelectorAll('.delete-btn');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const fileId = this.getAttribute('data-id');
            const url = `/file/${fileId}`;

            if (confirm('Are you sure you want to delete this file?')) {
                fetch(url, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.message === 'File deleted successfully') {
                            alert('File deleted successfully');
                            location.reload(); // Reload the page to reflect the changes
                        } else {
                            alert('An error occurred while deleting the file');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while deleting the file');
                    });
            }
        });
    });
</script>

@endsection