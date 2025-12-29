<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use Illuminate\Support\Facades\Auth; // ✨ Auth import karna zaroori hai
use Illuminate\Support\Facades\Hash; // <-- YE LINE ADD KAREIN TOP PAR

class ExpenseController extends Controller
{

    // 2. LIST, SEARCH, FILTER, CHART aur PAGINATION
    public function index(Request $request)
    {
        // Sirf Login wale user ka data nikalo
        $query = Expense::where('user_id', Auth::id())->latest();

        // 2. Date Filter Logic
        if ($request->filter == '7days') {
            $query->where('date', '>=', now()->subDays(7));
        } elseif ($request->filter == '30days') {
            $query->where('date', '>=', now()->subDays(30));
        } elseif ($request->filter == 'this_month') {
            $query->whereMonth('date', now()->month)
                  ->whereYear('date', now()->year);
        } elseif ($request->filter == 'last_month') {
            $query->whereMonth('date', now()->subMonth()->month)
                  ->whereYear('date', now()->subMonth()->year);
        }

        // Search Logic
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                  ->orWhere('category', 'like', "%$search%");
            });
        }

        // Date Filter
        if ($request->start_date && $request->end_date) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }

        // --- Chart aur Report ke liye SAARA data chahiye ---
        $allDataForChart = $query->clone()->get();
        
        $total = $allDataForChart->sum('amount');

        $report = $allDataForChart->groupBy('category')->map(function ($row) {
            return (object)['category' => $row->first()->category, 'total' => $row->sum('amount')];
        });

        $chartLabels = $report->pluck('category'); 
        $chartData = $report->pluck('total');

        // --- List ke liye PAGINATION chahiye (5 per page) ---
        $expenses = $query->paginate(5);

        return view('home', compact('expenses', 'total', 'report', 'chartLabels', 'chartData'));
    }

    // 3. CREATE FORM
    public function create()
    {
        return view('create');
    }

    // 4. STORE (Data Save karna)
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'amount' => 'required|numeric',
            'date' => 'required'
        ]);

        $expense = new Expense();
        $expense->user_id = Auth::id(); // ✨ Yahan User ID save ho rahi hai
        $expense->title = $request->title;
        $expense->amount = $request->amount;
        $expense->category = $request->category;
        $expense->date = $request->date;
        $expense->save();

        return redirect('/')->with('success', 'Kharcha add ho gaya!');
    }

    // 5. EDIT
    public function edit($id)
    {
        // Sirf apna data edit kar sakein
        $expense = Expense::where('user_id', Auth::id())->find($id);
        
        if(!$expense) {
            return redirect('/')->with('error', 'Ye kharcha aapka nahi hai!');
        }

        return view('edit', compact('expense'));
    }

    // 6. UPDATE
    public function update(Request $request, $id)
    {
        $expense = Expense::where('user_id', Auth::id())->find($id);
        
        if($expense) {
            $expense->title = $request->title;
            $expense->amount = $request->amount;
            $expense->category = $request->category;
            $expense->date = $request->date;
            $expense->save();
        }

        return redirect('/')->with('success', 'Kharcha update ho gaya!');
    }

    // 7. DELETE
    public function destroy($id)
    {
        $expense = Expense::where('user_id', Auth::id())->find($id);
        
        if ($expense) {
            $expense->delete();
        }

        return redirect('/')->with('success', 'Kharcha delete kar diya gaya!');
    }

    // --- Ye function naya hai ---
    public function profile()
    {
        $user = Auth::user(); // Logged in user ki details lo
        return view('profile', compact('user'));
    }

    // --- PROFILE UPDATE LOGIC ---
    public function updateProfile(Request $request)
    {
        $user = Auth::user(); // Jo user login hai use pakdo

        // 1. Validation
        $request->validate([
            'name' => 'required',
            // Email unique hona chahiye par khud ka email ignore karein
            'email' => 'required|email|unique:users,email,'.$user->id, 
            'password' => 'nullable|min:6|confirmed' // Password optional hai
        ]);

        // 2. Name aur Email Update
        $user->name = $request->name;
        $user->email = $request->email;
        $user->budget = $request->budget;

        // 3. Password Update (Sirf tab jab user ne naya password dala ho)
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save(); // Database me save karo

        return back()->with('success', 'Profile update ho gayi!');
    }

    // --- EXPORT TO CSV (EXCEL) ---
    public function export()
    {
        $filename = "my-expenses-" . date('Y-m-d') . ".csv";

        // Headers jo browser ko batate hain ki ye download file hai
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        // Column ke naam (Excel ki pehli line)
        $columns = ['Title', 'Amount', 'Category', 'Date'];

        // Data Streaming Callback
        $callback = function() use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns); // Pehli row likho

            // Database se saara data nikalo
            $expenses = Expense::where('user_id', Auth::id())->get();

            foreach ($expenses as $expense) {
                fputcsv($file, [
                    $expense->title, 
                    $expense->amount, 
                    $expense->category, 
                    $expense->date
                ]);
            }

            fclose($file);
        };

        // Download start karo
        return response()->stream($callback, 200, $headers);
    }
}