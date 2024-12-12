<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\{
    Carbon,
    Collection,
};

/**
 * Model class for 'weather_history' table.
 *
 * @property int $id
 * @property string $name
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property Collection $weatherHistory
 */
class City extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cities';

    /**
     * The attributes that are mass assignable.
     *
     * @var array <int, string>
     */
    protected $fillable = [
        'name',
    ];

    public function weatherHistory(): HasMany
    {
        return $this->hasMany(WeatherHistory::class, 'city_id', 'id');
    }
}
