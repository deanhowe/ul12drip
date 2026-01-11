<?php

namespace App\Traits;

/**
 * Alias trait for HasTags.
 *
 * Use this trait when "Taggable" reads more naturally than "HasTags".
 * Both traits provide identical functionality.
 *
 * Usage:
 *   class Product extends Model
 *   {
 *       use Taggable;
 *   }
 *
 *   $product->tags;
 *   $product->attachTags(['sale', 'featured']);
 */
trait Taggable
{
    use HasTags;
}
