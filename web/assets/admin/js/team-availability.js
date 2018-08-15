(function($) {
    "use strict";

    var $container = $('section.first-section'),
        count = []
    ;

    $container.find('[data-check-team-availability]').each(function() {
        var teamId = $(this).data('team-id');

        count[teamId] = 0;
        checkTeamAvailability(teamId, $(this));
    });

    function checkTeamAvailability(teamId, loginButton) {
        count[teamId]++;

        $.ajax({
            url: Routing.generate('main_team_check_availability', {'id': teamId}),
            method: 'GET',
        }).done(function(data) {
            if (data.available) {
                loginButton.parent().find('.spinner').hide();
                loginButton.show();
            } else {
                if (count[teamId] > 100) {
                    return;
                }

                setTimeout(checkTeamAvailability, 5000, teamId, loginButton);
            }
        });
    }
})(jQuery);
