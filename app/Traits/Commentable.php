<?php

namespace App\Traits;

/**
 * Alias trait for HasComments.
 *
 * Use this trait when "Commentable" reads more naturally than "HasComments".
 * Both traits provide identical functionality.
 *
 * Usage:
 *   class Video extends Model
 *   {
 *       use Commentable;
 *   }
 *
 *   $video->comments;
 *   $video->addComment('Great video!');
 */
trait Commentable
{
    use HasComments;
}
