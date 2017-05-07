<?php

/**
 * Created by cah4a.
 * Time: 10:59
 * Date: 22.11.15
 *
 * @property Field_Bool $error
 * @property Field_Int $counter
 * @property Field_Datetime_Mtime $mtime
 */
class CounterCache extends fvRoot
{

    const SOCIAL_FB = "fb";
    const SOCIAL_TWI = "twi";
    const SOCIAL_VK = "vk";

    const CACHE_TIME = 5; // 5 minutes

    static function getFb( Album $album ){
        return self::get( self::SOCIAL_FB, $album, function() use ($album){
            $url = fvUrlGenerator::get("album", [ "album" => $album ], true);

            $request = new fvHttpRequest("https://graph.facebook.com/{$url}");
            $request->request();

            return @(json_decode($request->getResultContent())->shares);
        } );
    }

    static function getVk( Album $album ){
        return self::get( self::SOCIAL_VK, $album, function() use ($album){
            $url = fvUrlGenerator::get("album", [ "album" => $album ], true);

            $request = new fvHttpRequest("https://vk.com/share.php?act=count&index=1&format=json&url={$url}");
            $request->request();

            if( preg_match("/VK\\.Share\\.count\\(\\d+, (\\d+)\\);/", $request->getResultContent(), $matches) ){
                return $matches[1];
            }

            return null;
        } );
    }

    static private function get( $social, Album $album, Callable $get ){
        $params = [ "social" => $social, "albumId" => $album->getId() ];

        $cache = self::find($params);

        if( $cache instanceof CounterCache ){
            if( time() - $cache->mtime->asTimestamp() < self::CACHE_TIME * 60 ){
                return $cache->counter->get();
            }
        } else {
            $cache = new CounterCache($params);
        }

        $shares = $get();

        if( is_numeric($shares) ){
            $cache->counter = $shares;
            $cache->error = false;
        } else {
            $cache->error = true;
        }

        $cache->save();

        return $cache->counter->get();
    }

}