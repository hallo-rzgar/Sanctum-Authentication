<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use Illuminate\Http\Request;

class LeaveRequestController extends Controller
{
    public function index()
    {

        $leaves = '';
        if (auth()->user()->role == 'manager') {
            $leaves = LeaveRequest::where('manager_id', auth()->user()->id);
        } else {
            $leaves = LeaveRequest::where('user_id', auth()->user()->id);
        }

        return response()->json(['message' => 'Leave requests list', 'data' => $leaves], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'type_id' => 'required',
            'manager_id' => 'required',
        ]);

        $leaveRequest = new LeaveRequest;
        $leaveRequest->user_id = auth()->user()->id;
        $leaveRequest->type_id = $request->input('type_id');
        $leaveRequest->manager_id = auth()->user()->manager_id;
        $leaveRequest->status = 'pending';
        $leaveRequest->save();

        return response()->json(['message' => 'Leave request created successfully', 'data' => $leaveRequest], 201);
    }

    public function update(Request $request)
    {
        $id=$request->id;

        $request->validate([
            'type_id' => 'required',
            'manager_id' => 'required',
        ]);

        $leaveRequest = LeaveRequest::find($id);

        if (!$leaveRequest) {
            return response()->json(['message' => 'Leave request not found'], 404);
        }

        if ($leaveRequest->user_id != auth()->user()->id) {
            return response()->json(['message' => 'You are not authorized to update this leave request'], 401);
        }

        $leaveRequest->type_id = $request->type_id;
        $leaveRequest->manager_id = $request->manager_id;
        $leaveRequest->status = $request->status;
        $leaveRequest->save();

        return response()->json(['message' => 'Leave request updated successfully', 'data' => $leaveRequest], 200);
    }
    public function destroy(Request $request)
    {
        dd('ok');
        $leaveRequest = LeaveRequest::find($request->id);
        $leaveRequest->delete();

        return response()->json([
            'message' => 'request deleted successfully.',
        ]);
    }

}
