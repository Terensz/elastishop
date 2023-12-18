<?php
namespace framework\packages\NewsletterPackage\translation;

class Translation_en
{
    public function getTranslation()
    {
        return array(
            'newsletter' => 'Newsletter',

            'newsletter.administration' => 'Newsletter administration',

            'admin.newsletters' => 'Newsletters',
            'create.new.newsletter' => 'Create new newsletter',
            'edit.newsletter' => 'Edit newsletter',
            
            'newsletter.campaign' => 'Newsletter campaign',
            'admin.newsletter.campaigns' => 'Newsletter campaigns',
            'create.new.newsletter.campaign' => 'Create new newsletter campaign',
            'edit.newsletter.campaign' => 'Edit newsletter campaign',

            'admin.newsletter.dispatch.processes' => 'Newsletter dispatch processes',
            'create.new.newsletter.dispatch.process' => 'Create new newsletter dispatch process',
            'edit.newsletter.dispatch.process' => 'Edit newsletter dispatch process',
            'total.dispatches.count' => 'Total count of dispatches',
            'dispatches.sent' => 'Sent dispatches',
            'newsletter.create.dispatch.process.info' => '',
            // 'admin.newsletter.init.campaign' => 'Init newsletter campaign',
            'admin.newsletter.process.sending' => 'Newsletter campaign processor',
            'newsletter.dispatch.process.status.info' => 'The "Active" dispatch processes will automatically join the dispatch queue, when you enter the "Newsletter campaign processor".
                The "Paused" processes will appear in the "Newsletter campaign processor", but will be inactives until you change theit statuses to "Active".
                The "Inactive" processes will not appear in the "Newsletter campaign processor". You can only activate them here, in this menu option, under this info box.',
            'newsletter.create.dispatch.process.create.info' => 'If you save this process, the software will prepare the dispatches for all subscribed users.',
            'created' => 'Created',
            'paused' => 'Paused'
        );
    }
}
