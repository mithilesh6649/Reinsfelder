<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
      public function __construct(){
        $this->middleware('auth');
      }

      public function index(){
           $categories = Category::paginate(4);
           $trashcategories = Category::onlyTrashed()->latest()->paginate(4);
          //$categories = Category::latest()->get();
         // $categories  = DB::table('categories')->latest()->paginate(4);

        // $categories = DB::table('categories')->join('users','categories.user_id','users.id')->select('categories.*','users.name')->latest()->paginate(5);

         return view('admin.category.index',compact('categories','trashcategories'));
      }

      public function AddCat(Request $request){
          $vaildateData = $request->validate([
              'category_name' => 'required|unique:categories|max:255',
          ], [
            'category_name.required' => 'Please input category name',
            'category_name.unique' => 'This category already exists',
            'category_name.max' => 'Category Less Then 255character',
          ]);

         
          Category::insert([
              'category_name' => $request->input('category_name'),
              'user_id' => Auth::user()->id,
              'created_at' => Carbon::now(),
          ]);


        // $category = new Category;
        // $category->category_name = $request->category_name;
        // $category->user_id = Auth::user()->id;
        // $category->save();
       
          return redirect()->back()->with('success','Category Inserted Successfully');

      }

     
       public function Catedit($id){
         // $categories = Category::find($id);
         $categories = DB::table('categories')->where('id',$id)->first();
          return view('admin.category.edit',compact('categories'));
       }


       public function Update(Request $request, $id){

        $vaildateData = $request->validate([
          'category_name' => 'required|unique:categories|max:255',
      ], [
        'category_name.required' => 'Please input category name',
        'category_name.unique' => 'This category already exists',
        'category_name.max' => 'Category Less Then 255character',
      ]);
      
        // $categories = Category::where('id',$id)->update([
        //          'category_name' => $request->category_name,
        //          'user_id' =>Auth::user()->id
        // ]);

        $data = array();

        $data['category_name'] = $request->category_name;
        $data['user_id'] =Auth::user()->id;
         
        DB::table('categories')->where('id',$id)->update($data);

        return redirect()->route('all.category')->with('success','Category Updated Successfully');

     }


  //SoftDelete

  public function SoftDelete($id){
     $delete = Category::find($id)->delete();

     return redirect()->route('all.category')->with('success','Category Soft Delete Successfully');

  }
  
  //Restore........

  public function Restore($id){
         $delete = Category::withTrashed()->find($id)->restore();
        return redirect()->back()->with('success','Category Restore Successfully');
  }

  //Permanent delete

  public function PermanentDelete($id){
       $delete = Category::onlyTrashed()->find($id)->forceDelete();
        
       return redirect()->back()->with('success','Category Permanently Deleted');

  }


}
