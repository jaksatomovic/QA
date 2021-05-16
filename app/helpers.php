<?php

if (! function_exists('makeSlugFromTitle')) {
    /**
     * Create a conversation slug.
     *
     * @param   object  $model
     * @param   string  $title
     * @return  string
     */
    function makeSlugFromTitle($model, $title)
    {
        $count = 0;
        $slug = Str::slug($title);

        if ($model::where("slug", $slug)->count() > 0) {
            $count = $model::whereRaw("slug RLIKE '^{$slug}(-[0-9]+)?$'")->count();
            do {
                $count++;
            } while ($model::whereRaw("slug = '{$slug}-{$count}'")->count());
        }

        return $count ? "{$slug}-{$count}" : $slug;
    }
}
