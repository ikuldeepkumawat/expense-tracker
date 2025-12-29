@extends('layout')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-warning text-dark">
                <h4>Kharcha Edit Karein ✏️</h4>
            </div>
            <div class="card-body">
                
                <form action="/update/{{ $expense->id }}" method="POST">
                    @csrf
                    @method('PUT') <div class="mb-3">
                        <label>Title</label>
                        <input type="text" name="title" class="form-control" value="{{ $expense->title }}" required>
                    </div>

                    <div class="mb-3">
                        <label>Amount</label>
                        <input type="number" name="amount" class="form-control" value="{{ $expense->amount }}" required>
                    </div>

                    <div class="mb-3">
                        <label>Category</label>
                        <select name="category" class="form-select">
                            <option value="Food" {{ $expense->category == 'Food' ? 'selected' : '' }}>Food</option>
                            <option value="Travel" {{ $expense->category == 'Travel' ? 'selected' : '' }}>Travel</option>
                            <option value="Shopping" {{ $expense->category == 'Shopping' ? 'selected' : '' }}>Shopping</option>
                            <option value="Bills" {{ $expense->category == 'Bills' ? 'selected' : '' }}>Bills</option>
                            <option value="Other" {{ $expense->category == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Date</label>
                        <input type="date" name="date" class="form-control" value="{{ $expense->date }}" required>
                    </div>

                    <button type="submit" class="btn btn-warning w-100">Update Karein</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection