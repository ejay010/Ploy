<?php

namespace App\Http\Controllers;

use App\bid;

use App\job;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Pusher;

class BidController extends Controller
{

    public $publicPusher;

    protected $privatePusher;

    public function __construct(){
        $this->publicPusher = new Pusher(env('PUSHER_PUBLIC_KEY'), env('PUSHER_SECRET_KEY'), env('PUSHER_APP_ID'));
        $this->privatePusher = new Pusher(env('PUSHER_PUBLIC_KEY'), env('PUSHER_SECRET_KEY'), env('PUSHER_APP_ID'));
    }

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
        // save the bid to the data base
        $thePost = $request['pid'];
        $bid = $request['bid'];
        $bidder = $request->user()->id;
        $comment = $request['comment'];

        if ($request->ajax()) {
            $theBid = bid::create(['job_id' => $thePost, 'bid' => $bid, 'bidder_id' => $bidder, 'comment' => $comment])->toArray();
            $this->updateBidTrack($theBid);
        } else {
            return response('unauthorized', 401);
        }
    }

    /**
     * Send all bids for a job
     * to the ajax request called getAllBids
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        //
        if($request->ajax()) {
            $theBids = bid::where('job_id', $id)->get()->toArray();
            return response()->json($theBids);
        } else {
            return response('unauthorized', 401);
        }
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
     * This is to send a notification to the user
     * that someone will do the job as is.
     *
     * @param Request $request
     * @param $id
     */
    public function dojob(Request $request, $id){

    }


    /**
     * This is to accept a bid for a job belonging to the user that is logged in
     *
     * @param Request $request
     */
    public function accept(Request $request){
        if($request->ajax()){

            $bidId = $request['bidid']; // I have bid Id, now for security purposes check to see if this bid belongs to a job that belongs to the authenticated user
            $theBid = bid::where('bidId', $bidId)->take(1)->get()->toArray(); //Now get a fresh instance of the bid from the db
            if(empty($theBid)){
                return 'false';
            }
            $theUser = Auth::User()->id; // get the auth user
            $theJob = job::where('jobpost_id',$theBid[0]['job_id'])->take(1)->get()->toArray(); // I now know that the bid is related to a valid job
            $theJobUserId = $theJob[0]['user_id'];
            if ($theUser === $theJobUserId){
                //save bid
                $bidValue = $theBid[0]['bid'];
                $this->initHandShake($bidId);
                $this->notifyBidderOfSuccessfulBid($bidId);
            } else {
                return 'false';
            }
            return 'true';
        } else {
            return 'false';
        }
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

    private function updateBidTrack(array $thebid){
        //$pusher = new Pusher(env('PUSHER_PUBLIC_KEY'), env('PUSHER_SECRET_KEY'), env('PUSHER_APP_ID'));
        $theJSONbid = json_encode($thebid);
        $this->publicPusher->trigger('theJobChannel', 'userPlaceBid', ['newBid' => $thebid]);
        $this->notifyPoster($thebid);
    }

    private function notifyPoster($thebid){
        //first find the job
        $theJob = job::where('jobpost_id', $thebid['job_id'])->first();
        //then find the owner of the post
        $theOwner = $theJob->user->id;
        $channelName = 'private-theU' . $theOwner;
        $this->privatePusher->trigger($channelName, 'bidToJob', ['owner_id' => $theOwner, 'theJobId' => $theJob->jobpost_id, 'thebid' => $thebid['id'],
        'theTitle'=> $theJob->title, 'theComment' => $thebid['comment'], 'thebidder' => $thebid['bidder_id'], 'theBidValue' => $thebid['bid']
        ]);
    }

    private function notifyBidderOfSuccessfulBid($thebidId){
        $thebid = bid::where('bidId', $thebidId)->take(1)->get()->toArray();
        $theJob = job::where('jobpost_id', $thebid[0]['job_id'])->first();
        // contact bid poster on his private channel
        $theBidder = $thebid[0]['bidder_id'];
        $bidderChannel = 'private-theU'.$theBidder;
        $message = 'Your bid of $'.$thebid[0]['bid'].' On the job "'.$theJob->title.'" has been accepted.';
        $this->privatePusher->trigger($bidderChannel, 'bidsuccess', ['m' => $message, 'thejob' => $theJob, 'thebid' => $thebid]);
    }

    public function pusherAuth(Request $request){
        if(Auth::user()) {
            //$this->privatepusher = new Pusher(env('PUSHER_PUBLIC_KEY'), env('PUSHER_SECRET_KEY'), env('PUSHER_APP_ID'));
           return $this->privatePusher->socket_auth($request['channel_name'], $request['socket_id']);
            }
        else {
            return response('unauthorized', 403);
        }
    }

    private function initHandShake($thebidId){
        $thebid = bid::where('bidId', $thebidId)->take(1)->get()->toArray();
        $theJob = job::where('jobpost_id', $thebid[0]['job_id'])->first();
        $theBidder = $thebid[0]['bidder_id'];
        $bidderChannel = 'private-theU'.$theBidder;
        $user = $theJob->user->id;
        $UserChannel = 'private-theU'.$user;
        $this->privatePusher->trigger($UserChannel, 'inithandshake', ['node' => $bidderChannel]);
        $this->privatePusher->trigger($bidderChannel, 'inithandshake', ['node' => $UserChannel]);

    }

    public function relayMessage(Request $request){
        $theMessage = $request['message'];
        $theNode = $request['node'];
        $this->privatePusher->trigger($theNode, 'incomeingMessage', ['m' => $theMessage]);
    }
}
