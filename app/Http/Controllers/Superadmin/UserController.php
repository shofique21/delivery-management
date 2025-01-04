<?php

namespace App\Http\Controllers\Superadmin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Role;
use App\Models\User;
use App\Models\SecratAgent;
use App\Models\SecretWithdrawal;
use App\Models\Merchant;
use DB;
class UserController extends Controller
{
   public function add(){
    $user_role = Role::where('id','!=',5)->get();
    return view('backEnd.user.add',compact('user_role'));
   }

   public function sadd(){
   
    return view('backEnd.sagent.add');
   }
    public function save(Request $request){
        // return 1;
        
    	$this->validate($request,[
    		'name'=>'required',
    		'username'=>'required',
    		'email'=>'required',
    		'phone'=>'required',
    		'designation'=>'required',
    		'role_id'=>'required',
    		'image'=>'required',
    		'status'=>'required',
    		'password'=>'required|min:6',
    	]);

    	// image upload
    	$file = $request->file('image');
    	$name = time().$file->getClientOriginalName();
    	$uploadPath = 'public/uploads/user/';
    	$file->move($uploadPath,$name);
    	$fileUrl =$uploadPath.$name;

    	$store_data					=	new User();
    	$store_data->name 			=	$request->name;
    	$store_data->username 		=	$request->username;
    	$store_data->email  		= 	$request->email;
    	$store_data->phone  		= 	$request->phone;
    	$store_data->designation 	= 	$request->designation;
    	$store_data->role_id 		= 	$request->role_id;
    	$store_data->image 			= 	$fileUrl;
    	$store_data->password 		= 	bcrypt(request('password'));
    	$store_data->status 		= 	$request->status;
    	$store_data->save();
        Toastr::success('message', 'User  add successfully!');
            $chatToken = "Basic QXBpQXV0aDoqSVRBUEkyMDIyIw==";
            header("Content-type: text / html; charset = utf-8");
            
            $option = array (
                "P_ACTION" => "C",
                "pRoleId" => 1,
                "pFullName" => $request->name,
                "pUsername" =>$request->name,
                "pEmail" => $request->email,
                "pPhone" => $request->phone,
                "pDesignation" =>$request->designation,
                "pPassword" => $request->password,
                "pImage" => "",
                "pStatus" => $request->status,
                "pRememberToken" => "",
              
            );
            
            $ch = curl_init();
            
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_URL,"http://103.163.246.94/it/apidb/api/crud_users");
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($option));
            curl_setopt($ch, CURLOPT_HTTPHEADER, array ('Authorization:'. $chatToken));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            
            $response = curl_exec($ch);
            curl_close($ch);
            var_dump($response);exit;
    	return redirect('/superadmin/user/manage');
    }
	public function ssave(Request $request){
        // dd($request);
    	// $this->validate($request,[
    	// 	'name'=>'required',
    	// 	'username'=>'required',
    	// 	'email'=>'required',
    	// 	'phone'=>'required',
    	// 	'designation'=>'required',    	
    	// 	'image'=>'required',
    	// 	'status'=>'required',
    	// 	'password'=>'required|min:6',
    	// ]);

    	// image upload
    	if($request->file('image')){
    	$file = $request->file('image');
    	$name = time().$file->getClientOriginalName();
    	$uploadPath = 'public/uploads/user/';
    	$file->move($uploadPath,$name);
    	$fileUrl =$uploadPath.$name;
    	}
// return 1;
    	$store_data					=	new User();
		// dd($store_data);
    	$store_data->name 			=	$request->name;
    	$store_data->username 		=	$request->username;
    	$store_data->email  		= 	$request->email;
    	$store_data->phone  		= 	$request->phone;
    	$store_data->designation 	= 	$request->designation;
    	$store_data->role_id 		= 	5;
    	$store_data->status 		= 	1;
		if($request->file('image')){
    	$store_data->image 			= 	$fileUrl;
		}
    	$store_data->password 		= 	bcrypt(request('password'));
    	
    	$store_data->save();
        Toastr::success('message', 'Secret Agent add successfully!');
    	return redirect('/superadmin/sagent/manage');
    }
   public function manage(){
        
    	$show_datas = DB::table('users')
    	->join('roles', 'users.role_id', '=', 'roles.id' )
    	->select('users.*', 'roles.user_role')
        ->orderBy('id','DESC')
    	->get();
    	return view('backEnd.user.manage',compact('show_datas'));
    }
	public function smanage(){
        
    	$show_datas = DB::table('users')
    	->where('role_id',5)
        ->orderBy('id','DESC')
    	->get();
    	return view('backEnd.sagent.manage',compact('show_datas'));
    }
    public function edit($id){
        $edit_data = User::find($id);
        $user_role = Role::all();
    	return view('backEnd.user.edit',compact('edit_data','user_role'));
    }
	public function sedit($id){
        $edit_data = User::find($id);       
    	return view('backEnd.sagent.edit',compact('edit_data'));
    }
    public function update(Request $request){
    	$this->validate($request,[
    		'name'=>'required',
    		'username'=>'required',
    		'email'=>'required',
    		'phone'=>'required',
    		'designation'=>'required',
    		'role_id'=>'required',
    		'status'=>'required',
    	]);
    	$update_data = User::find($request->hidden_id);
    	// image upload
    	$update_file = $request->file('image');
    	if ($update_file) {
	    	$name = time().$update_file->getClientOriginalName();
	    	$uploadPath = 'public/uploads/user/';
	    	$update_file->move($uploadPath,$name);
	    	$fileUrl =$uploadPath.$name;
    	}else{
    		$fileUrl = $update_data->image;
    	}

    	$update_data->name 			=	$request->name;
    	$update_data->username 		=	$request->username;
    	$update_data->email  		= 	$request->email;
    	$update_data->phone  		= 	$request->phone;
    	$update_data->designation 	= 	$request->designation;
    	$update_data->role_id 		= 	$request->role_id;
    	$update_data->image 		= 	$fileUrl;
    	$update_data->status 		= 	$request->status;
    	$update_data->save();
        Toastr::success('message', 'User  update successfully!');
    	return redirect('/superadmin/sagent/manage');
    }
	public function supdate(Request $request){
    	// $this->validate($request,[
    	// 	'name'=>'required',
    	// 	'username'=>'required',
    	// 	'email'=>'required',
    	// 	'phone'=>'required',
    	// 	'designation'=>'required',
    	// 	'role_id'=>'required',
    	// 	'status'=>'required',
    	// ]);
    	$update_data = User::find($request->hidden_id);
    	// image upload
    	$update_file = $request->file('image');
    	if ($update_file) {
	    	$name = time().$update_file->getClientOriginalName();
	    	$uploadPath = 'public/uploads/user/';
	    	$update_file->move($uploadPath,$name);
	    	$fileUrl =$uploadPath.$name;
    	}else{
    		$fileUrl = $update_data->image;
    	}

    	$update_data->name 			=	$request->name;
    	$update_data->username 		=	$request->username;
    	$update_data->email  		= 	$request->email;
    	$update_data->phone  		= 	$request->phone;
    	$update_data->designation 	= 	$request->designation;
    	$update_data->role_id 		= 	$request->role_id;
    	$update_data->image 		= 	$fileUrl;
    	$update_data->status 		= 	1;
		$update_data->role_id 		= 	5;
    	$update_data->save();
        Toastr::success('message', 'Secret Agent  update successfully!');
    	return redirect('/superadmin/sagent/manage');
    }
    public function inactive(Request $request){
        $inactive_data = User::find($request->hidden_id);
        $inactive_data->status=0;
        $inactive_data->save();
        Toastr::success('message', 'User  inactive successfully!');
        return redirect('/superadmin/user/manage');      
    }
	public function secrat_merchant($id){
		$user =User::where('id',$id)->first();
		$merchant=Merchant::orderBy('id','DESC')->get();
		$show_datas =SecratAgent::where('user_id',$id)->orderBy('id','DESC')->get();		
    	return view('backEnd.sagent.merchant',compact('show_datas','user','merchant'));


	}
	public function merchant_add(Request $request){
		$SecratAgent=new SecratAgent;
		$SecratAgent->user_id=$request->user_id;
		$SecratAgent->merchant_id=$request->merchant_id;
		$SecratAgent->commision=$request->commision;
		$SecratAgent->save();
		Toastr::success('message', 'Merchant has been successfully!');
        return back(); 
	}

	public function secrat_merchant_delete($id){
		SecratAgent::where('id',$id)->delete();
		Toastr::success('message', 'Secrat Agent has been Deleted!');
        return back(); 

	}
	public function merchant_commission_update(Request $request){
		$SecratAgent=SecratAgent::where('id',$request->id)->first();
		$SecratAgent->commision=$request->commision;
		$SecratAgent->save();
		Toastr::success('message', 'Commision has been Update!');
        return back(); 

	}
    public function active(Request $request){
        $inactive_data = User::find($request->hidden_id);
        $inactive_data->status=1;
        $inactive_data->save();
        Toastr::success('message', 'User  active successfully!');
        return redirect('/superadmin/user/manage');        
    }

    public function destroy(Request $request){
        $destroy_id = User::find($request->hidden_id);
        $destroy_id->delete();
        Toastr::success('message', 'User  delete successfully!');
        return redirect('/superadmin/user/manage');         
    }
	public function sdestroy(Request $request){
        $destroy_id = User::find($request->hidden_id);
        $destroy_id->delete();
        Toastr::success('message', 'User  delete successfully!');
        return redirect('/superadmin/sagent/manage');         
    }

	public function withdrawal(){
		$withdra=SecretWithdrawal::orderBy('id','DESC')->where('mWithdrawal',null)->get();
		return view('backEnd.sagent.withdrawal',compact('withdra'));

	}
	public function paid($id){
		$withdra=SecretWithdrawal::where('id',$id)->first();
		$withdra->status=1;
		$withdra->save();
		Toastr::success('message', 'Withdrawal Request  has been Paid !');
        return back(); 


	}
}
