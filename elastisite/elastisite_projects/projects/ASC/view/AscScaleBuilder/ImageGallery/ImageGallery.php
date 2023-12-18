
<div class="imageGallery-overlay" id="imageGallery-overlay"></div>
<div class="imageGallery-container" id="imageGallery-container" style="display: none;">
    <div class="imageGallery-flex-wrapper" style="display: flex; flex-direction: column; justify-content: space-between; align-items: stretch;">
        <div class="imageGallery-header">
            <div class="modal-header" style="width: 100%;">
                <h5 class="modal-title" id="editorModalLabel">Galéria</h5>
                <button type="button" onclick="AscImageGallery.closeGallery();" class="btn-close" aria-label="Close"></button>
            </div>
        </div>
        <div class="imageGallery-thumbnails card-footer" id="imageGallery-thumbnails">
            <img src="/asc/unitImage/thumbnail/rvaw6xtjan6zftxvfmvb">
            <img src="/asc/unitImage/thumbnail/ep0d9s43wghvrnfnwpix">
        </div>
    </div>
</div>

<style>
    /* Teljes képernyő overlay stílus */
    .imageGallery-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        /* background-color: rgba(0, 0, 0, 0.9); */
        z-index: 1099;
    }

    /* Galéria konténer stílus */
    .imageGallery-container {
        background-color: #e7e7e7;
        display: none;
        position: fixed;
        top: 20px;
        bottom: 20px;
        left: 20px;
        right: 20px;
        /* height: 100%; */
        /* transform: translate(-50%, -50%); */
        z-index: 1100;
    }

    /* Galéria fejléc stílus */
    .imageGallery-header {
        /* background-color: #333; */
        color: white;
        padding: 10px;
        display: flex;
        justify-content: space-between;
    }

    /* Galéria lábléc stílus */
    .imageGallery-footer {
        /* background-color: #333; */
        color: white;
        padding: 10px;
        text-align: center;
    }

    /* Bezáró gomb stílus */
    .imageGallery-close-button {
        color: white;
        cursor: pointer;
    }

    /* Thumbnail-ök stílus */
    .imageGallery-thumbnails {
        display: flex;
        justify-content: center;
        overflow-x: auto;
    }

    .imageGallery-thumbnails img {
        max-height: 100px;
        margin: 0 10px;
        cursor: pointer;
    }

    /* Aktív kép megjelenítés stílusa */
    .imageGallery-active-image {
        display: block;
        max-width: 100%;
        max-height: 80vh;
        margin: 0 auto;
    }
</style>
<script>
    var AscImageGallery = {
        images: [], // Képek tömbje

        // Galéria inicializálása
        init: function () {

        },

        openGallery: function () {
            // Galéria megjelenítési állapotának beállítása
            this.toggleGallery(true);

            // Bezáró gomb eseménykezelése
            // var closeButton = document.getElementById('imageGallery-closeButton');
            // closeButton.addEventListener('click', this.closeGallery.bind(this));

            // Képek betöltése a galériába
            // this.loadImages();
        },

        // Kép megjelenítése
        showImage: function (event) {
            var index = event.target.dataset.index;
            var activeImage = document.getElementById('imageGallery-activeImage');
            activeImage.src = this.images[index];
            activeImage.dataset.index = index;
            this.toggleGallery(true);
        },

        // Galéria bezárása
        closeGallery: function () {
            this.toggleGallery(false);
        },

        // Galéria megjelenítési állapotának váltása
        toggleGallery: function (show) {
            var overlay = document.getElementById('imageGallery-overlay');
            var gallery = document.getElementById('imageGallery-container');
            overlay.style.display = show ? 'block' : 'none';
            gallery.style.display = show ? 'block' : 'none';
        }
    };

    $(document).ready(function() {
        // Kattintás eseménykezelő hozzáadása az imageGallery-overlay elemhez
        $('#imageGallery-overlay').off('click');
        $('#imageGallery-overlay').on('click', function(event) {
            // Ellenőrizze, hogy a kattintás az overlay-en történt-e, és nem a galeria tartalmán
            if (event.target.id === 'imageGallery-overlay') {
                AscImageGallery.closeGallery();
            }
        });
    });
</script>