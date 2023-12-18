var Translator = {
    locale: 'hu',
    vocabulary: {
        'Click to Bold': {
            hu: 'Félkövér'
        },
        'Click to Italic': {
            hu: 'Dőlt'
        },
        'Click to Underline': {
            hu: 'Aláhúzott'
        },
        'Left Align': {
            hu: 'Balra igazítás'
        },
        'Center Align': {
            hu: 'Középre igazítás'
        },
        'Right Align': {
            hu: 'Jobbra igazítás'
        },
        'Justify Align': {
            hu: 'Sorkizárás'
        },
        'Insert Ordered List': {
            hu: 'Sorszámozott lista'
        },
        'Insert Unordered List': {
            hu: 'Sorszámozatlan lista'
        },
        'Click to Subscript': {
            hu: 'Alsó index'
        },
        'Click to Superscript': {
            hu: 'Felső index'
        },
        'Click to Strike Through': {
            hu: 'Áthúzás'
        },
        'Remove Formatting': {
            hu: 'Formázás eltávolítása'
        },
        'Indent Text': {
            hu: 'Szöveg behúzása'
        },
        'Remove Indent': {
            hu: 'Behúzás eltávolítása'
        },
        'Horizontal Rule': {
            hu: 'Vízszintes vonal'
        },
        'Select Font Size': {
            hu: 'Válasszon betűméretet'
        },
        'Select Font Family': {
            hu: 'Válasszon betűtípust'
        },
        'Select Font Format': {
            hu: 'Válasszon betűformátumot'
        },
        'Change Text Color': {
            hu: 'Betűszín módosítása'
        },
        'Change Background Color': {
            hu: 'Háttérszín módosítása'
        },
        'Save this content': {
            hu: 'Tartalom mentése'
        },
        'Font&nbsp;Size...': {
            hu: 'Betűméret...'
        },
        'Font&nbsp;Family...': {
            hu: 'Betűtípus'
        },
        'Font&nbsp;Format...': {
            hu: 'Betűformátum'
        },
        'Edit HTML': {
            hu: 'HTML szerkesztése'
        },
        'Add Image': {
            hu: 'Kép linkelése'
        },
        'Upload Image': {
            hu: 'Kép feltöltése'
        },
        'Image uploads are not supported in this browser, use Chrome, Firefox, or Safari instead.': {
            hu: 'Nem támogatott böngésző. Ezeket támogatjuk: Firefox, Chrome, Safari'
        },
        'Failed to upload image': {
            hu: 'A képfeltöltés meghiúsult'
        },
        'Only image files can be uploaded': {
            hu: 'Csak kép típusű file tölthető fel'
        },
        'Add/Edit Link': {
            hu: 'Link hozzáadása /szerkesztése'
        },
        'Title': {
            hu: 'Cím'
        },
        'Add Link': {
            hu: 'Link hozzáadása'
        },
        'Remove Link': {
            hu: 'Link eltávolítása'
        },
        'Open In': {
            hu: 'Cél'
        },
        'Current Tab': {
            hu: 'Ez a tab'
        },
        'New Tab': {
            hu: 'Új tab'
        },
        'You must enter a URL to Create a Link': {
            hu: 'Link készítéséhez be kell írnia egy URL-t'
        },
        'Click to mark Word explain': {
            hu: 'Szómagyarázat megjelölése'
        }
    },
    trans: function(code) {
        // console.log('Translator.trans');
        var loc = Translator.locale;
        // console.log('loc:', Translator.vocabulary[code]);
        // console.log('loc2:', Translator.vocabulary[code][Translator.locale]);
        if (typeof(Translator.vocabulary[code][Translator.locale]) !== 'undefined') {
            return Translator.vocabulary[code][Translator.locale];
        } else {
            return code;
        }
    }
};

// var TextareaEditorHelper = {
//     copyWysiwygToTextarea: function() {
//         var content = nicEditors.findEditor("ArticlePackage_article_teaser").getContent();
//         $('#ArticlePackage_article_teaser').html(content);
//     },
//     save: function(articleId) {
//         ArticleEditPanel.copyTeaserToTextarea();
//         ArticleEditPanel.copyBodyToTextarea();
//         ArticleEdit.save(articleId);
//     }
// };