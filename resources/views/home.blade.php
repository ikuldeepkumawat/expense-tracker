@extends('layout')

@section('content')

<div class="row">
    <div class="col-md-12">
        
        <div class="row align-items-end mb-4 gap-2 gap-md-0">
            
            <div class="col-md-6">
                <h3>📋 Mere Kharche</h3>
            </div>

            
            <div class="col-md-6 text-end">
                <a href="{{ route('export') }}" class="btn btn-success me-2">
                   📥 Download Report
                </a>
                <a href="/add" class="btn btn-primary">+ Add New</a>
            </div>

           

            <div class="col-md-12">
                <form action="/" method="GET" class="row g-2">
                    
                    <div class="col-md-3">
                        <label class="small text-muted">Kab Se (From)</label>
                        <input type="date" name="start_date" class="form-control form-control-sm" value="{{ request('start_date') }}">
                    </div>

                    <div class="col-md-3">
                        <label class="small text-muted">Kab Tak (To)</label>
                        <input type="date" name="end_date" class="form-control form-control-sm" value="{{ request('end_date') }}">
                    </div>

                    <div class="col-md-4">
                        <label class="small text-muted">Search</label>
                        <div class="input-group input-group-sm">
                            <input type="text" name="search" class="form-control" placeholder="Pizza, Food..." value="{{ request('search') }}">
                            <button type="submit" class="btn btn-primary">Filter</button>
                        </div>
                    </div>

                    <div class="col-md-1 d-flex align-items-end">
                        @if(request('start_date') || request('search'))
                            <a href="/" class="btn btn-sm btn-outline-danger w-100">❌</a>
                        @endif
                    </div>
                </form>
            </div>
            @php
                $budget = Auth::user()->budget;
                $percentage = 0;
                $barColor = 'success'; // Default Green (Hara)
                $msg = 'Shaandaar! Aap budget ke andar hain. ✅';

                if ($budget > 0) {
                    $percentage = ($total / $budget) * 100;

                    // Logic: Color kab badalna hai?
                    if ($percentage >= 100) {
                        $barColor = 'danger'; // Red (Khatra)
                        $msg = '⚠️ Warning: Aapne Budget Cross kar diya hai!';
                    } elseif ($percentage >= 75) {
                        $barColor = 'warning'; // Yellow (Savdhan)
                        $msg = 'Dhyan dein! Budget khatam hone wala hai.';
                    }
                }
            @endphp

            @if($budget > 0)
            <div class="card my-4 shadow-sm border-{{ $barColor }}">
                <div class="card-body">
                    
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="mb-0 text-{{ $barColor }} fw-bold">{{ $msg }}</h5>
                        <span class="fw-bold">Kharcha: ₹{{ $total }} / Target: ₹{{ $budget }}</span>
                    </div>

                    <div class="progress" style="height: 25px;">
                        <div class="progress-bar bg-{{ $barColor }} progress-bar-striped progress-bar-animated" 
                            role="progressbar" 
                            style="width: {{ $percentage > 100 ? 100 : $percentage }}%">
                            {{ round($percentage) }}% Used
                        </div>
                    </div>

                </div>
            </div>
            @endif
        </div>
         <div class="row mb-4">
    
    <div class="col-md-4">
        <form action="/" method="GET" id="filterForm">
            <input type="hidden" name="search" value="{{ request('search') }}">
            
            <select name="filter" class="form-select border-primary fw-bold text-primary" onchange="document.getElementById('filterForm').submit()">
                <option value="">📅 All Time (Sab Dikhao)</option>
                <option value="7days" {{ request('filter') == '7days' ? 'selected' : '' }}>Last 7 Days</option>
                <option value="30days" {{ request('filter') == '30days' ? 'selected' : '' }}>Last 30 Days</option>
                <option value="this_month" {{ request('filter') == 'this_month' ? 'selected' : '' }}>This Month</option>
                <option value="last_month" {{ request('filter') == 'last_month' ? 'selected' : '' }}>Last Month</option>
            </select>
        </form>
    </div>

    <div class="col-md-8">
        <form action="/" method="GET">
            <div class="input-group">
                <input type="hidden" name="filter" value="{{ request('filter') }}">
                
                <input type="text" name="search" class="form-control" placeholder="Search expenses..." value="{{ request('search') }}">
                <button class="btn btn-outline-primary" type="submit">🔍 Search</button>
            </div>
        </form>
    </div>

</div>
        <div class="row mb-4">
            <div class="col-12">
                <h5>📊 Kharcho ka Hisaab (Category Wise)</h5>
            </div>

            @foreach($report as $r)
            <div class="col-md-3 col-6 mb-2">
                <div class="card text-white 
                    {{ $r->category == 'Food' ? 'bg-success' : '' }}
                    {{ $r->category == 'Travel' ? 'bg-info' : '' }}
                    {{ $r->category == 'Shopping' ? 'bg-warning' : '' }}
                    {{ $r->category == 'Bills' ? 'bg-danger' : '' }}
                    {{ $r->category == 'Other' ? 'bg-secondary' : '' }}
                ">
                    <div class="card-body p-2 text-center">
                        <small>{{ $r->category }}</small>
                        <h5 class="m-0">₹{{ $r->total }}</h5>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="row mt-4 mb-5 justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="m-0 text-center">📊 Kharcho ka Graph</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="expenseChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card">
            <div class="card-body">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Date</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Amount (₹)</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($expenses as $data)
                        <tr>
                            <td>{{ date('d M Y', strtotime($data->date)) }}</td>
                            <td>{{ $data->title }}</td>
                            <td>
                                <span class="badge bg-secondary">{{ $data->category }}</span>
                            </td>
                            <td class="fw-bold">₹{{ $data->amount }}</td>
                            <td>
                                <div class="d-flex gap-2"> <a href="/edit/{{ $data->id }}" class="btn btn-warning btn-sm">✏️ Edit</a>

                                    <form action="/delete/{{ $data->id }}" method="POST" id="delete-form-{{ $data->id }}">
                                        @csrf
                                        @method('DELETE')
                                        
                                        <button type="button" onclick="confirmDelete({{ $data->id }})" class="btn btn-danger btn-sm">
                                            🗑️ Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        @if($expenses->isEmpty())
                        <tr>
                            <td colspan="4" class="text-center text-muted">Abhi koi kharcha nahi joda hai.</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            
            <div class="card-footer bg-light text-end">
                <h4>Total Kharcha: <span class="text-danger">₹{{ $total }}</span></h4>
            </div>
        </div>

    </div>
</div>
</div> </div> <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    var labels = @json($chartLabels);
    var data = @json($chartData);

    const ctx = document.getElementById('expenseChart');

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                label: 'Kharcha (₹)',
                data: data,
                backgroundColor: [
                    '#198754', '#0dcaf0', '#ffc107', '#dc3545', '#6c757d', '#6610f2'
                ],
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });

    function confirmDelete(id) {
        Swal.fire({
            title: 'Kya aap sure hain?',
            text: "Ye record hamesha ke liye delete ho jayega!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Haan, Delete karo!',
            cancelButtonText: 'Nahi'
        }).then((result) => {
            if (result.isConfirmed) {
                // Agar user 'Haan' bole, toh form submit karo
                document.getElementById('delete-form-' + id).submit();
            }
        })
    }
</script>
@endsection