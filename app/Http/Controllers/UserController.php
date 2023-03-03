<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{

    public function updateSalary(Request $request)
    {
        $id=$request->id;

        $employee = User::findOrFail($id);
        $employee->salary = $request->salary;
        $employee->save();

        Artisan::call('salary:change', [
            'employee_id' => $id,
            'new_salary' => $employee->salary,
        ]);

        return response()->json([
            'message' => 'Salary updated successfully.',
        ]);


    }

    /**
     * update  user
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $id=$request->id;
        // Only authenticated users with the role of "admin" or "manager" can update user data
//        $this->authorize('update', User::class);

        // Find the user to update by ID
        $user = User::find($id);

        //  Validate the request data
        $validatedData = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'sometimes|nullable|string|min:8',
            'role' => 'required|string|in:admin,manager,employee',
            'manager_id' => 'nullable|integer|exists:users,id',
            'birthdate' => 'nullable|date',
            'salary' => 'nullable|numeric',
            'gender' => 'nullable|string|in:male,female,other',
            'hired_date' => 'nullable|date',
            'job_title' => 'nullable|string|max:255',
            'profile_logo' => 'nullable|images|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Update the user with the validated data
        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'birthdate' => $request->birthdate,
            'salary' => $request->salary,
            'gender' => $request->gender,
            'hired_date' => $request->hired_date,
            'job_title' => $request->job_title,
            'manager_id' => auth()->user()->id,
            'password' => bcrypt($request->password),
        ]);
        if ($validatedData->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validatedData->errors()
            ], 400);
        }
        if (isset($validatedData['password'])) {
            $user->password = Hash::make($validatedData['password']);
        }

        // Handle profile logo upload
        if ($request->hasFile('profile_logo')) {
            $profileLogoPath = $request->file('profile_logo')->store('public/profile_logos');
            $user->profile_logo = $profileLogoPath;
        }

        $user->save();

        return response()->json($user);
    }

    /**
     * Register a new user
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {

        $user = User::where('email', $request->email)->orWhere('name', 'like', "%{$request->name}%")->get();

        return response()->json([
            'status' => 'success',
            'message' => 'User retrieved successfully',
            'data' => $user
        ], 200);
    }


    /**
     * delete the  user
     *
     * @param User $user
     * @return \Illuminate\Http\Response
     */


    public function destroy(Request $request)
    {
       $user = User::find($request->id);
//      $this->authorize('delete', $user);
        $user->delete();

        return response()->json([
            'message' => 'user deleted successfully.',
        ]);
    }

}
