<?php
namespace framework\packages\ArticlePackage\translation;

class Translation_huInf
{
    public function getTranslation()
    {
        return array(
            'article.not.found' => 'Ellenőrizd a webcímet vagy a szöveget, ahonnan kimásoltad,
                hogy nem maradt-e le véletlenül egy karakter a cím végéről.<br /><br />
                Ha van ötleted a cikkben szereplő kulcsszóra, használd a keresőnket.',
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
