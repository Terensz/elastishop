<?php  
// dump($settings);
?>
<footer class="text-center text-white" style="background-color: #f1f1f1;">
  <!-- Grid container -->
  <div class="container pt-4">
    <!-- Section: Social media -->
    <section class="mb-4">
      <?php if (!empty($settings['FooterPackage_facebookLink'])): ?>
      <!-- Facebook -->
      <a
        class="btn btn-link btn-floating btn-lg text-dark m-1"
        href="<?php echo $settings['FooterPackage_facebookLink']; ?>"
        target="_blank"
        role="button"
        data-mdb-ripple-color="dark"
        ><i class="fab fa-facebook-f"></i
      ></a>
      <?php endif; ?>

      <?php if (!empty($settings['FooterPackage_twitterLink'])): ?>
      <!-- Twitter -->
      <a
        class="btn btn-link btn-floating btn-lg text-dark m-1"
        href="<?php echo $settings['FooterPackage_twitterLink']; ?>"
        target="_blank"
        role="button"
        data-mdb-ripple-color="dark"
        ><i class="fab fa-twitter"></i
      ></a>
      <?php endif; ?>

      <?php if (!empty($settings['FooterPackage_googleLink'])): ?>
      <!-- Google -->
      <a
        class="btn btn-link btn-floating btn-lg text-dark m-1"
        href="<?php echo $settings['FooterPackage_googleLink']; ?>"
        target="_blank"
        role="button"
        data-mdb-ripple-color="dark"
        ><i class="fab fa-google"></i
      ></a>
      <?php endif; ?>

      <?php if (!empty($settings['FooterPackage_instagramLink'])): ?>
      <!-- Instagram -->
      <a
        class="btn btn-link btn-floating btn-lg text-dark m-1"
        href="<?php echo $settings['FooterPackage_instagramLink']; ?>"
        target="_blank"
        role="button"
        data-mdb-ripple-color="dark"
        ><i class="fab fa-instagram"></i
      ></a>
      <?php endif; ?>
      
      <?php if (!empty($settings['FooterPackage_linkedinLink'])): ?>
      <!-- Linkedin -->
      <a
        class="btn btn-link btn-floating btn-lg text-dark m-1"
        href="<?php echo $settings['FooterPackage_linkedinLink']; ?>"
        target="_blank"
        role="button"
        data-mdb-ripple-color="dark"
        ><i class="fab fa-linkedin"></i
      ></a>
      <?php endif; ?>

      <?php if (!empty($settings['FooterPackage_githubLink'])): ?>
      <!-- Github -->
      <a
        class="btn btn-link btn-floating btn-lg text-dark m-1"
        href="<?php echo $settings['FooterPackage_githubLink']; ?>"
        target="_blank"
        role="button"
        data-mdb-ripple-color="dark"
        ><i class="fab fa-github"></i
      ></a>
      <?php endif; ?>
    </section>
    <!-- Section: Social media -->
  </div>
  <!-- Grid container -->

  <!-- Copyright -->
  <?php if (!empty($settings['FooterPackage_ownWebsiteLink']) && !empty($settings['FooterPackage_ownWebsiteName'])): ?>
  <div class="text-center text-dark p-3" style="background-color: rgba(0, 0, 0, 0.2);">
    <a class="text-dark" href="<?php echo $settings['FooterPackage_ownWebsiteLink']; ?>" target="_blank"><?php echo $settings['FooterPackage_ownWebsiteName']; ?></a>
  </div>
  <?php endif; ?>
  <!-- Copyright -->
</footer>