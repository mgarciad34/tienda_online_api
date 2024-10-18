<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
/**
 * Class Usuario
 *
 * @property int $id
 * @property string $nombre
 * @property string $email
 * @property string $contrasena
 * @property string $rol
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Collection|Cesta[] $cestas
 *
 * @package App\Models
 */
class Usuario extends Model
{
	protected $table = 'usuarios';

    use HasApiTokens;

	protected $fillable = [
		'nombre',
		'email',
		'contrasena',
		'rol'
	];

	public function cestas()
	{
		return $this->hasMany(Cesta::class);
	}
}
