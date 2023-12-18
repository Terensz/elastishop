<div class="article-head">
    <div class="article-title"><?php echo trans('registration.successful.title'); ?></div>
</div>
<div class="article-teaser"><?php echo trans('registration.successful.body', [['from' => '[reg_email]', 'to' => $email]]); ?></div>
<script>
    $('#userRegistrationSubmitContainer').parent().parent().remove();
    $('#userRegistrationSubmitContainer').remove();
</script>
