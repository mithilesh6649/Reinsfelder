<?php

namespace App\Http\Controllers;

use App\Models\HomeAbout;
use Illuminate\Http\Request;

class AboutController extends Controller
{
      public function HomeAbout(){
          $homeabout = HomeAbout::latest()->get();
          return view('admin.homeabout.index',compact('homeabout'));
      }

      public function AddAbout(){
        return view('admin.homeabout.create'); 
      }

      public function StoreAbout(Request $request){
         
           HomeAbout::create($request->all());

           return redirect()->route('home.about')->with('success','About Instered Successfully');
   
      }

      public function EditAbout($id){
          
            $homeabout = HomeAbout::find($id);
          return view('admin.homeabout.edit',compact('homeabout'));

      }

      public function UpdateAbout(Request $request , $id){
             dump($request->all());
             dump($id);

            
             HomeAbout::where("id", $id)->update([
                   'title' =>$request->title,
                   'short_des' =>$request['short_des'],
                   'long_des' => $request['long_des'],
             ]); 
         
          return redirect()->route('home.about')->with('success','About  Updated  Successfully');  

      }

      public function DeleteAbout($id){
        HomeAbout::find($id)->delete();
        return redirect()->route('home.about')->with('success','About Deleted Successfully');  
   }
      
}
