<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CestaDetalle
 * 
 * @property int $id
 * @property int $cesta_id
 * @property int $producto_id
 * @property int $cantidad
 * @property float $precio_unitario
 * @property float $subtotal
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Cesta $cesta
 * @property Producto $producto
 *
 * @package App\Models
 */
class CestaDetalle extends Model
{
	protected $table = 'cesta_detalles';

	protected $casts = [
		'cesta_id' => 'int',
		'producto_id' => 'int',
		'cantidad' => 'int',
		'precio_unitario' => 'float',
		'subtotal' => 'float'
	];

	protected $fillable = [
		'cesta_id',
		'producto_id',
		'cantidad',
		'precio_unitario',
		'subtotal'
	];

	public function cesta()
	{
		return $this->belongsTo(Cesta::class);
	}

	public function producto()
	{
		return $this->belongsTo(Producto::class);
	}
}
