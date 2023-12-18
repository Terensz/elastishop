<?php 

?>

<div class="pc-container">
    <div class="pcoded-content card-container">
        <div class="card">
            <div class="bg-primary text-white card-header d-flex justify-content-between align-items-center">
                <div class="card-header-textContainer">
                    <h6 class="mb-0 text-white">Meghívás visszaigazolása</h6>
                </div>
            </div>
            <div class="card-body">
            <?php if ($processedInviteConfirmation['success']): ?>
                <span>
                Ön meghívást kapott a(z) <b><?php echo $ascScaleTitle ?></b> admin skálához, hogy a jövőben az ezen dolgozó csapat tagja lehessen.<br>
                <?php if ($processedInviteConfirmation['pageRouteName'] == 'asc_inviteUser_registration'): ?>
                    Ehhez már csak egy lépés hiányzik: regisztrálnia kell. A regisztrációs kérdőív elkezdéséhez Kattintson a lentebb található "Regisztráció indítása" gombra!
                <?php endif; ?>
                </span>
            <?php else: ?>
                <span>
                Hiba: <?php echo $processedInviteConfirmation['message']; ?>
                </span>
            <?php endif; ?>
            </div>
        </div>
        <?php if ($processedInviteConfirmation['pageRouteName'] == 'asc_inviteUser_registration' && $processedInviteConfirmation['success']): ?>
        <div class="mb-4">
            <button type="button" onclick="CustomRegistration.init(event);" class="btn btn-success">Regisztráció indítása</button>
        </div>
        <?php endif; ?>
    </div>
</div>