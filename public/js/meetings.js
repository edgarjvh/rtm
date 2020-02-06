$(document).ready(function () {

    $(document).on('click', '.tabs .tab', function (e) {
        let btn = $(this);
        let tabs = btn.closest('.tabs');
        let tabsContainer = $(document).find('.tabs-container');
        let name = btn.attr('data-name');
        tabs.find('.tab').removeClass('active');
        btn.addClass('active');

        tabsContainer.find('.tab-content').removeClass('active');
        tabsContainer.find('.tab-content.' + name).addClass('active');
    });

    $(document).on('change', '#cbox-set-send-rating-emails', function (e) {
        let input = $(this);
        let checked = input.is(':checked');
        let trow = input.closest('.trow');
        let loader = trow.find('.loader');

        loader.show();

        $.ajax({
            type: 'post',
            url: '/setSendingRatingEmails',
            dataType: 'json',
            data: {
                '_token': $('input[name=_token]').val(),
                'status': checked
            },
            success: function (response) {
                console.log(response);

                if (response.result === 'OK') {
                    loader.hide();
                }
            },
            error: function (a, b, c) {
                console.log('Error');
            }
        });
    });

    $(document).on('change', '#cbox-set-allow-sharing', function (e) {
        let input = $(this);
        let checked = input.is(':checked');
        let trow = input.closest('.trow');
        let loader = trow.find('.loader');

        loader.show();

        $.ajax({
            type: 'post',
            url: '/setSharingMeetingScore',
            dataType: 'json',
            data: {
                '_token': $('input[name=_token]').val(),
                'status': checked
            },
            success: function (response) {
                console.log(response);

                if (response.result === 'OK') {
                    loader.hide();
                }
            },
            error: function (a, b, c) {
                console.log('Error');
            }
        });
    });

    $(document).on('click', '.exclusion .times', function (e) {
        $(this).closest('.exclusion').remove();
        $('.btn-add-exclusion').removeClass('disabled');
    });

    $(document).on('click', '.exclusion .check', function (e) {
        let exclusion = $(this).closest('.exclusion');
        let input = exclusion.find('.input');

        if (!validateEmail(input.text().trim())) {
            alert("You have entered an invalid email address!");
            return;
        }

        $.ajax({
            type: 'post',
            url: '/saveExclusion',
            dataType: 'json',
            data: {
                '_token': $('input[name=_token]').val(),
                'email': input.text().trim()
            },
            success: function (response) {
                if (response.result === 'OK') {
                    input.attr('contenteditable', false);
                    $('.btn-add-exclusion').removeClass('disabled');
                }

                if (response.result === 'DUPLICATED') {
                    alert("Exclusion already exists");
                }
            },
            error: function (a, b, c) {
                console.log('Error');
            }
        })
    });

    $(document).on('click', '.exclusion .minus', function (e) {
        if (confirm('Are you sure to remove the selected exclusion?')) {
            let exclusion = $(this).closest('.exclusion');
            let input = exclusion.find('.input');

            $.ajax({
                type: 'post',
                url: '/deleteExclusion',
                dataType: 'json',
                data: {
                    '_token': $('input[name=_token]').val(),
                    'email': input.text().trim()
                },
                success: function (response) {
                    if (response.result === 'OK') {
                        exclusion.remove();
                        $('.btn-add-exclusion').removeClass('disabled');
                    }
                },
                error: function (a, b, c) {
                    console.log('Error');
                }
            })
        }
    });

    $(document).on('click', '.btn-add-exclusion', function (e) {
        let container = $(this).closest('.exclusions-container');
        let list = container.find('.excluded-email-list');

        let exclusion = `
            <div class="exclusion">
                <div class="input" contenteditable="true"></div>

                <div class="actions">
                    <span class="fas fa-check-circle check" title="save"></span>
                    <span class="fas fa-minus-circle minus" title="delete"></span>
                    <span class="fas fa-times-circle times" title="cancel"></span>
                </div>
            </div>
        `;

        list.append(exclusion);

        $(this).addClass('disabled');
        list.find('.exclusion:last-child .input').focus();
    });

    loadMeetings();
});

function getTeam() {

}

function loadMeetings() {
    $.ajax({
        type: 'get',
        url: '/getMeetings',
        dataType: 'json',
        success: function (response) {
            if (response.result === 'ok') {
                let meetings = '';

                for (let i = 0; i < response.meetings.length; i++) {
                    let row = response.meetings[i];

                    let meeting = `
                        <tr>
                        <td class="align-middle">
                        <img src="img/` + row.provider.toLowerCase() + `.png" alt=""
                        style="width:16px; margin-top:-5px">
</td>
<td class="align-middle">
                                        <b>` + row.name + `</b>
                                        <br>
                                        <small>` + row.organizer + `</small>
                                    </td>
                        
</tr>
                   `
                }

            }
        },
        error: function (a, b, c) {
            console.log('error');
        }
    })
}

function validateEmail(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}