@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h4>Add New Product
                <a href="{{ route('products.index') }}" class="btn btn-danger float-end">Back</a>
            </h4>
        </div>
        <div class="card-body">
            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Barcode</label>
                        <input type="text" name="barcode" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Category</label>
                        <select name="category_id" class="form-control" required>
                            <option value="">-- Select Category --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Supplier</label>
                        <select name="supplier_id" class="form-control" required>
                            <option value="">-- Select Supplier --</option>
                            @foreach($suppliers as $sup)
                                <option value="{{ $sup->id }}">{{ $sup->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Cost Price</label>
                        <input type="number" step="0.01" name="cost_price" class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Sale Price</label>
                        <input type="number" step="0.01" name="sale_price" class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Quantity</label>
                        <input type="number" name="qty" class="form-control" required>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label>Image</label>
                        <input type="file" name="image" class="form-control">
                    </div>
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">Save Product</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection