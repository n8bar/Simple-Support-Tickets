<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ticket extends Model
{
    use HasFactory;
	protected $table = 'tickets';
	
	protected $casts = [
		
	];
	
	protected $fillable = [
		'title',
		'details',
		'satisfaction'
	];
	
	public function notes()
	{
		return $this->hasMany(Note::class);
	}
	
	public function assignedTech()
	{
		return $this->hasMany(assignment_change::class);
		//Todo: Return the user from the latest assignment change instead.
	}
	
	public function status()
	{
		return $this->hasMany(status_change::class);
		//Todo: Return the status from the latest status change instead.
	}
	
	public function category()
	{
		return $this->belongsTo(Category::class);
	}
	
	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
