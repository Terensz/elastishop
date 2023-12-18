<div class="article-wrapper">
    <div class="article-container">

        <div class="article-head">
            <div class="article-info"><?php echo $article->getCreatedAt(); ?></div>
            <div class="article-title"><?php echo $article->getTitle(); ?></div>
        </div>

        <div class="articleBody"><?php
                $body = $article->getBody();
                $body = str_replace(array("\r\n", "\r", "\n"), "<br />", $body);
                // echo nl2br_indent(trim($teaser));
                echo trim(html_entity_decode($body));
        ?></div>
    </div>
</div>
