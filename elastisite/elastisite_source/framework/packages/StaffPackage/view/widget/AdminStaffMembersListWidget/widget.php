<div id="adminStaffMembers-list-container"></div>

<script>
    $('document').ready(function() {
        console.log('docready');
        AdminStaffMembers.list();
    });

    var StaffMemberModal = {
        switchTab: function(e, tabId) {
            if (e != null) {
                e.preventDefault();
            }

            $('.staffMemberTab').each(function() {
                if ($(this).attr('data-tabid') == tabId) {
                    $(this).addClass('active');

                    console.log('tabId:', tabId);

                    if ($(this).attr('data-tabid') == 'statsPages') {
                        AdminStaffMembers.loadStatsPages();
                    }

                    if ($(this).attr('data-tabid') == 'statsView') {
                        AdminStaffMembers.loadStatsView();
                    }

                    $('#adminStaffMembers-' + $(this).attr('data-tabid') + '-container').show();

                } else {
                    $(this).removeClass('active');
                    $('#adminStaffMembers-' + $(this).attr('data-tabid') + '-container').hide();
                }
            });
        }
    };

    var AdminStaffMembers = {
        list: function() {
            $.ajax({
                'type': 'POST',
                'url': '/admin/staff/member/list',
                'data': {
                },
                'async': true,
                'success': function(response) {
                    console.log(response);
                    $('#adminStaffMembers-list-container').html(response.view);
                },
                'error': function(request, error) {
                    console.log(request);
                    console.log(" Can't do because: " + error);
                },
            });
        },
        loadStatsPages: function() {
            console.log('AdminStaffMembers.loadStatsPages');
            $.ajax({
                'type': 'POST',
                'url': '/admin/staff/member/stats/pages',
                'data': {
                    'staffMemberId': $('#adminStaffMembers-staffMemberId').html()
                },
                'async': true,
                'success': function(response) {
                    console.log(response);
                    $('#adminStaffMembers-statsPages-container').html(response.view);
                },
                'error': function(request, error) {
                    console.log(request);
                    console.log(" Can't do because: " + error);
                },
            });
        },
        loadStatsView: function() {
            $.ajax({
                'type': 'POST',
                'url': '/admin/staff/member/stats/view',
                'data': {
                    'staffMemberId': $('#adminStaffMembers-staffMemberId').html()
                },
                'async': true,
                'success': function(response) {
                    console.log(response);
                    $('#adminStaffMembers-statsView-container').html(response.view);
                },
                'error': function(request, error) {
                    console.log(request);
                    console.log(" Can't do because: " + error);
                },
            });
        },
    };
</script>