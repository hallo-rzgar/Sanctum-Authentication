<?php

namespace App\Http\Controllers;

use App\Models\LeaveType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LeaveTypeController extends Controller
{
    // Display a listing of the leave types
    public function index()
    {
        $leaveTypes = LeaveType::all();
        return response()->json(['success' => true, 'data' => $leaveTypes], 200);
    }


    // Store a newly created leave type in storage
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:leave_types|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->errors()], 400);
        }

        $leaveType = LeaveType::create($validator->validated());

        return response()->json(['success' => true, 'data' => $leaveType], 201);
    }



    // Remove the specified leave type from storage
    public function destroy(Request $request)
    {


        $leaveType = LeaveType::findOrFail($request->id);
        $leaveType->delete();
        return response()->json(['success' => true], 204);
    }
}
