<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Multipic;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class BrandController extends Controller
{

     public function __construct(){
       $this->middleware('auth');
     }

      public function AllBrand(){
           $brands = Brand::latest()->paginate(5);
           return view('admin.brand.index',compact('brands'));
      }

      public  function StoreBrand(Request $request){

         $validateData = $request->validate([
             'brand_name' => 'required| unique:brands| min:4 | max:250',
             'brand_image' => 'required | mimes:jpg,jpeg,png,gif',
         ],[
             'brand_name.required' => 'Please input brand name',
             'brand_name.unique' => 'Brand already exists',
             'brand_name.min' => 'Brand longer than 4 Characters',
         ]);

         $brand_image = $request->file('brand_image');
        // dd($brand_image->getClientOriginalExtension());
         $name_gen = hexdec(uniqid());
         
         $img_extension = strtolower($brand_image->getClientOriginalExtension());
         
         $image_name = $name_gen.'.'.$img_extension;

         $up_location = 'image/brand/';
         $last_img = $up_location.$image_name;
 
         $brand_image->move($up_location,$last_img);
       
          Brand::insert([
              'brand_name' => $request->brand_name,
              'brand_image' =>  $last_img,
              'created_at' => Carbon::now(),        
            ]);
         
          return redirect()->back()->with('success','Brand Inserted Successfully');  
     
      }


      public function EditBrand($id){
          $brands = Brand::find($id);
          return view('admin.brand.edit',compact('brands'));
      }


      public function UpdateBrand(Request $request, $id){
           
         $validateData = $request->validate([
            'brand_name' => 'required| min:4 | max:250',
        ],[
            'brand_name.required' => 'Please input brand name',
            'brand_name.min' => 'Brand longer than 4 Characters',
        ]);


        $brand_image = $request->file('brand_image');
        
        if($brand_image)
        {
            $old_image = $request->old_image;
        // dd($brand_image->getClientOriginalExtension());
         $name_gen = hexdec(uniqid());
         
         $img_extension = strtolower($brand_image->getClientOriginalExtension());
         
         $image_name = $name_gen.'.'.$img_extension;

         $up_location = 'image/brand/';
         $last_img = $up_location.$image_name;

         $brand_image->move($up_location,$last_img);
          
         //remove image from folder by unlink

         unlink($old_image);

          Brand::find($id)->update([
              'brand_name' => $request->brand_name,
              'brand_image' =>  $last_img,
              'created_at' => Carbon::now(),        
            ]);
         
          return redirect()->back()->with('success','Brand Inserted Successfully');  
     

        }
        else{
         
            Brand::find($id)->update([
                'brand_name' => $request->brand_name,
                'created_at' => Carbon::now(),        
              ]);
           
            return redirect()->back()->with('success','Brand Inserted Successfully');  
       


        }

       

      }



      public function DeleteBrand($id){
               $image = Brand::find($id);
               $old_image = $image->brand_image;
               unlink($old_image);

             Brand::find($id)->delete();
             return redirect()->back()->with('success','Brand Delete Successfully');
      }

      //Multi image upload.......
      public function MultiImage(){
         $images =  Multipic::all();
         return view('admin.multipic.index',compact('images'));
      }

      public function StoreImg(Request $request){

      //   $validateData = $request->validate([
      //     'brand_name' => 'required| unique:brands| min:4 | max:250',
      //     'brand_image' => 'required | mimes:jpg,jpeg,png,gif',
      // ],[
      //     'brand_name.required' => 'Please input brand name',
      //     'brand_name.unique' => 'Brand already exists',
      //     'brand_name.min' => 'Brand longer than 4 Characters',
      // ]);

      $brand_image = $request->file('image');
     // dd($brand_image->getClientOriginalExtension());
       //dd($brand_image);
      //Upload Multiple Images..........
      foreach($brand_image as $multi_img){
       
         $name_gen = hexdec(uniqid());
      
      $img_extension = strtolower($multi_img->getClientOriginalExtension());
      
     $image_name = $name_gen.'.'.$img_extension;

        $up_location = 'image/multi/';
     $last_img = $up_location.$image_name;

     $multi_img->move($up_location,$last_img);
    
       Multipic::insert([
           'image' =>  $last_img,
           'created_at' => Carbon::now(),        
         ]);

      } 


      
      
      return redirect()->back()->with('success','Brand Inserted Successfully');  
  

         // dd($request);
      }

      

       public function logout(){
        \Auth::logout();
       
        return redirect()->route('login')->with('success','User logout');

       }


}
