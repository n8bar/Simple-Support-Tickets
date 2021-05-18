<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class Ticket extends Model
{
    use HasFactory;
	protected $table = 'tickets';

	protected $casts = [

	];

	protected $fillable = [
		'title',
		'details',
		'satisfaction',
        'user_id',
        'category_id',
        'created_at'
	];

	public function notes()
	{
		return $this->hasMany(Note::class);
	}

	public function assignedTech()
	{
        $assCh = AssignmentChange::where('ticket_id', $this->id)->latest()->get()->first();

        if ( $assCh == null) {
            return null;
        } else {
            return User::where('id', $assCh->new_tech_id)->first();
        }
	}

    /**
     *
     *
     * @return Status
     */
	public function status()
    {
        $sc = StatusChange::where('ticket_id', $this->id)->latest()->get()->first();

        if ( $sc == null) {
            return Status::where('id', 1)->first();
        } else {
            return Status::where('id', $sc->status_id)->first();
        }
	}

	public function category()
	{
        //return $this->belongsTo(category::class);
        //return $this->belongsTo('App\Models\category');
        //dd($this->belongsTo('App\Models\category'));

        return Category::where('id', $this->category_id)->first();
	}

    public function user()
    {
        return User::find($this->user_id);
        //return $this->belongsTo(User::class);
    }

    public function duration()
    {
        if ($this->status()->id != 5) {
            return 0;
        }
        $sc = StatusChange::where('ticket_id', $this->id)->
            where('status_id',5)->
            latest()->get()->first();
        $duration=$sc->created_at->diffInSeconds($this->created_at);
        return gmdate('H:i:s', $duration);
    }
}
