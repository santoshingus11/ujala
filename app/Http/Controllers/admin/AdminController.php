<?php

namespace App\Http\Controllers\admin;

use App\Helpers\QueryHelper;
use App\Models\User;
use App\Models\Admin;
use App\Models\TennisMatchScore;
use App\Models\FootballMatchScore;
use App\Models\CricketMatchScore;
use App\Models\CricketMatchScoreDetail;
use App\Models\CreditLog;
use App\Models\BetHistory;
use App\Models\Passwordlogs;
use App\Models\PositionTaking;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use App\Rules\CustomRule;
use Jenssegers\Agent\Agent;

class AdminController extends Controller
{

    use AuthenticatesUsers;
    public function update_status(Request $request){
        // dd($request->type);
        if($request->type=='bet'){
       $data=[
        'id' => $request->id,
        'bet_status' => $request->status,
       ];
    }else{
        $data=[
            'id' => $request->id,
            'transfer_status' => $request->status,
           ];
    }
       Admin::where('id',$request->id)->update($data);
       if($request->type=='bet'){
       return response()->json(['message' => 'Bet Status updated successfully']);
    }else{
        return response()->json(['message' => 'Transfer Status updated successfully']);
    }
}
    public function admin_login(){
    //   return Hash::make('123456');
       
        return view('agent.login');
    }
    public function logout_all(Request $request){
        return redirect()->back();
    }
    // public function logout_all(Request $request){
    //     try{       
    //         $sessions = glob(storage_path("framework/sessions/*"));
    //         foreach($sessions as $file){
    //           if(is_file($file))
    //             unlink($file);
    //         }
    //         $agent = new Agent();
    //         $browserName = $agent->browser();
    //         $browserVersion = $agent->version($browserName);
    //         $platform = $agent->platform();
    //         $agentInfo = "$browserName $browserVersion ($platform)";
    //         Admin::where('login_status',1)->update(['logout_at' => date('Y-m-d H:i:s'),'login_status'=>2]);
    //         $username = $request->username;
    //         $ipAddress = request()->ip();
    //         $agent_info = $agentInfo;
    //         $new_password = $request->password;
    //         $fieldType = filter_var($username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
    //         if (Auth::guard('agent')->attempt(array($fieldType => $username, 'password' => $new_password))) {    
    //           Admin::where('id',Auth::guard('agent')->user()->id)->update(['ip_address'=>$ipAddress,'last_login'=>date('Y-m-d H:i:s'),'login_status'=>1,'agent_info'=>$agent_info]);          
    //           return redirect()->route('online-user')->with('success','Successfully Updated');
    //        }
    //        return redirect()->back()->with('success','Successfully Logout');
    //     }catch(\Throwable $th){
    //      //  return $th->getMessage();
    //        return redirect()->back()->with('success','Something Went Wrong');
    //     }
       
    // }
    // public function logout_all(Request $request){
    //     // return 5;
    //     if(Auth::guard('agent')->user()->user_access=='1'){
    //         $userid=Auth::guard('agent')->user()->admin_id;
    //     }else{
    //         $userid=Auth::guard('agent')->user()->id;
    //     }
    

    //     // Update login_status and logout_at for all users except the current one
    //     $logout=Admin::where('login_status', '1')
    //         ->where('id', '!=', $userid)
        
    //         ->update([
    //         'login_status' => 2,
    //         'logout_at' => now(),
    //     ]);
     

    //     return response()->json(['message' => 'All users have been logged out.']);
    // }
    public function agent(Request $request){
    
        return view('agent.dashboard');
    }
    public function agent_dashboard(){
        return view('agent.dashboard');
    }
    public function admin_detail(Request $request){
       $id=$request->id;     
        $admindata=Admin::where('id',$id)->first(); 
        return view('agent.formadminmodal',compact('admindata'));
    }
   
   
    public function password_update(Request $request)
{   
    // return $request->old_password;
    $validator = Validator::make($request->all(), [
        'old_password' => 'required|string',
        'new_password' =>  [
            'required',
            'string',
            'min:8',
            new CustomRule,           
        ],
      
        'confirm_password' => 'required|string|same:new_password',
    ]);
    $validator->sometimes('confirm_password', 'different:new_password', function ($input) {
       
        return $input->new_password !== $input->confirm_password;
    });

    // Check if validation fails
    if ($validator->fails()) {
        $errors = $validator->errors();

        return response()->json(['errors' => $errors], 422);
    }
    $user = Auth::guard('agent')->user();
    if(Auth::guard('agent')->user()->user_access == '1') {
        $userid=Auth::guard('agent')->user()->admin_id;
    }else{
        $userid=Auth::guard('agent')->user()->id;
    }
    if (!$user || !Hash::check($request->old_password, $user->password)) {
        return response()->json(['error' => 'Request failed. Old Password is incorrect.'], 401);
    }else{
        $passwordupdate=[
            'password' => bcrypt($request->new_password),
        ];
       
        
            $passwordlog = [
                'user_id' => $user->id,
                'change_by_user' => $user->id,
                'remarks' => 'Password updated by Self',
                'created_at' => now(),
            ];
            // dd($passwordlog);
            $insertpassword=Passwordlogs::insert($passwordlog);
          
        
        Admin::where('id',$user->id)->update($passwordupdate);
      
    }
    $user = Auth::guard('agent')->user();
    // $passwordlogs=[
    //     'user_id' => $userid,
    //     'change_by_user'=>$userid,
    //     'remarks' =>1,
    //     'created_at' =>now()
    // ];
    // $insertlog=Passwordlogs::insertorcreate($passwordlogs);
    return response()->json(['message' => 'Password updated successfully']);
}
public function agent_listing(Request $request){

    $admin_ids=QueryHelper::adminids();

    if ($request->has('id')) {
        $admin = Admin::find($request->id);        
        if ($admin) {  
            $data = Admin::where('admin_id', $admin->id)
            ->get();
                
        }             
       
    } else {
            $data = Admin::whereIn('id', $admin_ids)->get() ?? [];
        }

    return view('agent.agent-listing',compact('data'));
  }
  public function admin_add_submit(Request $request){
    // return $request->login_id;
    $validator= Validator::make($request->all(),[
        'username' =>'required|string',
        'password'=>[ 'required',
        'string',
        'min:8',
        new CustomRule,

    ],
    'confirm_password'=>'required|string|same:password',
    'status' => 'required|in:0,1',
    'credit_limit' => 'required|numeric',
    'message' => 'nullable|string',
    'cricket' => 'nullable|numeric',
    'football' => 'nullable|numeric',
    'tennis' => 'nullable|numeric',
    'horse_racing' => 'nullable|numeric',
    'greyhound_racing' => 'nullable|numeric',
    'casino' => 'nullable|numeric',
    'other' => 'nullable|numeric',
    ], [
        'numeric' => 'The :attribute must be a number.',
        'regex' => 'The :attribute must contain only numeric values.',
    ]);
    
    
    $validator->sometimes('confirm_password', 'different:new_password', function ($input) {
       
        return $input->new_password !== $input->confirm_password;
    });
  
    if ($validator->fails()) {
        $errors = $validator->errors();
        return response()->json(['errors' => $errors], 422);
    }
    $existingloginId=Admin::where('login_id', $request->login_id)->first();
    $existingUser = Admin::where('username', $request->username)->first();
    if ($existingloginId) {
        return response()->json(['error' => 'Login Id Already Exist!', 'field' => 'loginId'], 402);
    }
    if ($existingUser) {
        return response()->json(['error' => 'Username Already Exist', 'field' => 'username'], 401);
    }

    $user = Auth::guard('agent')->user();
    $adminId = null; 

    if(Auth::guard('agent')->user()->user_access==1){

        $agent_admin_id=Auth::guard('agent')->user()->admin_id;
        $userid= Auth::guard('agent')->user()->admin_id;
    }else{
        $agent_admin_id=Auth::guard('agent')->user()->id;
        $userid= Auth::guard('agent')->user()->id;
    }

    $data = [
        'username'    => $request->username,
        'password'    => bcrypt($request->password),
        'status'      => $request->status,
        'credit_limit'=> $request->credit_limit,
        'message'     => $request->message,
        'login_id'    => $request->login_id,
        'role_id'     => $user->role_id + 1,
        'admin_id'    => $agent_admin_id,
        'admin_role'  => $user->role_id,
        'created_at'  => now(),
    ];

    $admin = Admin::create($data);
    $id = $admin->id;

    // if ($admin) {
    //     $admin = Admin::where('username', $request->username)->first();
    //     $adminId = $admin->id;
    // }

    // if (!$adminId) {
    //     return response()->json(['error' => 'Failed to insert data'], 500);
    // }

    $credit=[
        'credit_new_value' => $request->credit_limit,
        'credit_from' =>$userid,
        'credit_to' => $id,
        'stake' =>$request->credit_limit,
        
    ];
    $credit_log=CreditLog::insert($credit);
    
    $position = [
        'cricket'           => $request->cricket,
        'football'          => $request->football,
        'admin_id'          => $id,
        'tennis'            => $request->tennis,
        'horse_racing'      => $request->horse_racing,
        'greyhound_racing'  => $request->greyhound_racing,
        'casino'            => $request->casino,
        'other'             => $request->other,
        'created_at'        => now(),
    ];

    $positionadd = PositionTaking::insert($position);

    if ($positionadd) {
        return response()->json(['success' => 'Added Successfully.']);
       
        return response()->json(['success' => true, 'redirect' => route('super-master-listing')]);
    }else{
        return response()->json(['error' => 'Failed to insert data'], 500);
    }

   
}
    public function admin_submit(Request $request){
        // $agent = new Agent();
        // $browserName = $agent->browser();
        // $browserVersion = $agent->version($browserName);
        // $platform = $agent->platform();
        // $agentInfo = "$browserName $browserVersion ($platform)";
        // $ipAddress = $request->ip();

        // Check for forwarded headers to get the actual client IP
   
       
        try {
           
            // Validate the incoming request data
            $validator = Validator::make($request->all(), [
                'username' => 'required',
                'password' => 'required',
            ]);
    
            // Check if validation fails
            if ($validator->fails()) {
                $errors = $validator->errors()->toArray();
                $errorMessages = [];
    
                foreach ($errors as $field => $messages) {
                    foreach ($messages as $message) {
                        $errorMessages[] = $message;
                    }
                }
    
                throw new ValidationException($validator, response()->json(['success' => false, 'message' => implode(' ', $errorMessages)], 422)); // Use 422 Unprocessable Entity status code for validation errors
            }
    
            $input = $request->all();
    
          if (Auth::guard('agent')->attempt(['username' => $input['username'], 'password' => $input['password']]) && Auth::guard('agent')->user()->status == 1) {
            $allowedRoles = [1,2,3,4];
            $userRoleId = Auth::guard('agent')->user()->role_id;
            $userId = Auth::guard('agent')->user()->id;
            
            $userdata = Auth::guard('agent')->user(); // Use Auth::guard('web')->user() directly
            // return $userdata;
            if (in_array($userRoleId, $allowedRoles)) {
                // $this->updateLoginStatus($userId);
                $lastlogin= Admin::where(['id'=>Auth::guard('agent')->user()->id])->update(['last_login' => now()]);
                $user = Admin::where(['id'=>Auth::guard('agent')->user()->id])->update(['login_status' => 1]);
                // $ip = Admin::where(['id'=>Auth::guard('agent')->user()->id])->update(['ip_address' => $ipAddress]);
                // $platforms = Admin::where(['id'=>Auth::guard('agent')->user()->id])->update(['agent_info' => $agentInfo]);
                return response()->json(['success' => true, 'redirect' => route('admin-cricket-game-list')]);
            }
            return response()->json(['success' => false, 'message' => 'Please fill valid details']);
        }else{
            return response()->json(['success' => false, 'message' => 'Incorrect Username or Password']);
        }

        
    } catch (ValidationException $e) {
            // Handle validation errors
            return $e->getResponse();
        } catch (\Throwable $th) {
            // return $th->getMessage();
            return response()->json(['success' => false, 'message' => 'An error occurred during login']);
        }
    }
    public function logout() {
        if (Auth::guard('agent')->check()) {
            // Update login_status
            Admin::where('id', Auth::guard('agent')->user()->id)->update(['login_status' => 2]);
            $user = Admin::where(['id'=>Auth::guard('agent')->user()->id])->update(['logout_at' => now()]);
            // Logout the user
            Auth::guard('agent')->logout();
        }
        // Redirect to the admin login page
        return redirect()->route('admin_login');
    }

    public function super_master(Request $request){

        $admin_ids = QueryHelper::adminids();

    if ($request->has('id')) {
        $admin = Admin::find($request->id);

        if ($admin) {
            $data = Admin::where('admin_id', $admin->id)->paginate(30); // Adjust the pagination limit as needed
        }
    } else {
        $user = Auth::guard('agent')->user();

        if ($user) {
            $data = Admin::whereIn('id', $admin_ids)->paginate(30); // Adjust the pagination limit as needed
        }
    }

    return view('agent.super-master-listing', compact('data'));
}

    public function super_master_search(Request $request){
        // return 5;
        if(Auth::guard('agent')->user()->user_access==1){
            $userRoleId =  Auth::guard('agent')->user()->role_id + 1;
            $adminRoleId =  Auth::guard('agent')->user()->role_id;
            $adminId =  Auth::guard('agent')->user()->admin_id;
    
            $data = Admin::where('role_id', $userRoleId)
                ->where('admin_role', $adminRoleId)
                ->where('admin_id', $adminId);

        }else{
        $userRoleId =  Auth::guard('agent')->user()->role_id + 1;
        $adminRoleId =  Auth::guard('agent')->user()->role_id;
        $adminId =  Auth::guard('agent')->user()->id;

        $data = Admin::where('role_id', $userRoleId)
            ->where('admin_role', $adminRoleId)
            ->where('admin_id', $adminId);
        }
        if ($request->selected_status != '') {
            $searchvalue = $request->selected_status;
           // DB::getQueryLog();
            $data = $data->where('admins.status', 'like', '%' . $searchvalue . '%');
            //DB::getQueryLog();
            // return $data;
        }
        
        if ($request->agency_member_name != '') {
            $searchvalue = $request->agency_member_name;
            $data = $data->where('admins.first_name', 'like', '%' . $searchvalue . '%');
        }
        
        if ($request->login_name != '') {
            $searchvalue = $request->login_name;
            $data = $data->where('admins.username', 'like', '%' . $searchvalue . '%');
        }
        $data=$data->paginate();
        return view('agent.super-master-listing-search',compact('data'));
    }
 

   

   
   
  
   


    public function new_agent(){
        return view('agent.createagent');
    }
    public function casino(){
        return view('agent.casino-report');
    }
    public function user_transfer(){
        return view('agent.user-transfer-statement');
    }
  

 
    public function user_listing(Request $request){
        $userRoleId = Auth::guard('agent')->user()->role_id;
        if (Auth::guard('agent')->user()->user_access == 1) {
            $userId = Auth::guard('agent')->user()->admin_id;
            $mainid = Auth::guard('agent')->user()->id;
            $query = Admin::where('role_id', $userRoleId)
                    ->where('id', '!=' , $mainid)
                    ->where('admin_id', $userId);
        } else {
            $userId = Auth::guard('agent')->user()->id;
            $query = Admin::where('role_id', $userRoleId)
            ->where('id', '!=' , $userId)
            ->where('admin_id', $userId);
           
        }
        // Exclude main super admin with ID 1
        $query->where('id', '!=', 1);
        
        // Exclude current user ID
        // $query->where('id', '!=', $mainid);
        
        // If user_access is not 1, also exclude it from the query
        // if (Auth::guard('agent')->user()->user_access != 1) {
        //     $query->where('id', '!=', $userId);
        // }
      
        // Get the result
        $UserData = $query->paginate(15);
        return view('agent.user-listing',compact('UserData'));
    }
    public function create_account(){
        $userRoleId = Auth::guard('agent')->user()->role_id;
        if (Auth::guard('agent')->user()->user_access == 1) {
            $userId = Auth::guard('agent')->user()->admin_id;
            $mainid = Auth::guard('agent')->user()->id;
            $query = Admin::where('role_id', $userRoleId)
                    ->where('id', '!=' , $mainid)
                    ->where('admin_id', $userId);
        } else {
            $userId = Auth::guard('agent')->user()->id;
            $query = Admin::where('role_id', $userRoleId)
            ->where('id', '!=' , $userId)
            ->where('admin_id', $userId);
           
        }
        // Exclude main super admin with ID 1
        $query->where('id', '!=', 3);
        $data = $query->get();

        return view('agent.create-account',compact('data'));
    }

    public function banner_update(){
        return view('agent.banner-update');
    }  
  
   
    public function market_rollback(){
        return view('agent.market-rollback');
    }
    public function online_user(Request $request){
        $data=DB::table('admins')->where('login_status', '1');
        // dd(DB::getQueryLog());
                if($request->search!=''){
                    // DB::enableQueryLog();
                    $data=$data->where('username','like','%'.$request->search.'%');
                    // $data=$data->where('login_id','like','%'.$request->search.'%');
                    // dd(DB::getQueryLog());
                $data=$data->get();
                }
                
            if($request->search!=''){
            return view('agent.online-user-search',compact('data'));
        }
    
        if(Auth::guard('agent')->user()->user_access=='1'){
            $mainid=Auth::guard('agent')->user()->id;
            $userId=Auth::guard('agent')->user()->admin_id;
            $data=DB::table('admins')->where('login_status', '1')
            ->where('id' ,'!=',$mainid )
            ->where('id' ,'!=',$userId )
            ->paginate(30);
        }else{
            $mainid=Auth::guard('agent')->user()->id;
            $data=DB::table('admins')->where('login_status', '1')
            ->where('id' ,'!=',$mainid )
            ->paginate(30);
        }
       
        return view('agent.online-user' , compact('data'));
    }
    public function delete_bet_history(Request $request){
        $data = DB::table('bet_histories')
        ->select('bet_histories.*', 'clients.username as client_username', 'deleted_by.username as deleted_by_username')
        ->leftJoin('admins as clients', 'bet_histories.client_id', '=', 'clients.id')
        ->leftJoin('admins as deleted_by', 'bet_histories.deleted_by', '=', 'deleted_by.id')
        ->whereNotNull('bet_histories.deleted_at')
        ->get();
        // dd($data);
        return view('agent.delete-bet-history', compact('data'));
    }
 
    public function fancy_setting(){
        return view('agent.fancy-setting');
    }
    public function match_setting(){
        return view('agent.match-setting');
    }
    public function match_limit(){
        return view('agent.match-limit');
    }
    public function suspend_market(){
        return view('agent.suspend-all-market');
    }
    public function score_tv(){
        return view('agent.score-card-and-tv');
    }
    public function profitloss_downline(){
        return view('agent.profitLoss-report-by-downline');
    }
    public function prifitloss_market(){
        return view('agent.profitLoss-report-by-market');
    }
    public function risk_management(){
        return view('agent.risk-management');
    }
   
    public function password_history(){
        return view('agent.password-history');
    }
    public function commission(){
        return view('agent.commission');
    }
    public function market_analysis(){
        return view('agent.bet-market-analysis');
    }
    public function void_market(){
        return view('agent.void-markets');
    }
    public function white_label(Request $request){
        $admin_ids = QueryHelper::adminids();
                $user = Auth::guard('agent')->user();

                if ($user) {
                    $data = Admin::whereIn('id', $admin_ids)->paginate(15); // Adjust the pagination limit as needed
                }
    return view('agent.white-lable', compact('data'));
    }
    public function new_super(){
        return view('agent.super-createagent');
    }
    
    public function transfer_statement(){
        return view('agent.transfer-statement');
    }
    public function casino_result(){
        return view('agent.casino-result');
    }
    public function game_report(){
        return view('agent.game-report');
    }
    public function client_account_statement(){
        return view('agent.clients-account-statement');
    }
   
    public function live_casino(){
        return view('agent.live-casino');
    }
    public function message_report(){
        return view('agent.message-report');
    }
    public function notifi_cation(){
        return view('agent.notification');
    }
  
    public function demo_2(){
        return view('agent.smdemo1-agent-listing-demoag5');
    }
    public function demo_4(){
        return view('agent.agent-listing-demoag5');
    }
    public function demo_1(){
        return view('agent.agent-listing2');
    }
    public function new_user(){
        return view('agent.create-new-user');
    }
    public function race20(){
        return view('agent.race20');
    }
    public function admin_website_dashboard(Request $request){
        
        if($request->ajax()){
            
       
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL,"https://newsilver.art/api/all_games_bet_count");
        $result=curl_exec($ch);
        $newsilver=json_decode($result, true);
        curl_close($ch);
    
        $ch1 = curl_init();
        curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch1, CURLOPT_URL,"https://allpanel.art/api/all_games_bet_count");
        $result1=curl_exec($ch1);
        $allpanel=json_decode($result1, true);
        curl_close($ch);
        
        $ch12 = curl_init();
        curl_setopt($ch12, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch12, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch12, CURLOPT_URL,"https://crickekbuz.art/api/all_games_bet_count");
        $result12=curl_exec($ch12);
        $crickekbuz=json_decode($result12, true);
        curl_close($ch);
      
        $ch121 = curl_init();
        curl_setopt($ch121, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch121, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch121, CURLOPT_URL,"https://laserclub.art/api/all_games_bet_count");
        $result121=curl_exec($ch121);
        $Laser=json_decode($result121, true);
        curl_close($ch);
        
        $ch1212 = curl_init();
        curl_setopt($ch1212, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch1212, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch1212, CURLOPT_URL,"https://gold365.art/api/all_games_bet_count");
        $result1212=curl_exec($ch1212);
        $gold=json_decode($result1212, true);
        curl_close($ch);
        
        $data = [
                'newsilver' => $newsilver,
                'allpanel' => $allpanel,
                'crickekbuz' => $crickekbuz,
                'gold' => $gold,
                'Laser' => $Laser,
            ];
         return response()->json($data);
    }
        return view('agent.admin.dashboard',get_defined_vars());
    }
    public function admin_website_dashboard_detail($website){
        
        $url = "https://".$website."/api/all_games_bet_count_and_data";
       
        $ch = curl_init();
        // Disable SSL verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // Will return the response, if false it print the response
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Set the url
        curl_setopt($ch, CURLOPT_URL,$url);
        // curl_setopt($ch, CURLOPT_URL,"https://newsilver.art/api/all_games_bet_count_and_data");
        // Execute
        $result=curl_exec($ch);
        // Will dump a beauty json <3
        $response=json_decode($result, true);
      
        curl_close($ch);


        return view('agent.admin.detail',get_defined_vars());
    }
    public function admin_website_dashboard_detail_reject($id,$website,$game){
        
       
        $url = "https://".$website."/api/all_games_bet_reject/".$id."/".$game;
       
        $ch = curl_init();
        // Disable SSL verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // Will return the response, if false it print the response
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Set the url
        curl_setopt($ch, CURLOPT_URL,$url);
        // curl_setopt($ch, CURLOPT_URL,"https://newsilver.art/api/all_games_bet_count_and_data");
        // Execute
        $result=curl_exec($ch);
        // Will dump a beauty json <3
        $response=json_decode($result, true);
      
        curl_close($ch);

        return redirect()->back()->with('success','Successfully Updated');
       
    }
    public function admin_website_dashboard_detail_accept($id,$website,$game){
        
      
        $url = "https://".$website."/api/all_games_bet_accept/".$id."/".$game;
     
        $ch = curl_init();
        // Disable SSL verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // Will return the response, if false it print the response
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Set the url
        curl_setopt($ch, CURLOPT_URL,$url);
        // curl_setopt($ch, CURLOPT_URL,"https://newsilver.art/api/all_games_bet_count_and_data");
        // Execute
        $result=curl_exec($ch);
        // Will dump a beauty json <3
        $response=json_decode($result, true);
      
        curl_close($ch);

        return redirect()->back()->with('success','Successfully Updated');
       
    }
    
     public function getGameScoreList($game_id){
        
       
           $tennis = TennisMatchScore::where('game_id',$game_id)->first();
           $football = FootballMatchScore::where('game_id',$game_id)->first();
           $cricket = CricketMatchScore::where('game_id',$game_id)->get();
           $cricket_detail = CricketMatchScoreDetail::where('game_id',$game_id)->first();
       
       
        
        $data = [
                'tennis' => $tennis,
                'cricket' => $cricket,
                'football' => $football,
                'cricket_detail' => $cricket_detail,
            ];
         return response()->json($data);
    }
}
