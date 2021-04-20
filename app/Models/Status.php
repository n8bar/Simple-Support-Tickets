<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;
	protected $table = 'statuses';
	
	protected $casts = [
		
	];
	
	protected $fillable = [
		'name',
		'waiting_on_user'
	];
	
	public function tickets()
	{
		//todo: Replace this with a list of tickets where this status is the latest in status_changes.
		return $this->hasMany(status_change::class);
	}
	
}
