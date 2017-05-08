<?php

/**
 * @method static LanguageManager getManager()
 */
class Language extends fvRoot
{

    public static function getEntity()
    {
        return __CLASS__;
    }

    function isCurrent()
    {
        return $this == self::getManager()->getCurrentLanguage();
    }
}
