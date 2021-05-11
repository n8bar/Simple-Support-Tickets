<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class note extends Model
{
    use HasFactory;
	protected $table = 'notes';
	public $timestamps = true;

	protected $casts = [

	];

	protected $fillable = [
		'note',
        'ticket_id',
        'user_id'
	];

	public function ticket()
	{
		return $this->belongsTo(Ticket::class);
	}
}
