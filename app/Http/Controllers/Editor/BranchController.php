<?php

namespace App\Http\Controllers\Editor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Nearestzone;
use App\Models\Branch;
use DB;
class BranchController extends Controller
{
    public function add(){
    $areas = Nearestzone::where('status',1)->orderBy('zonename','ASC')->get();
    return view('backEnd.branch.add',compact('areas'));
   }
    public function save(Request $request){
       // dd($request);exit;
    	$this->validate($request,[
    	   	'name'=>'required',
    		'address'=>'required',
    		'phone'=>'required|unique:branches',
    		'area'=>'required',
    		'image'=>'required',
            'status'=>'required',
    	]);
        
    	// image upload
    	$file = $request->file('image');
    	 if (!is_dir(storage_path() . "/public/uploads/branches/")) {
                    mkdir(storage_path() .  "/public/uploads/branches/", 0777, true);
                }
    	$name = time().$file->getClientOriginalName();
    	$uploadPath = 'public/uploads/branches/';
    	$file->move($uploadPath,$name);
    	$fileUrl =$uploadPath.$name;

    	$store_data					=	new Branch();
    	$store_data->name 			=	$request->name;
    	$store_data->area 			=	$request->area;
    	$store_data->address  		= 	$request->address;
    	$store_data->phone  		= 	$request->phone;
    	$store_data->images 			= 	$fileUrl;
    	$store_data->status 		= 	$request->status;
    	$store_data->save();
        Toastr::success('message', 'Branch added successfully!');
    	return redirect('editor/branch/manage');
    }
   
   public function manage(){
    	$show_datas = DB::table('branches')
    	->join('nearestzones', 'branches.area', '=', 'nearestzones.id' )
    	->select('branches.*', 'nearestzones.zonename')
        ->orderBy('id','DESC')
    	->get();
    	return view('backEnd.branch.manage',compact('show_datas'));
    }

    public function edit($id){
        $edit_data = Branch::find($id);
        $areas = Nearestzone::where('status',1)
         ->orderBy('zonename','ASC')->get();
    	return view('backEnd.branch.edit',compact('edit_data','areas'));
    }

    public function update(Request $request){
    	$this->validate($request,[
    	   	'name'=>'required',
    		'area'=>'required',
    		'address'=>'required',
    		'phone'=>'required',
    		'status'=>'required',
    	]);
    	$update_data = Branch::find($request->hidden_id);
    	// image upload
    	$update_file = $request->file('image');
    		 if (!is_dir(storage_path() . "/public/uploads/branches/")) {
                    mkdir(storage_path() .  "/public/uploads/branches/", 0777, true);
                }
    	if ($update_file) {
	    	$name = time().$update_file->getClientOriginalName();
	    	$uploadPath = 'public/uploads/branches';
	    	$update_file->move($uploadPath,$name);
	    	$fileUrl =$uploadPath.$name;
    	}else{
    		$fileUrl = $update_data->images;
    	}
	    $update_data->name 			=	$request->name;
    	$update_data->area 			=	$request->area;
    	$update_data->address  		= 	$request->address;
    	$update_data->phone  		= 	$request->phone;
    	$update_data->images 		= 	$fileUrl;
    	$update_data->status 		= 	$request->status;
    	$update_data->save();
        Toastr::success('message', 'Branch updated successfully!');
    	return redirect('editor/branch/manage');
    }

    public function inactive(Request $request){
        $inactive_data = Branch::find($request->hidden_id);
        $inactive_data->status=0;
        $inactive_data->save();
        Toastr::success('message', 'Branch inactive successfully!');
        return redirect('editor/branch/manage');      
    }

    public function active(Request $request){
        $inactive_data = Branch::find($request->hidden_id);
        $inactive_data->status=1;
        $inactive_data->save();
        Toastr::success('message', 'Branch active successfully!');
        return redirect('editor/branch/manage');        
    }

    public function destroy(Request $request){
        $destroy_id = Branch::find($request->hidden_id);
        $destroy_id->delete();
        Toastr::success('message', 'Branch deleted successfully!');
        return redirect('editor/branch/manage');         
    }
    
   
	
}
