<?php
namespace framework\packages\ArticlePackage\translation;

class Translation_huFor
{
    public function getTranslation()
    {
        return array(
            'article.not.found' => 'Ellenőrizze a webcímet vagy a szöveget, ahonnan kimásolta,
                hogy nem maradt-e le véletlenül egy karakter a cím végéről.<br /><br />
                Ha van ötlete a cikkben szereplő kulcsszóra, használja a keresőnket.',
            'search.article' => 'Cikk keresése',
            'search.min.digits' => 'A keresési kifejezés legyen legalább 4 karakteres!',
            'search.no.result' => 'A keresés nem hozott eredményt',
            'read.more' => 'Tovább olvasom',
            'article' => 'Cikk',
            'add.hard.coded.article.slug' => 'Lefejlesztett cikk hozzáadása'
            // 'hard.coded.over.teaser' => 'Lefejlesztett cikk az ízelítő helyett'
        );
    }
}
