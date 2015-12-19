<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class bid extends Model
{
    //
    protected $table = 'jobBids';

    protected $fillable = ['bid', 'bidder_id', 'comment', 'job_id'];

    /**
     *
     * Get the post associated with this bid
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function thepost(){

        return $this->belongsTo('App\job');
    }

    /**
     *
     * Get the user that made this bid
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function theowner(){

        return $this->hasOne('App\User');
    }
}
