<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AboutController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware('auth:sanctum')->get('/dashboard', function (Request $request) {
    //return  view('dashboard');
   // return  view('dashboard');
    return view('backend.admin.index');
})->name('dashboard');


Route::get('/', function () {
   // return view('admin.admin_master');
   $brands = DB::table('brands')->get();
   $abouts = DB::table('home_abouts')->first();
   return view('home',compact('brands','abouts'));
});

//category controlle
Route::get('/category/all',[CategoryController::class,'index'])->name('all.category');

//store category  
Route::post('/category/add',[CategoryController::class,'AddCat'])->name('store.category');

//Edit category
Route::get('/category/edit/{id}',[CategoryController::class,'Catedit']);


//Update category
Route::post('/category/update/{id}',[CategoryController::class,'Update']);


//Edit category
Route::get('softdelete/category/{id}',[CategoryController::class,'SoftDelete']);

//Restore category
Route::get('category/restore/{id}',[CategoryController::class,'Restore']);

//Permanent delete category
Route::get('permanent/category/delete/{id}',[CategoryController::class,'PermanentDelete']);

// Route::get('/dashboard', function () {
//         $users = User::all();
//         return view('dashboard',compact('users'));
//     })->name('dashboard');

 
 /*
|--------------------------------------------------------------------------
|  Brand Related all coding........
|--------------------------------------------------------------------------
*/ 
    
    Route::get('/brand/all',[BrandController::class,'AllBrand'])->name('all.brand');

    Route::post('/barnd/add',[BrandController::class,'StoreBrand'])->name('store.brand');

    Route::get('brand/edit/{id}',[BrandController::class,'EditBrand'])->name('edit.brand');
    
    Route::post('brand/update/{id}',[BrandController::class,'UpdateBrand'])->name('update.brand');

    Route::get('brand/delete/{id}',[BrandController::class,'DeleteBrand'])->name('delete.brand'); 

    //multi Image Route.......

    Route::get('/multi/image',[BrandController::class,'MultiImage'])->name('multi.image');
    Route::post('/multi/add',[BrandController::class,'StoreImg'])->name('store.image');


    //Logout.....

    Route::get('/user/logout',[BrandController::class,'logout'])->name('user.logout');



    //Admit All Route...........

    Route::get('/home/slider',[HomeController::class,'HomeSlider'])->name('home.slider');
    
    Route::get('/add/slider',[HomeController::class,'AddSlider'])->name('add.slider');
    Route::post('/store/slider',[HomeController::class,'StoreSlider'])->name('store.slider'); 

    Route::get('slider/delete/{id}',[HomeController::class,'DeleteSlider'])->name('delete.slider'); 
    
    Route::get('slider/edit/{id}',[HomeController::class,'EditSlider'])->name('edit.slider'); 
    
    Route::post('slider/update/{id}',[HomeController::class,'Updateslider'])->name('update.slider');



    /*
|--------------------------------------------------------------------------
| Home About Related all coding........
|--------------------------------------------------------------------------
*/  


Route::get('/home/about',[AboutController::class,'HomeAbout'])->name('home.about');
Route::get('/add/about',[AboutController::class,'AddAbout'])->name('add.about');
Route::post('/store/about',[AboutController::class,'StoreAbout'])->name('store.about');
Route::get('/about/edit/{id}',[AboutController::class,'EditAbout'])->name('edit.about');
Route::post('about/update/{id}',[AboutController::class,'UpdateAbout'])->name('update.about');
Route::get('about/delete/{id}',[AboutController::class,'DeleteAbout'])->name('delete.about'); 
    