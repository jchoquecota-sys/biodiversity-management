<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Publication extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'title',
        'abstract',
        'publication_year',
        'author',
        'journal',
        'doi',
        'pdf_path',
    ];

    /**
     * The biodiversity categories that belong to the publication.
     */
    public function biodiversityCategories()
    {
        return $this->belongsToMany(BiodiversityCategory::class, 'biodiversity_category_publication')
            ->withPivot('relevant_excerpt', 'page_reference')
            ->withTimestamps();
    }

    /**
     * Register media collections for the model.
     * Note: PDF collection removed - now using pdf_path field for direct storage
     */
    public function registerMediaCollections(): void
    {
        // PDF collection removed - now using pdf_path field
    }
    
    /**
     * Obtener publicaciones similares basadas en el mismo autor, año o revista.
     *
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getSimilarPublications($limit = 5)
    {
        return self::where('id', '!=', $this->id)
            ->where(function ($query) {
                $query->where('author', $this->author)
                    ->orWhere('publication_year', $this->publication_year)
                    ->orWhere('journal', $this->journal);
            })
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }

    /**
     * Scope para filtrar por año de publicación.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $year
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByYear($query, $year)
    {
        return $query->where('publication_year', $year);
    }

    /**
     * Scope para filtrar por autor.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $author
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByAuthor($query, $author)
    {
        return $query->where('author', 'LIKE', "%{$author}%");
    }

    /**
     * Scope para filtrar por revista/journal.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $journal
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByJournal($query, $journal)
    {
        return $query->where('journal', 'LIKE', "%{$journal}%");
    }

    /**
     * Scope para filtrar por categoría de biodiversidad relacionada.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $biodiversityCategoryId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByBiodiversityCategory($query, $biodiversityCategoryId)
    {
        return $query->whereHas('biodiversityCategories', function ($q) use ($biodiversityCategoryId) {
            $q->where('biodiversity_category_id', $biodiversityCategoryId);
        });
    }

    /**
     * Scope para buscar por término en título, autor, revista o resumen.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $term
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('title', 'LIKE', "%{$term}%")
                ->orWhere('author', 'LIKE', "%{$term}%")
                ->orWhere('journal', 'LIKE', "%{$term}%")
                ->orWhere('abstract', 'LIKE', "%{$term}%");
        });
    }
}