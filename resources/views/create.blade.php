@extends('layout')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="card">
            <div class="card-header bg-dark text-white">
                <h4>Naya Kharcha Add Karein</h4>
            </div>
            <div class="card-body">
                <form action="/save-expense" method="POST">
                    @csrf <div class="mb-3">
                        <label class="form-label">Kharcha Kis Cheez Ka?</label>
                        <input type="text" name="title" class="form-control" placeholder="Jaise: Pizza, Petrol..." required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kitne Rupaye?</label>
                        <input type="number" name="amount" class="form-control" placeholder="500" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <select name="category" class="form-select">
                            <option value="Food">Khana (Food)</option>
                            <option value="Travel">Aana-Jaana (Travel)</option>
                            <option value="Shopping">Shopping</option>
                            <option value="Bills">Bills & Recharge</option>
                            <option value="Other">Anya (Other)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Taarikh (Date)</label>
                        <input type="date" name="date" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Add Expense</button>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection