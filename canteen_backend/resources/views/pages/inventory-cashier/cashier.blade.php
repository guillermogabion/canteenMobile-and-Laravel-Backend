@extends('layouts.app')

@section('content')
<div class="container-scroller">
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row mt-2">
                <div class="col-lg-8 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-3">

                                <table id="itemsTable">
                                    <thead>
                                        <tr>
                                            <th>Item Code</th>
                                            <th>Item Name</th>
                                            <th>Quantity</th>
                                            <th>Price</th>
                                            <th>Total Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end m-4">
                            <div>Total: <span class="font-bold" id="totalSum"></span></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="d-flex flex-column">
                        <div class="grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-lg-12 col-md-12">
                                            <form id="itemForm" method="GET">
                                                <div class="input-group">
                                                    <input type="text" name="itemCode" value="{{ request()->query('search') }}" id="itemCode" class="form-control" placeholder="Enter Item Code" oninput="validateInput(this)" required>
                                                    <span class="input-group-append">
                                                        <button class="btn btn-outline-secondary d-none" type="submit">Add</button>
                                                    </span>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-lg-12 col-md-12">
                                            <label class="card-title">Payment</label>
                                            <div class="">
                                                <input type="text" name="received" id="received" class="form-control" placeholder="Enter Payment Received" oninput="validateInputReceived(this)" required>
                                            </div>
                                            <div class="mt-2">
                                                <input type="text" name="change" id="change" value="0.00" class="form-control" placeholder="Change" style="font-size: 35px;" disabled>
                                            </div>
                                            <div class="d-flex justify-content-end mt-2">
                                                <button class="btn btn-outline-primary w-100 h-100" type="submit" id="payButton">Pay</button>
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
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="{{ asset('js/jquery.cookie.js') }}"></script>

<script>
    function removeRow(row) {
        $(row).remove();
        updateTotalSum();
    }

    function validateInput(input) {
        var baseFontSize = 20;
        var maxFontSize = 20;

        var length = input.value.length;

        var newSize = baseFontSize + length;

        newSize = Math.min(newSize, maxFontSize);

        input.style.fontSize = newSize + 'px';
        input.value = input.value.replace(/[^0-9*]/g, '');

        if ((input.value.match(/\*/g) || []).length > 1) {
            input.value = input.value.replace(/\*/g, '');
        }
    }

    function validateInputReceived(input) {

        var baseFontSize = 30;
        var maxFontSize = 30;

        var length = input.value.length;

        var newSize = baseFontSize + length;

        newSize = Math.min(newSize, maxFontSize);

        input.style.fontSize = newSize + 'px';
        input.value = input.value.replace(/[^\d.]/g, '');

        input.value = input.value.replace(/\.+/g, '.');

        var parts = input.value.split('.');

        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');

        input.value = parts.join('.');
    }

    function inputChange(input) {

        var baseFontSize = 35;
        var maxFontSize = 35;

        var length = input.value.length;

        var newSize = baseFontSize + length;

        newSize = Math.min(newSize, maxFontSize);

        input.style.fontSize = newSize + 'px';
    }

    function updateWarehouseQuantities() {
        var items = [];
        var rows = $('#itemsTable tbody tr');
        var received = $('#totalSum').text();


        rows.each(function(index, row) {
            var itemCode = $(row).find('td:nth-child(1)').text();
            var quantity = parseInt($(row).find('.quantity').text());

            items.push({
                itemCode: itemCode,
                quantity: quantity
            });
        });

        $.post('{{ route("update-warehouse-quantities") }}', {
            _token: $('meta[name="csrf-token"]').attr('content'),
            items: items
        }, function(response) {
            console.log('Warehouse quantities updated successfully:', response);

            // Now, call the payment route to save the received payment
            $.post('{{ route("payment") }}', {
                _token: $('meta[name="csrf-token"]').attr('content'),
                received: received
            }, function(paymentResponse) {
                console.log('Payment received saved successfully:', paymentResponse);
                Swal.fire({
                    title: 'Success!',
                    text: 'Payment Success',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.reload();
                    }
                });
            }).fail(function(paymentXhr, paymentStatus, paymentError) {
                console.error('Error saving payment received:', paymentError);
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: paymentXhr.responseJSON.message || "An error occurred",
                    confirmButtonText: 'OK'
                });
            });
        }).fail(function(xhr, status, error) {
            console.error('Error updating warehouse quantities:', error);
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: xhr.responseJSON.message || "An error occurred",
                confirmButtonText: 'OK'
            });
        });
    }




    document.addEventListener('DOMContentLoaded', function() {
        var form = document.getElementById('itemForm');
        var submitButton = form.querySelector('button[type="submit"]');

        submitButton.addEventListener('click', function(event) {
            event.preventDefault();
            var inputValue = document.getElementById('itemCode').value;

            console.log('Input value:', inputValue);

            var parts = inputValue.split('*');
            var quantity = 1;
            var itemCode = inputValue;

            if (parts.length > 1) {
                quantity = parseInt(parts[0]) || 1;
                itemCode = parts[1];
            }

            updateTotalPrice(quantity);

            $.get('{{ route("cashier-search") }}', {
                search: itemCode
            }, function(data) {
                console.log('Search result:', data);

                if (data.length > 0) {
                    var itemCode = data[0].item_code;

                    var existingRow = null;

                    $('#itemsTable tbody tr').each(function() {
                        if ($(this).find('td:first').text() === itemCode) {
                            existingRow = $(this);
                            return false;
                        }
                    });

                    if (existingRow) {
                        var quantityCell = existingRow.find('.quantity');
                        var existingQuantity = parseInt(quantityCell.text());
                        quantityCell.text(existingQuantity + quantity);
                        updateTotalSum();

                    } else {
                        $('#itemsTable tbody').append('<tr><td>' + itemCode + '</td><td>' + data[0].item_name + '</td><td><button class="btn btn-sm btn-primary increment-decrement" onclick="decrementQuantity(this)">-</button><span class="quantity">' + quantity + '</span><button class="btn btn-sm btn-primary increment-decrement" onclick="incrementQuantity(this)">+</button></td><td>' + data[0].price + '</td><td>' + (data[0].price * quantity) + '</td><td><button class="btn btn-sm btn-danger remove-row">Remove</button></td></tr>');
                        updateTotalSum();

                    }

                    document.getElementById('itemCode').value = "";
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Item not found',
                        text: 'The item code you entered does not exist.',
                        confirmButtonText: 'OK'
                    });
                }

                document.getElementById('itemCode').focus();
            });

            function filterInput(event) {
                var input = event.target.value;
                event.target.value = input.replace(/[^0-9*]/g, '');
            }
            $('#itemsTable tbody').on('click', '.remove-row', function() {
                var row = $(this).closest('tr');
                removeRow(row);
            });
        });

        function sumTotalPrice() {
            var totalPriceSum = 0;
            $('#itemsTable tbody tr').each(function() {
                var totalPrice = parseFloat($(this).find('td:nth-child(5)').text());
                totalPriceSum += totalPrice;
            });
            return totalPriceSum;
        }

        function updateTotalSum() {
            var totalSum = sumTotalPrice();
            $('#totalSum').text(totalSum.toFixed(2));
        }


        updateTotalSum();

        function calculateChange() {
            var received = parseFloat(document.getElementById('received').value.replace(/,/g, ''));
            var totalSum = parseFloat(document.getElementById('totalSum').innerText.replace(/,/g, ''));
            var change = received - totalSum;

            // Prevent displaying negative values
            if (change >= 0) {
                // Display the result with thousand separators and two decimal places
                document.getElementById('change').value = change.toLocaleString(undefined, {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            } else {
                // If change is negative, display zero
                document.getElementById('change').value = "0.00";
            }
        }

        // Call calculateChange whenever the received amount changes
        $('#received').on('input', calculateChange);

        $('#payButton').on('click', function() {
            // Call the function to update warehouse quantities
            updateWarehouseQuantities();
        });
    });

    function incrementQuantity(button) {
        var quantityCell = $(button).siblings('.quantity');
        var quantity = parseInt(quantityCell.text());
        quantityCell.text(quantity + 1);
        updateTotalPrice(quantityCell.closest('tr'));
        updateTotalSum();
    }

    function decrementQuantity(button) {
        var quantityCell = $(button).siblings('.quantity');
        var quantity = parseInt(quantityCell.text());
        if (quantity > 1) {
            quantityCell.text(quantity - 1);
            updateTotalPrice(quantityCell.closest('tr'));
            updateTotalSum();

        }
    }

    function updateTotalSum() {
        var totalPriceSum = 0;
        $('#itemsTable tbody tr').each(function() {
            var totalPrice = parseFloat($(this).find('td:nth-child(5)').text());
            totalPriceSum += totalPrice;
        });
        $('#totalSum').text(totalPriceSum.toFixed(2));
    }

    function updateTotalPrice(row) {
        var $row = $(row);
        var quantity = parseInt($row.find('.quantity').text());
        var price = parseFloat($row.find('td:nth-child(4)').text());
        var totalPrice = quantity * price;
        $row.find('td:nth-child(5)').text(totalPrice);
        updateTotalSum();
    }
</script>




@endsection