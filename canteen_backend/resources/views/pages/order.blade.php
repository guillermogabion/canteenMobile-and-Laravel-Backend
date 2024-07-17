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
                                        <label for="" class="card-title">Orders</label>
                                    </div>
                                    <div class="col-lg-6 col-md-6 d-flex justify-content-end">
                                        <button type="button" class="btn btn-primary add-item" data-toggle="modal" data-target="#addModal">Add Meal</button>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-lg-6 col-md-6">
                                        <form method="GET" action="{{ route('order') }}">
                                            <div class="input-group">
                                                {{-- <input type="text" name="search" class="form-control" placeholder="Search file..." value="{{ request()->query('search') }}">--}}
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
                                                <td>{{ $item->user->name }}</td>
                                                <td>{{ $item->meal->name }}</td>
                                                <td>{{ $item->total }}</td>
                                                <td>{{ $item->type }}</td>
                                                <td>{{ $item->user->address }}</td>
                                                <td>{{ $item->deliveryStatus }}</td>
                                                {{-- <td>
                                                    <button type="button" class="btn btn-outline-secondary btn-rounded btn-icon edit-btn" data-toggle="modal" data-target="#editModal" data-id="{{ $item->id }}" data-name="{{ $item->name }}" data-description="{{ $item->description }}" data-image="{{ $item->image }}" data-price="{{$item->price}}">
                                                <i class="mdi mdi-lead-pencil text-primary"></i>
                                                </button>
                                                <form action="{{ route('class.updateStatus', $item->id) }}" method="POST">
                                                    @csrf
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" id="statusSwitch{{ $item->id }}" name="status" value="inactive" {{ $item->status == 'active' ? 'checked' : '' }} onchange="this.form.submit()">
                                                        <input class="form-check-input" type="checkbox" id="statusSwitch{{ $item->id }}" name="status" value="active" {{ $item->status == 'active' ? 'checked' : '' }} onchange="this.form.submit()">
                                                    </div>
                                                </form>
                                                </td> --}}
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="9" class="alert alert-info">No Items</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                {{-- <div class="d-flex justify-content-end">
                                    {{ $items->appends(request()->query())->links('vendor.pagination.bootstrap-4') }}
                            </div> --}}
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

@endsection