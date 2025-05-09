<?php

namespace App\Models;

use App\Contracts\FileableInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Banca extends Model implements FileableInterface
{
    use HasFactory, SoftDeletes;

    protected $jsonOptions = JSON_UNESCAPED_UNICODE;

    protected $fillable = [
        'nome',
        'descricao',
        'horarios_funcionamento',
        'preco_minimo',
        'entrega',
        'feira_id',
        'agricultor_id',
        'pix'
    ];

    protected $casts = [
        'horarios_funcionamento' => 'array'
    ];

    protected function asJson($value): string
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }

    public function isOpen(): bool
    {
        $map = [
            'Sunday'    => 'domingo',
            'Monday'    => 'segunda-feira',
            'Tuesday'   => 'terca-feira',
            'Wednesday' => 'quarta-feira',
            'Thursday'  => 'quinta-feira',
            'Friday'    => 'sexta-feira',
            'Saturday'  => 'sábado',
        ];

        $hojeEn = Carbon::now()->format('l');
        $diaPt = $map[$hojeEn] ?? null;

        if (! $diaPt || ! isset($this->horarios_funcionamento[$diaPt])) {
            return false;
        }

        [$ab, $fe] = $this->horarios_funcionamento[$diaPt];
        $ab = Carbon::createFromFormat('H:i', $ab);
        $fe = Carbon::createFromFormat('H:i', $fe);

        return Carbon::now()->between($ab, $fe, true);
    }

    public function feira(): BelongsTo
    {
        return $this->belongsTo(Feira::class);
    }

    public function produtos(): HasMany
    {
        return $this->hasMany(Produto::class);
    }

    public function agricultor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agricultor_id');
    }

    public function file(): MorphOne
    {
        return $this->morphOne(File::class, 'fileable');
    }

    public function formasPagamento(): BelongsToMany
    {
        return $this->belongsToMany(FormaPagamento::class)->withTimestamps();
    }

    public function vendas(): HasMany
    {
        return $this->hasMany(Venda::class);
    }

    public function bairros_info_entrega(): BelongsToMany
    {
        return $this->belongsToMany(Bairro::class)->withPivot('taxa')->withTimestamps();
    }
}
