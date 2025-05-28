<?php

namespace Mkamel\StarterCoreKit\App\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Mkamel\StarterCoreKit\App\Models\ExceptionModel;
use App\Http\Controllers\Controller;

class ExceptionController extends Controller
{
    public function index()
    {
        $exceptions = ExceptionModel::latest('created_at')->paginate(2);
        return view('starter-core-kit::exceptions', compact('exceptions'));
    }

    public function store(Request $request)
    {
        dd($request->all());
        $request->validate([
            'exception' => 'required',
            'message' => 'required|array',
            'message.*' => 'required|string',
        ]);
        $data = ExceptionModel::create($request->validated());
        dd($data);
        return redirect()->route('exceptions.index')->with('success', 'Exception created successfully!');
    }

}
