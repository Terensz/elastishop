<?php

function trans($code, $placeholders = null, $locale = null)
{
    $translator = framework\kernel\base\Container::getSelfObject()->getService('Translator');
    if (!$locale) {
        $locale = framework\kernel\base\Container::getSelfObject()->getSession()->getLocale();
    }

    // dump($code);
    // dump($locale);//exit;

    $translation = $translator->getTranslation($code, $locale);

    if ($placeholders) {
        foreach ($placeholders as $placeholder) {
            if (empty($placeholder['from'])) {
                $placeholder['from'] = '';
            }
            if (empty($placeholder['to'])) {
                $placeholder['to'] = '';
            }
            $translation = str_replace($placeholder['from'], $placeholder['to'], $translation);
        }
    }

    return $translation;
}
