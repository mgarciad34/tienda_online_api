<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Cesta
 *
 * @property int $id
 * @property int $usuario_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property float $total
 * @property string $estado
 *
 * @property User $usuario
 * @property Collection|CestaDetalle[] $cesta_detalles
 *
 * @package App\Models
 */
class Cesta extends Model
{
	protected $table = 'cestas';

	protected $casts = [
		'usuario_id' => 'int',
		'total' => 'float'
	];

	protected $fillable = [
		'usuario_id',
		'total',
		'estado'
	];

	public function usuario()
	{
		return $this->belongsTo(User::class);
	}

	public function cesta_detalles()
	{
		return $this->hasMany(CestaDetalle::class);
	}
}
