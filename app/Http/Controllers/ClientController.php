<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin;
class ClientController extends Controller
{
    use AuthenticatesUsers;
    public function client_login(){
     
        // return view('client.login');
        return view('agent.login');
    }
    public function logout(){
        if (Auth::guard('client')->check()) {
            // Update login_status
            Admin::where('id', Auth::guard('client')->user()->id)->update(['login_status' => 2]);
            $user = Admin::where(['id'=>Auth::guard('client')->user()->id])->update(['logout_at' => now()]);
            // Logout the user
            Auth::guard('client')->logout();
        }
    
        return redirect()->route('login');
    }
    public function client(){
        return view('client.home');
    }
    public function login_submit(Request $request){
    
        try {
            // $isDatabaseConnected = $this->checkDatabaseConnection();

            // if (!$isDatabaseConnected) {
            //     return response()->json(['success' => false, 'message' => 'Unable to establish a connection to the database']);
            // }
            $validator = Validator::make($request->all(), [
                'username' => 'required',
                'password' => 'required',
            ]);
    
            // Check if validation fails
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
    
            $input = $request->all();
    
            if (Auth::guard('client')->attempt(['username' => $input['username'], 'password' => $input['password']]) && Auth::guard('client')->user()->status == 1) {
                // Check if the role_id is not in [1, 2, 3, 4]
                $notallowedRoles = [1, 2, 3, 4];
                $userRoleId = Auth::guard('client')->user()->role_id;
    
                if (in_array($userRoleId, $notallowedRoles)) {
                   
                    return response()->json(['success' => false, 'message' => 'Please fill valid details']);
                }
                $lastlogin= Admin::where(['id'=>Auth::guard('client')->user()->id])->update(['last_login' => now()]);
                $user = Admin::where(['id'=>Auth::guard('client')->user()->id])->update(['login_status' => 1]);
                // Assuming you want to return a JSON response
                return response()->json(['success' => true, 'redirect' => route('client-home')]);
            } else {
                return response()->json(['success' => false, 'message' => 'Incorrect username or password']);
            }
        } catch (ValidationException $e) {
            // Handle validation errors
            $errors = $e->validator->errors()->toArray();
            return response()->json(['success' => false, 'message' => reset($errors)[0]]);
        } catch (\Throwable $th) {
            // Log or handle other exceptions
            return response()->json(['success' => false, 'message' => 'An error occurred during login']);
        }
    }
    // private function checkDatabaseConnection()
    // {
    //     try {
    //         // Use the default connection to check if the database is connected
    //         DB::select('select 1');
    //         return true;
    //     } catch (\Exception $e) {
    //         // An exception will be thrown if the database connection fails
    //         return false;
    //     }
    // }
}
