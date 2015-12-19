<?php

namespace App\Http\Controllers;

use App\job;
use App\Posts;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Pusher;

class PostsController extends Controller
{
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
    public function storeGeneralPost(Request $request)
    {
        // Save a post of type general
        $loc = $request['loc'];
        $content = $request['description'];
        $type = $request['type'];
        $title = $request['title'];
        $salary = $request['cost'];
        $employerId = $request->user()->id;

        $generalPost = new job();
        $generalPost->content = $content;
        $generalPost->type = $type;
        $generalPost->title = $title;
        $generalPost->salary = $salary;
        $generalPost->user_id = $employerId;
        $generalPost->location = $loc;
        $generalPost->save();

        if($request->ajax()){
            $this->updateAllPosts($generalPost->toArray());
        }

        return redirect('/userlogin');
    }

    public function storeFoodPost(Request $request){

        $loc = $request['loc'];
        $content = $request['description'];
        $type = $request['type'];
        $title = $request['title'];
        $salary = $request['cost'];
        $employerId = $request->user()->id;

        $FoodPost = new job();
        $FoodPost->content = $content;
        $FoodPost->type = $type;
        $FoodPost->title = $title;
        $FoodPost->salary = $salary;
        $FoodPost->user_id = $employerId;
        $FoodPost->location = $loc;
        $FoodPost->save();

        if($request->ajax()){
            $this->updateAllPosts($FoodPost->toArray());
        }

        return redirect('/userlogin');
    }

    public function storeVicePost(Request $request){

        $loc = $request['loc'];
        $content = $request['description'];
        $type = $request['type'];
        $title = $request['title'];
        $salary = $request['cost'];
        $employerId = $request->user()->id;

        $VicePost = new job();
        $VicePost->content = $content;
        $VicePost->type = $type;
        $VicePost->title = $title;
        $VicePost->salary = $salary;
        $VicePost->user_id = $employerId;
        $VicePost->location = $loc;
        $VicePost->save();

        if($request->ajax()){
            $this->updateAllPosts($VicePost->toArray());
        }
        return redirect('/userlogin');
    }

    public function storeTransportPost(Request $request){

        $loc = $request['loc'];
        $content = $request['description'];
        $type = $request['type'];
        $title = $request['title'];
        $salary = $request['cost'];
        $employerId = $request->user()->id;

        $TransPost = new job();
        $TransPost->content = $content;
        $TransPost->type = $type;
        $TransPost->title = $title;
        $TransPost->salary = $salary;
        $TransPost->user_id = $employerId;
        $TransPost->location = $loc;
        $TransPost->save();

        if($request->ajax()){
            $this->updateAllPosts($TransPost->toArray());
        }
        return redirect('/userlogin');
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
    public function update(Request $request, $id)
    {
        //
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

    public function getPostLocation(Request $request){
        $latitude = $request['lat'];
        $longitude = $request['long'];
        return $this->setPostLocation($latitude, $longitude);
    }

    private function setPostLocation($lat,  $long){
        $latAndLong = "{lat: $lat, lng: $long}";

        return $latAndLong;
    }

    public function placeOnMap(Request $request){
        // This json thing was so confusing
        if ($request->json()){
            $thePosts = job::all(['location',
                'type', 'title', 'jobpost_id', 'content']);
            return response()->json($thePosts->toArray());
        } else {
            return response('Unauthorized', 401);
        }
    }

    private function updateAllPosts(array $theJob){
        $pusher = new Pusher(env('PUSHER_PUBLIC_KEY'), env('PUSHER_SECRET_KEY'), env('PUSHER_APP_ID'));
        $theJSONjob = $theJob;
        $pusher->trigger('theJobChannel', 'userAddedPost', ['newjob' => $theJSONjob]);
    }

}
