<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
	protected $table = 'categories';

	protected $casts = [

	];

	protected $fillable = [
		'categoryName',
		'description'
	];

	public function tickets()
	{
		return $this->hasMany(Ticket::class);
	}

	public function changedBy()
	{
		return $this->belongsTo(User::class, 'changed_by_tech_id');
	}

	public function newTech()
	{
		return $this->belongsTo(User::class, 'new_tech_id');
	}
}
