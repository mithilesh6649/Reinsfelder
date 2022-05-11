<?php

namespace App\Http\Controllers\restApi;
session_start();
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\emailVerification;
use App\Company_registration;    
class company extends Controller
{
    private $data;
    private $password;
    private $query;
    private $macAddress;
    private $macAddressObj;
    private $allData;
    private $generate_password;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         
         $this->data = $request->all();
         $this->generate_password = $this->data['password'];
         $this->password = array("password"=>"mk12");
         $this->data =  array_replace($this->data,$this->password);
       
        
        Company_registration::create($this->data); 
        

        //sending erp_url and password
 
       \Mail::to($this->data['company_email'])->send(new emailVerification(array(
               "erp_url" => $this->data['erp_url'],
               "password" => $this->generate_password
             )));
            
          //releasing memory
          
          unset($_POST);
          unset($request);
          unset($this->data);
          unset($this->generate_password);
          unset($this->password);  

          return redirect("/congrats");
          exit;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($query,Request $request)
    {  
      $this->macAddressObj = new macAddress();
      $this->macAddress = $this->macAddressObj->__construct();
      
     $this->query =  json_decode(base64_decode($query));
     
     //validate from company slug

    if($this->query->query == "erp"){
      $this->data = Company_registration::where("company_slug",$this->query->string)->get(); 
              if(count($this->data) != 0){
                 if($request->ajax()){
                     return response(array("notice"=>"Data found !"),200)->header('Content-Type','application/json');

                     //releasing memory
                     unset($_GET);
                     unset($query);
                     unset($request);
                     unset($this->macAddress);
                     unset($this->macAddressObj);
                     unset($this->data);
                     unset($this->query);
                 }
                 else{
                     session()->flash("authentication",$this->query->string);
                     
                     //find not registered admin
 
                    foreach ($this->data as $this->allData){
                        if(empty($this->allData->admin_mac_address)){
                           session()->flash("mac_authentication","notRegistered");
                        }else{
                            if($this->allData->admin_mac_address == $this->macAddress){
                                session()->flash("mac_authentication","admin");
                            }
                            else{
                               // session()->flash("mac_authentication","employee");
                               session()->flash("mac_authentication","admin");
                            }
                        }   
                    }
                    
                     return  response()->view("erp.authenticate")->header('Content-Type','text/html')->setStatusCode(200);

                     //releasing memory
                     unset($_GET);
                     unset($query);
                     unset($request);
                     unset($this->macAddress);
                     unset($this->macAddressObj);
                     unset($this->data);
                     unset($this->query);
                     unset($this->allData);
                 }
                        
              }else{
                 
                    return response(array("notice"=>"Data not found !"),404)->header('Content-Type',"application/json");

                //releasing memory
                     unset($_GET);
                     unset($query);
                     unset($request);
                     unset($this->macAddress);
                     unset($this->macAddressObj);
                     unset($this->data);
                     unset($this->query);


                 

              }
      } 

    //next validation


    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $query)
    {
      $this->macAddressObj = new macAddress();
      $this->macAddress = $this->macAddressObj->__construct();
      
      $this->query = json_decode(base64_decode($query));
     // print_r($this->query);

      //register admin pc
      if($this->query->query == "adminRegister"){
          $this->data = Company_registration::where([
               ["company_slug",$this->query->company_slug],
               ["company_estd",$this->query->company_estd],
               ["password","mk12"]
          ])->update(array(
               "admin_mac_address" => $this->macAddress,
             ));

        if($this->data)
        {
             $_SESSION= array(
                  "adminAuthentication" => "true",
                  "team_creator" => "mk@gmail.com",
                  "team_role" => "admin"
              ); 
            return response(array("Notice","Admin authenticated !"),200)->header("Content-Type","application/json"); 

          //releasing memory
                    
                     unset($request);
                     unset($query);
                     unset($this->macAddress);
                      unset($this->macAddressObj);
                     unset($this->query);
                     unset($this->data);
        } 

      }
      else{
        return response(array("Notice","Authentication failed !"),404)->header("Content-Type","application/json"); 

          //releasing memory
                    
                     unset($request);
                     unset($query);
                     unset($this->macAddress);
                      unset($this->macAddressObj);
                     unset($this->query);
                     unset($this->data);
      }
     
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}



//list of custom classes 

//getting admin mac address

class macAddress{
    private $data;
    private $info;
    function __construct(){
      
      exec("ipconfig/all",$this->info);
     foreach ($this->info as $this->data) {
       if(preg_match("/Physical Address/i",$this->data)){
         $this->data =  preg_replace("/\s+\./","",$this->data);
         $this->data = explode(":",$this->data);
        
         return $this->data[1] ;
         break;
       }
     }


    }
}


 
 