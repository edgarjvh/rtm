@extends('layouts.app')

@section('content')
    <div class="banner">
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 mt-3">
                <div class="row">
                    <div class="col">
                        <div class="col">
                            <label for="" style="font-family: Merriweather, Serif; font-size: 24px;color: #C96756; text-align: center; width: 100%">
                                Start with a team!
                            </label>
                            <br>
                            <br>
                            <br>
                            <br>
                            <img src="img/register-img.png" style="width: 250px" alt="">
                        </div>
                    </div>
                    <div class="col mt-2">

                        <a href="/find" class="nav-link btn-block">
                            JOIN AN EXISTING TEAM
                        </a>
                        <br>
                        <div class="text-center">
                            or
                        </div>
                        <br>
                        <a href="/create" class="nav-link btn-block">
                            CREATE A NEW TEAM
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>

        $(document).on('keyup', '#txt-organization', delay(function (e) {
            $('#org-msg').html(
                '<i class="fa fa-spin fa-spinner"></i>'
            );

            if (this.value !== '') {
                $.ajax({
                    type: 'post',
                    url: '/checkorg',
                    data: {
                        'orgName': this.value
                    },
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    success: function (response) {
                        $('#org-msg').html('');
                        let dd = $('.ctl-drop-down');
                        let inputText = $('#txt-organization').val();

                        dd.hide();
                        dd.html('');

                        let items = '';

                        let orgMatch = false;
                        let nameMatches = '';

                        if (response.length > 0) {
                            for (let i = 0; i < response.length; i++) {
                                items += `
                                <div class="dd-item">
                                    <label for="" class="m-0" data-org="` + response[i].name + `">` + response[i].name + `</label>
                                </div>
                            `;

                                if (!orgMatch) {
                                    if (response[i].name.toString().trim().toLowerCase() === inputText.toString().trim().toLowerCase()) {
                                        orgMatch = true;
                                        nameMatches = response[i].name;
                                    }
                                }
                            }

                            if (orgMatch) {
                                dd.html(
                                    `
                                <div class="dd-item border-bottom bg-info">
                                    <label for="" class="m-0" data-org="` + nameMatches + `">Register under <i>` + nameMatches + `</i> organization</label>
                                </div>
                                `
                                    + items
                                );

                                for (let i = 0; i < dd.find('.dd-item').length; i++) {
                                    let item = dd.find('.dd-item').eq(i);

                                    if (item.find('label').text() === nameMatches) {
                                        item.remove();
                                        break;
                                    }
                                }
                            } else {
                                dd.html(
                                    `
                                <div class="dd-item border-bottom bg-success">
                                    <label for="" class="m-0" data-org="` + inputText + `">` + inputText + ` is available</label>
                                </div>
                                `
                                    + items
                                );
                            }
                            dd.show();
                        } else {
                            dd.html(
                                `
                                <div class="dd-item bg-success">
                                    <label for="" class="m-0" data-org="` + inputText + `">` + inputText + ` is available</label>
                                </div>
                                `
                            );
                            dd.show();
                        }
                    },
                    error: function (a, b, c) {
                        console.log('this ios an ajax error');
                    }
                });
            } else {
                $('#org-msg').html('');
                $('.ctl-drop-down').hide();
            }

        }, 500));

        $(document).on('click', '.ctl-drop-down .dd-item label', function () {
            let lbl = $(this).attr('data-org');
            let formGroup = $(this).closest('.form-group');
            let dd = $(this).closest('.ctl-drop-down');

            formGroup.find('input').val(lbl);
            dd.hide();
            dd.html('');

        });

        $(document).on('click', function () {

        });

        function delay(callback, ms) {
            let timer = 0;
            return function () {
                let context = this, args = arguments;
                clearTimeout(timer);
                timer = setTimeout(function () {
                    callback.apply(context, args);
                }, ms || 0);
            };
        }
    </script>
@endsection
