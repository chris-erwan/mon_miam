<?php

namespace App\Modules\Fidelite\Models;

use App\Modules\Auth\Models\Etudiant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Referral extends Model
{
    protected $table = 'parrainages';

    protected $primaryKey = 'id_parrainage';

    public $timestamps = false;

    protected $fillable = [
        'id_parrain', 'id_filleul', 'date_parrainage',
        'recompense_attribuee', 'points_recompense', 'date_recompense',
    ];

    protected $casts = [
        'recompense_attribuee' => 'boolean',
        'points_recompense'    => 'integer',
        'date_parrainage'      => 'datetime',
        'date_recompense'      => 'datetime',
    ];

    public function referrer(): BelongsTo
    {
        return $this->belongsTo(Etudiant::class, 'id_parrain');
    }

    public function referee(): BelongsTo
    {
        return $this->belongsTo(Etudiant::class, 'id_filleul');
    }
}