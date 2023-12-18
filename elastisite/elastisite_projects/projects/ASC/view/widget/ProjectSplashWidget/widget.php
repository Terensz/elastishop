<!-- Project Splash!! -->

<div id="Splash-container">
    <img class="Splash-image" src="/image/ASC_wallpaper.jpg">
</div>

<style>
.Splash-container {
  display: flex; /* Vagy: display: grid; */
  justify-content: center; /* Kép középre igazítása vízszintesen */
  align-items: center; /* Kép középre igazítása függőlegesen */
}

.Splash-image {
  width: 100%; /* A kép szélessége mindig a konténer szélességéhez igazodik */
  height: auto; /* A magasság automatikusan alkalmazkodik az arányhoz */
  max-width: 100%; /* Az esetleges túl nagy képek kezeléséhez */
}
</style>
