<?php

namespace Afrittella\LaravelPages\Domain\Model;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

class Page extends Model
{
    use NodeTrait;

    protected $guarded = ['created_at', 'updated_at', '_lft', '_rgt'];

    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = $this->createSlug($value, $this->id);
    }

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = strip_tags($value);
    }

    public function setBodyAttribute($value)
    {
        $this->attributes['body'] = strip_tags($value);
    }

    public function createSlug(string $value, int $id = 0): string
    {
        $slug = str_slug($value);

        $relatedSlugs = $this->getRelatedSlugs($slug, $id);

        if (!$relatedSlugs->contains('slug', $slug)) {
            return $slug;
        }

        $completed = false;
        $i = 1;

        while ($completed === false) {
            $newSlug = $slug .'-'.$i;
            if (!$relatedSlugs->contains('slug', $newSlug)) {
                return $newSlug;
            }
            $i++;
        }

        return $slug;
    }

    /**
     * Get related slugs
     * @param $slug
     * @param $id
     * @return mixed
     */
    protected function getRelatedSlugs($slug, $id = 0): Collection
    {
        return $this->select('slug')
                ->where('slug', 'like', $slug.'%')
                ->where('id', '<>', $id)
                ->get();
    }
}
