<style>
* {
  box-sizing: border-box;
}

/* Position the image container (needed to position the left and right arrows) */
.slideshow-container {
  position: relative;
}

/* Hide the images by default */
.slideshow-bigImage-container {
  display: none;
}

.slideshow-bigImages-frame {
    background-color: #c0c0c0;
    /* background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/xQAAukB5vvocQUAAAAASUVORK5CYII=') !important; */
}

/* Add a pointer when hovering over the thumbnail images */
.cursor {
  cursor: pointer;
}

/* Next & previous buttons */
.prev,
.next {
  cursor: pointer;
  position: absolute;
  top: 40%;
  width: auto;
  padding: 16px;
  margin-top: -50px;
  color: white;
  font-weight: bold;
  font-size: 20px;
  border-radius: 0 3px 3px 0;
  user-select: none;
  -webkit-user-select: none;
}

/* Position the "next button" to the right */
.next {
  right: 0px;
  border-radius: 3px 0 0 3px;
  padding: 16px;
}

/* On hover, add a black background color with a little bit see-through */
.prev:hover,
.next:hover {
  background-color: #a2a2a2;
}

/* Number text (1/3 etc) */
.numbertext {
  color: #f2f2f2;
  font-size: 12px;
  padding: 8px 12px;
  position: absolute;
  top: 0;
}

/* Container for image text */
.caption-container {
  text-align: center;
  background-color: #222;
  padding: 2px 16px;
  color: white;
}

.row:after {
  content: "";
  display: table;
  clear: both;
}

/* Six columns side by side */
.slideshow-thumbnail-column {
  float: left;
  /* width: 16.66%; */
  border: 1px solid #c0c0c0;
}

/* Add a transparency effect for thumnbail images */
.demo {
  opacity: 0.6;
}

.active,
.demo:hover {
  opacity: 1;
}

.slideshow-bigImage {
    display: block;
    margin-left: auto;
    margin-right: auto;
    width: auto;
    height: 400px;
}

.slideshow-thumbnail {
    width: auto;
    max-height: 120px;
}
</style>

<!-- Container for the image gallery -->
<div class="slideshow-container">

    <div class="slideshow-bigImages-frame">
<?php 
foreach ($productImages as $productImage):
?>
        <div class="slideshow-bigImage-container">
        <!-- <div class="numbertext">X / 6</div> -->
            <img class="slideshow-bigImage" src="/webshop/image/big/<?php echo $productImage->getSlug(); ?>">
        </div>
<?php 
endforeach;
?>
    </div>

    <?php if (count($productImages) > 1): ?>
    <!-- Next and previous buttons -->
    <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
    <a class="next" onclick="plusSlides(1)">&#10095;</a>
    <?php endif; ?>

    <!-- Image text -->
    <!-- <div class="caption-container">
    <p id="caption"></p>
    </div> -->
    <div style="border-bottom: 1px solid #c0c0c0;">
    </div>

    <!-- Thumbnail images -->
    <div class="row">
<?php 
$selectedSlideCounter = 0;
for ($i = 0; $i < count($productImages); $i++):
  if ($selectedImageId && $selectedImageId == $productImages[$i]->getId()) {
    $selectedSlideCounter = $i + 1;
  }
?>
        <div class="slideshow-thumbnail-column">
            <img class="demo cursor slideshow-thumbnail" src="<?php echo $httpDomain; ?>/webshop/image/thumbnail/<?php echo $productImages[$i]->getSlug(); ?>" 
                onclick="currentSlide(<?php echo ($i + 1); ?>)" alt="image-<?php echo $i ?>">
        </div>
<?php 
endfor;
?>
    </div>
</div>

<script>
var slideIndex = 1;
showSlides(slideIndex);

// Next/previous controls
function plusSlides(n) {
  showSlides(slideIndex += n);
}

// Thumbnail image controls
function currentSlide(n) {
  showSlides(slideIndex = n);
}

function showSlides(n) {
  var i;
  var slides = document.getElementsByClassName("slideshow-bigImage-container");
  var dots = document.getElementsByClassName("demo");
  var captionText = document.getElementById("caption");
  if (n > slides.length) {slideIndex = 1}
  if (n < 1) {slideIndex = slides.length}
  for (i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";
  }
  for (i = 0; i < dots.length; i++) {
    dots[i].className = dots[i].className.replace(" active", "");
  }
  slides[slideIndex-1].style.display = "block";
  dots[slideIndex-1].className += " active";
  if (captionText) {
    captionText.innerHTML = dots[slideIndex-1].alt;
  }
}

$('document').ready(function() {
  <?php if ($selectedImageId): ?>
    currentSlide(<?php echo $selectedSlideCounter; ?>);
  <?php endif; ?>
});
</script>