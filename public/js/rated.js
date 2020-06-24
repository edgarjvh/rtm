$(document).ready(function () {
    $(document).on('click', '.submit-btn', function (e) {
       let comments = $(document).find('#rating-comment');
       let ratingkey = $(document).find('#rating_key');
       let eventid = $(document).find('#event_id');

       if (comments.val().trim() === ''){
           alert('Please type some words');
       }else{


           $.ajax({
               type: 'post',
               url: '/saveComments',
               dataType: 'json',
               data: {
                   '_token': $('input[name=_token]').val(),
                   'comments': comments.val().trim(),
                   'ratingkey': ratingkey.val().trim(),
                   'eventid': eventid.val().trim()
               },
               success: function (response) {
                   console.log(response);

                   alert('Your comments have been submitted!');

                   window.location.href = '/login';
               },
               error: function (a, b, c) {
                   console.log('Error');
               }
           });
       }
    });
});