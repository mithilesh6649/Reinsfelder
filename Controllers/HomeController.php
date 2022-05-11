<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Slider;
use Carbon\Carbon;
class HomeController extends Controller
{
     public function HomeSlider(){
        $sliders = Slider::latest()->get();
        return view('admin.slider.index',compact('sliders'));
     }


     public function AddSlider(){
        return view('admin.slider.create');
     }

     public function StoreSlider(Request $request){
       // dd($request->all());
       
        
        $validateData = $request->validate([
         'title' => 'required| max:250',
         'image' => 'required | mimes:jpg,jpeg,png,gif',
         'description' => 'required'
     ]);
       
     

     $slider_image = $request->file('image');
    // dd($brand_image->getClientOriginalExtension());
     $name_gen = hexdec(uniqid());
     
     $img_extension = strtolower($slider_image->getClientOriginalExtension());
     
     $image_name = $name_gen.'.'.$img_extension;

     $up_location = 'image/slider/';
     $last_img = $up_location.$image_name;

     $slider_image->move($up_location,$last_img);
    
      Slider::insert([
          'title' => $request->title,
           'description' => $request->description,
          'image' =>  $last_img,
          'created_at' => Carbon::now(),        
        ]);
     
      return redirect()->route('home.slider')->with('success','Slider Inserted Successfully');  

     }


    public function EditSlider($id){
         $sliderData =   \DB::table('sliders')->where('id',$id)->get();    
         
          // dd($sliderData);
         return view('admin.slider.edit',compact('sliderData'));  
    }


    public function Updateslider(Request $request , $id){
       
      $validateData = $request->validate([
         'title' => 'required| min:4 | max:250',
         'description' => 'required ',
     ],[
         'name.required' => 'Please  Provide title name',
     ]);


     $slider_image = $request->file('image');
     
     if($slider_image)
     {
         $old_image = $request->old_image;
     // dd($brand_image->getClientOriginalExtension());
      $name_gen = hexdec(uniqid());
      
      $img_extension = strtolower($slider_image->getClientOriginalExtension());
      
      $image_name = $name_gen.'.'.$img_extension;

      $up_location = 'image/slider/';
      $last_img = $up_location.$image_name;

      $slider_image->move($up_location,$last_img);
       
      //remove image from folder by unlink

      unlink($old_image);

       Slider::find($id)->update([
           'title' => $request->title,
           'description' => $request->description,
           'image' =>  $last_img,
           'created_at' => Carbon::now(),        
         ]);
      
       return redirect()->route('home.slider')->with('success',' Slider  Updated Successfully');  
  

     }
     else{
      
         Slider::find($id)->update([
             'title' => $request->title,
             'description' => $request->description,
             'created_at' => Carbon::now(),        
           ]);
        
         return redirect()->route('home.slider')->with('success','Slider  Updated  Successfully');  
    


     }



 
    }


    public function DeleteSlider($id){
         $delete_slider = Slider::find($id)->delete();
         return redirect()->back()->with('success','Slider Deleted Successfully');  
    }


}
