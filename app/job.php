<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class job extends Model
{
    //
    protected $table = 'jobposts';

    protected $fillable = ['type', 'title', 'content', 'user_id', 'status'];

    /**
     *
     * Get the many bids associated with this post
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function thebids(){

        return $this->hasMany('App\bid');
    }


    /**
     *
     * Get the user that this bid is associated with
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(){

        return $this->belongsTo('App\User');
    }
}
