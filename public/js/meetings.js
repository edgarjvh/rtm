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

    $(document).on('change', '#cbox-set-send-rating-emails-attended', function (e) {
        let input = $(this);
        let checked = input.is(':checked');
        let trow = input.closest('.trow');
        let loader = trow.find('.loader');

        loader.show();

        $.ajax({
            type: 'post',
            url: '/setSendingRatingEmailsAttended',
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

    $(document).on('click', '.tbtn.delete-account', function (e) {
        $(document).find('.modal-container').fadeIn(500);
    });

    $(document).on('click', '.modal-cancel-btn', function (e) {
        $(document).find('.modal-container').fadeOut(500);
    });

    $(document).on('click', '.modal-delete-btn', function (e) {
        $.ajax({
            type: 'post',
            url: '/deleteAccount',
            dataType: 'json',
            data: {
                '_token': $('input[name=_token]').val()
            },
            success: function (response) {
                if (response.logout === 'success') {
                    window.location.href = '/login';
                }
            },
            error: function (a, b, c) {
                console.log('Error');
            }
        })
    });

    $(document).on('click', '.btn-comment-mobile', function (e) {
        let tableMobile = $(this).closest('.table-mobile');
        let row = $(this).closest('.trow');
        let eventInfo = row.find('.event-info');
        let eventComments = row.find('.event-comments');
        let eventId = eventInfo.find('.tcol-line.event-id').text();
        let btnLoading = row.find('.btn-loading');

        if (eventComments.hasClass('shown')) {
            eventComments.slideUp();
            eventComments.removeClass('shown');
        } else {
            btnLoading.show();
            $.ajax({
                type: 'post',
                url: '/getComments',
                dataType: 'json',
                data: {
                    '_token': $('input[name=_token]').val(),
                    'eventId': eventId
                },
                success: function (response) {
                    console.log(response);

                    if (response.comments.length > 0) {

                        let htmlcomments = '';

                        for (let i = 0; i < response.comments.length; i++) {
                            let comment = response.comments[i];

                            htmlcomments += `
                            <div class="comment-container">
                                <div class="date-time">` + comment.created_at + `</div>

                                <div class="content">` + comment.comment + `</div>
                            </div>
                        `;
                        }

                        eventComments.html(htmlcomments);
                    } else {
                        eventComments.html(
                            `
                        <div class="comment-container">
                            <div class="no-comments">
                                No comments to show
                            </div>
                        </div>
                        `
                        );
                    }

                    tableMobile.find('.event-comments').slideUp();
                    tableMobile.find('.event-comments').removeClass('shown');
                    eventComments.slideDown();
                    eventComments.addClass('shown');
                    btnLoading.hide();
                },
                error: function (a, b, c) {
                    console.log('Error');
                }
            });
        }
    });

    $(document).on('click', '.btn-comment', function (e) {
        let tbody = $(this).closest('.tbody');
        let row = $(this).closest('.trow');
        let eventInfo = row.find('.event-info');
        let eventComments = row.find('.event-comments');
        let eventId = eventInfo.find('.tcol.event-id').text();
        let btnLoading = row.find('.btn-loading');

        if (eventComments.hasClass('shown')) {
            eventComments.slideUp();
            eventComments.removeClass('shown');
        } else {
            btnLoading.show();
            $.ajax({
                type: 'post',
                url: '/getComments',
                dataType: 'json',
                data: {
                    '_token': $('input[name=_token]').val(),
                    'eventId': eventId
                },
                success: function (response) {
                    console.log(response);

                    if (response.comments.length > 0) {

                        let htmlcomments = '';

                        for (let i = 0; i < response.comments.length; i++) {
                            let comment = response.comments[i];

                            htmlcomments += `
                            <div class="comment-container">
                                <div class="date-time">` + comment.created_at + `</div>

                                <div class="content">` + comment.comment + `</div>
                            </div>
                        `;
                        }

                        eventComments.html(htmlcomments);
                    } else {
                        eventComments.html(
                            `
                        <div class="comment-container">
                            <div class="no-comments">
                                No comments to show
                            </div>
                        </div>
                        `
                        );
                    }

                    tbody.find('.event-comments').slideUp();
                    tbody.find('.event-comments').removeClass('shown');
                    eventComments.slideDown();
                    eventComments.addClass('shown');
                    btnLoading.hide();
                },
                error: function (a, b, c) {
                    console.log('Error');
                }
            });
        }
    });

    $(document).on('click', '.action-delete-user', function (e) {
        if (confirm('Are you sure to delete this user?')){
            let tbody = $(this).closest('.tbody');
            let trow = $(this).closest('.trow');
            let wrapper = trow.find('.trow-wrapper');
            let id = trow.find('input.member-id').val();
            let counter = $(document).find('span.members-counter');

            $.ajax({
                type: 'post',
                url: '/deleteTeamMember',
                dataType: 'json',
                data: {
                    '_token': $('input[name=_token]').val(),
                    'memberId': id
                },
                success: function (response) {
                    if (response.result === 'OK' && response.data === 1){
                        wrapper.animate({
                            left: '-100%'
                        },500, function () {
                            trow.remove();

                            let count = tbody.find('.trow.member').length;
                            counter.text(10 - count);
                        });
                    }
                },
                error: function (a, b, c) {
                    console.log('Error');
                }
            });
        }
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