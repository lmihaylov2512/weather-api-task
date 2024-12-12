<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\{
    Model,
    Builder
};
use Illuminate\Support\Carbon;

/**
 * Model class for 'weather_history' table.
 *
 * @property int $id
 * @property int $city_id
 * @property int $avg_temperature
 * @property Carbon $date
 * @property Carbon $created_at
 *
 * @property City $city
 */
class WeatherHistory extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'weather_history';

    /**
     * The attributes that are mass assignable.
     *
     * @var array <int, string>
     */
    protected $fillable = [
        'city_id',
        'avg_temperature',
        'date',
        'created_at',
    ];

    protected $casts = [
        'date' => 'date',
        'created_at' => 'timestamp',
    ];

    public $timestamps = false;

    protected static function booted(): void
    {
        static::creating(function (WeatherHistory $model) {
            $model->created_at = now();
        });
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }

    public function scopeLastDays(Builder $builder, int $days = 10): Builder
    {
        return $builder->where('date', '>=', Carbon::now()->subDays($days+1));
    }
}
