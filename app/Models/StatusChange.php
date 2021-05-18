<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusChange extends Model
{
    use HasFactory;
	protected $table = 'status_changes';
	public $timestamps = true;

	protected $casts = [

	];

	protected $fillable = [
        'ticket_id',
        'changed_by_tech_id',
		'status_id',
        'created_at'
	];

	public function ticket()
	{
		return $this->belongsTo(Ticket::class, 'ticket_id');
	}

	public function changedBy() {
		return $this->belongsTo(User::class, 'changed_by_tech_id');
	}

	public function newStatus() {
		return $this->belongsTo(Status::class, 'new_status_id');
	}
}
