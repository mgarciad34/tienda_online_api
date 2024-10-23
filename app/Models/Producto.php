<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Producto
 *
 * @property int $id
 * @property string $nombre
 * @property string $img1
 * @property string $img2
 * @property string $img3
 * @property string $descripcion
 * @property float $precio
 * @property int $existencias
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $categoria_id
 *
 * @property Categoria $categoria
 * @property Collection|CestaDetalle[] $cesta_detalles
 *
 * @package App\Models
 */
class Producto extends Model
{
	protected $table = 'productos';

    protected $casts = [
        'precio' => 'float',
        'existencias' => 'int',
        'categoria_id' => 'int'
    ];

    protected $fillable = [
        'nombre',
        'img1',
        'img2',
        'img3',
        'descripcion',
        'precio',
        'existencias',
        'categoria_id'
    ];
	public function categoria()
	{
		return $this->belongsTo(Categoria::class);
	}

	public function cesta_detalles()
	{
		return $this->hasMany(CestaDetalle::class);
	}

}
