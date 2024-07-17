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
                                        <label for="" class="card-title">Stocks</label>
                                    </div>
                                    <div class="col-lg-6 col-md-6 d-flex justify-content-end">
                                        <button type="button" class="btn btn-primary add-item" data-toggle="modal" data-target="#addModal">Add Item</button>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-lg-6 col-md-6">
                                        <form method="GET" action="{{ route('warehouse') }}">
                                            <div class="input-group">
                                                <input type="text" name="search" class="form-control" placeholder="Search items..." value="{{ request()->query('search') }}">
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
                                                <td>{{ $item->item_name }}</td>
                                                <td>{{ $item->type }}</td>
                                                <td>{{ $item->user->firstName }}</td>
                                                <td>{{ $item->location }}</td>
                                                <td>{{ $item->branch ? $item->branch : 'Null' }}</td>
                                                <td>{{ $item->quantity }}</td>
                                                <td>
                                                    @if ($item->status == 'active')
                                                    <label class="badge badge-success">Active</label>
                                                    @elseif ($item->status == 'critical')
                                                    <label class="badge badge-warning">Warning</label>
                                                    @else
                                                    <label class="badge badge-danger">Empty</label>
                                                    @endif
                                                </td>
                                                <td>{{ $item->expiry }}</td>
                                                <td>
                                                    <button data-toggle="tooltip" title="Edit" type="button" class="btn btn-outline-secondary btn-rounded btn-icon edit-btn" data-toggle="modal" data-target="#editModal" data-id="{{ $item->id }}" data-name="{{ $item->item_name }}" data-type="{{ $item->type }}" data-location="{{ $item->location }}" data-branch="{{ $item->branch }}" data-price="{{ $item->price }}" data-quantity="{{ $item->quantity }}" data-status="{{ $item->status }}" data-expiry="{{ $item->expiry }}" data-batch="{{ $item->batch_no }}">
                                                        <i class="mdi mdi-lead-pencil text-primary"></i>
                                                    </button>
                                                    <button data-toggle="tooltip" title="Print" type="button" class="btn btn-outline-secondary btn-rounded btn-icon print-btn" data-toggle="modal" data-target="#editModal" data-id="{{ $item->id }}" data-itemcode="{{ $item->item_code }}">
                                                        <i class="mdi mdi-printer text-primary"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                        @empty
                                        <div>
                                            <span colspan="12" class="alert alert-info justify-content-center d-flex">No Items</span>
                                        </div>
                                        @endforelse
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
                    <h5 class="modal-title">Add Item</h5>
                    <!-- <button type="button" class="close close-add" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button> -->
                </div>
                <div class="modal-body">
                    <form id="addForm">
                        @csrf
                        <div class="form-group">
                            <label for="addName">Item Name:</label>
                            <input type="text" id="addName" name="item_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="addType">Type:</label>
                            <input type="text" id="addType" name="type" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="addType">Batch No:</label>
                            <input type="text" id="addBatch" name="batch_no" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="addLocation">Location:</label>
                            <input type="text" id="addLocation" name="location" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="addBranch">Branch:</label>
                            <input type="text" id="addBranch" name="branch" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="addQuantity">Quantity:</label>
                            <input type="number" min="1" id="addQuantity" name="quantity" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="addQuantity">Price:</label>
                            <input type="number" min="1" id="addPrice" name="price" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="addExpiry">Expiry Date:</label>
                            <input type="date" id="addExpiry" name="expiry" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="">Supplier</label>
                            <select name="supplier_id" id="addSuppId" class="form-select">
                                <option value="" disabled selected>Please Select a Supplier</option>
                                @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
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
                <div class="modal-header d-flex justify-content-center">
                    <h5 class="modal-title">Update Item</h5>
                    <!-- <button type="button" class="close close-edit" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button> -->
                </div>
                <div class="modal-body">
                    <form id="editForm">
                        @csrf
                        <input type="hidden" id="editId" name="id">
                        <div class="form-group">
                            <label for="editName">Item Name:</label>
                            <input type="text" id="editName" name="item_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="editType">Type:</label>
                            <input type="text" id="editType" name="type" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="editBatch">Batch:</label>
                            <input type="text" id="editBatch" name="batch" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="editLocation">Location:</label>
                            <input type="text" id="editLocation" name="location" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="editBranch">Branch:</label>
                            <input type="text" id="editBranch" name="branch" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="editQuantity">Quantity:</label>
                            <input type="number" id="editQuantity" name="quantity" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="editPrice">Price:</label>
                            <input type="number" id="editPrice" name="price" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="editStatus">Status:</label>
                            <input type="text" id="editStatus" name="status" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="editExpiry">Expiry Date:</label>
                            <input type="date" id="editExpiry" name="expiry" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary mt-4 edit-submit-btn">Submit</button>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <!-- print modal  -->
    <div id="printModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Print Barcode</h5>

                </div>
                <div class="modal-body text-center">
                    <svg id="barcode"></svg>
                    <div class="form-group">
                        <label for="copies">Number of Copies:</label>
                        <input type="number" id="copies" class="form-control" value="1" min="1">
                    </div>
                    <button type="button" class="btn btn-primary mt-4" onclick="printBarcode()">Print</button>
                </div>
            </div>
        </div>
    </div>




</div>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>



<script src="{{ asset('js/jquery.cookie.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.6.0/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html-docx-js/0.3.1/html-docx.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.0/dist/JsBarcode.all.min.js"></script>


<script>
    $(document).ready(function() {

        $('[data-toggle="tooltip"]').tooltip();

        $('.print-btn').click(function() {
            let itemCode = $(this).data('itemcode');
            generateBarcode(itemCode, 'barcode');
            $('#printModal').modal('show');
        });

        function generateBarcode(text, elementId) {
            JsBarcode(`#${elementId}`, text, {
                format: "CODE128",
                lineColor: "#000",
                width: 0.8,
                height: 30,
                displayValue: true,
                fontSize: 10

            });
        }

        window.printBarcode = function() {
            let numCopies = parseInt(document.getElementById('copies').value) || 1;
            let pageContent = '';

            for (let i = 0; i < numCopies; i++) {
                pageContent += document.getElementById('barcode').outerHTML;
            }

            let originalContents = document.body.innerHTML;
            document.body.innerHTML = pageContent;

            document.querySelectorAll('.time, .app-name').forEach(function(element) {
                element.style.display = 'none';
            });
            document.querySelectorAll('.barcode-text').forEach(function(element) {
                element.style.fontSize = '10px';
            });


            window.print();

            // Restore original contents
            document.body.innerHTML = originalContents;

            window.location.reload();
        };

        $('.add-item').click(function() {
            $('#addModal').modal('show');
        });

        $('.edit-btn').click(function() {
            let itemId = this.getAttribute('data-id');
            let itemName = this.getAttribute('data-name');
            let itemType = this.getAttribute('data-type');
            let itemBatch = this.getAttribute('data-batch');
            let itemLocation = this.getAttribute('data-location');
            let itemBranch = this.getAttribute('data-branch');
            let itemPrice = this.getAttribute('data-price');
            let itemQuantity = this.getAttribute('data-quantity');
            let itemStatus = this.getAttribute('data-status');
            let itemExpiry = this.getAttribute('data-expiry');
            // let itemSupplier = this.getAttribute('data-expiry');

            $('#editId').val(itemId);
            $('#editName').val(itemName);
            $('#editType').val(itemType);
            $('#editBatch').val(itemBatch);
            $('#editLocation').val(itemLocation);
            $('#editBranch').val(itemBranch);
            $('#editQuantity').val(itemQuantity);
            $('#editPrice').val(itemPrice);
            $('#editStatus').val(itemStatus);
            $('#editExpiry').val(itemExpiry);

            $('#editModal').modal('show');
        });





        $(document).ready(function() {

            function generateNumberCode(itemName) {
                let hash = 0;
                for (let i = 0; i < itemName.length; i++) {
                    const char = itemName.charCodeAt(i);
                    hash = (hash << 5) - hash + char;
                    hash |= 0; // Convert to 32bit integer
                }
                return Math.abs(hash);
            }
            $('.add-btn').click(function() {
                event.preventDefault();

                let itemName = document.getElementById('addName').value;
                let itemType = document.getElementById('addType').value;
                let itemBatch = document.getElementById('addBatch').value;
                let itemLocation = document.getElementById('addLocation').value;
                let itemBranch = document.getElementById('addBranch').value;
                let itemQuantity = document.getElementById('addQuantity').value;
                let itemPrice = document.getElementById('addPrice').value;
                let itemExpiry = document.getElementById('addExpiry').value;
                let itemSupplier = document.getElementById('addSuppId').value;
                let itemCode = generateNumberCode(itemName);
                $.post('/warehouse_add', {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        item_name: itemName,
                        type: itemType,
                        batch_no: itemBatch,
                        location: itemLocation,
                        branch: itemBranch,
                        quantity: itemQuantity,
                        price: itemPrice,
                        expiry: itemExpiry,
                        user_id: itemSupplier,
                        item_code: itemCode
                    }).done(function(res) {
                        Swal.fire({
                            title: 'Success!',
                            text: 'Saving Success',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Close the modal
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
                        });
                        console.error(err);
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Close the modal
                            window.location.reload();
                        }
                    });

                // Swal.fire({
                //     title: 'Success!',
                //     text: 'Saving Success',
                //     icon: 'success',
                //     confirmButtonText: 'OK'
                // });


            });
        });





        // Handle Edit Form Submission
        $(document).ready(function() {
            $('.edit-submit-btn').click(function() {
                event.preventDefault();
                let itemId = document.getElementById('editId').value;
                let itemName = document.getElementById('editName').value;
                let itemType = document.getElementById('editType').value;
                let itemBatch = document.getElementById('editBatch').value;
                let itemLocation = document.getElementById('editLocation').value;
                let itemBranch = document.getElementById('editBranch').value;
                let itemQuantity = document.getElementById('editQuantity').value;
                let itemPrice = document.getElementById('editPrice').value;
                let itemExpiry = document.getElementById('editExpiry').value;

                $.post('/warehouse_update', {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        id: itemId,
                        item_name: itemName,
                        type: itemType,
                        batch_no: itemBatch,
                        location: itemLocation,
                        branch: itemBranch,
                        quantity: itemQuantity,
                        price: itemPrice,
                        expiry: itemExpiry,
                    })
                    .done(function(res) {
                        Swal.fire({
                            title: 'Success!',
                            text: 'Saving Success',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Close the modal
                                window.location.reload();
                            }
                        });;

                    })
                    .fail(function(err) {
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: err.responseJSON.message || "An error occurred",
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Close the modal
                                window.location.reload();
                            }
                        });;
                        console.error(err);
                    });
            });
        });


        $('.close-add, .close-edit', 'close').click(function() {
            event.preventDefault();

            $('#addForm')[0].reset();
            $('#editForm')[0].reset();
            $('#addModal').modal('hide');
            $('#editModal').modal('hide');
            $('#printModal').modal('hide');

        });

        $(window).click(function(event) {
            if ($(event.target).hasClass('modal')) {
                event.preventDefault();

                $('#addModal').modal('hide');
                $('#editModal').modal('hide');
                $('#printModal').modal('hide');
                $('#addForm')[0].reset();
                $('#editForm')[0].reset();

            }
        });
    });
</script>

@endsection