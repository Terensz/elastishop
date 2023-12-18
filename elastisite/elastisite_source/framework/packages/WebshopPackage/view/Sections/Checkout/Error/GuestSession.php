<?php 

?>

<div class="card">
    <div class="bg-primary text-white card-header d-flex justify-content-between align-items-center">
        <div class="card-header-textContainer">
            <h6 class="mb-0 text-white"><?php echo trans('error'); ?></h6>
        </div>
    </div>
    <div class="card-body">
        <span>
            A webáruházban a fizetéshez be kell jelentkezni.<br>
            <br>
            Ha nincs még felhasználói fiókja, <a class="ajaxCallerLink" href="" onclick="CustomRegistration.init(event);">ide kattintva tud regisztrálni</a>.<br>
            <br>
            Ha már van felhasználói fiókja, <a class="ajaxCallerLink" href="" onclick="LoginHandler.initLogin(event);">ide kattintva tud bejelentkezni</a>.
        </span>
    </div>
</div>