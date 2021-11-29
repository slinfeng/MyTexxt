<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class FontFamilyType
 * 
 * @property int $id
 * @property string $font_family_name
 * @property string $font_family
 *
 * @package App\Models
 */
class FontFamilyType extends Model
{
	protected $table = 'font_family_type';
	public $timestamps = false;

	protected $fillable = [
		'font_family_name',
		'font_family'
	];
}
