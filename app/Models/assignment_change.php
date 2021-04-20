<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class assignment_change extends Model
{
    use HasFactory;
	
	protected $table = 'assignment_changes';
	public $timestamps = true;
	
	protected $casts = [
		
	];
	
	protected $fillable = [
		
	];
	
	public function ticket()
	{
		return $this->belongsTo(Ticket::class, 'ticket_id');
	}
	
	public function changedBy() {
		return $this->belongsTo(User::class, 'changed_by_tech_id');
	}
	
	public function newTech() {
		return $this->belongsTo(User::class, 'new_tech_id');
	}
}
