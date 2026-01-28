@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card">
        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0">កែប្រែប្រភេទទំនិញ: {{ $category->name }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('categories.update', $category->id) }}" method="POST">
                @csrf
                @method('PUT') {{-- សំខាន់ណាស់សម្រាប់ Update --}}

                <div class="mb-3">
                    <label>ឈ្មោះប្រភេទទំនិញ</label>
                    <input type="text" name="name" value="{{ $category->name }}" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary">រក្សាទុកការកែប្រែ</button>
                <a href="{{ route('categories.index') }}" class="btn btn-secondary">ត្រឡប់ក្រោយ</a>
            </form>
        </div>
    </div>
</div>
@endsection