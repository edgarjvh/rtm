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
                            <label for="" style="font-family: Merriweather, Serif; font-size: 24px;color: #C96756;">
                                Register your organization
                            </label>
                            <br>
                            <br>
                            <br>
                            <br>
                            <img src="img/register-img.png" style="width: 250px" alt="">
                        </div>
                    </div>
                    <div class="col">
                        <form method="POST" action="{{ route('register') }}" autocomplete="off">
                            @csrf

                            <div class="form-group">
                                <label for="name" class="m-0">{{ __('Name') }}</label>

                                @if (!empty($name))
                                    <input id="name" type="text"
                                           class="form-control @error('name') is-invalid @enderror" name="name"
                                           value="{{ $name }}" required autocomplete="name" autofocus>
                                @else
                                    <input id="name" type="text"
                                           class="form-control @error('name') is-invalid @enderror" name="name"
                                           value="{{ old('name') }}" required autocomplete="name" autofocus>
                                @endif

                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="email"
                                       class="m-0">{{ __('E-Mail Address') }}</label>

                                @if (!empty($email))
                                    <input id="email" type="email"
                                           class="form-control @error('email') is-invalid @enderror" name="email"
                                           value="{{ $email }}" required autocomplete="email">
                                @else
                                    <input id="email" type="email"
                                           class="form-control @error('email') is-invalid @enderror" name="email"
                                           value="{{ old('email') }}" required autocomplete="email">
                                @endif

                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group" style="position: relative;">
                                <label for="txt-organization"
                                       class="m-0">Organization</label>

                                <input id="txt-organization" type="text"
                                       class="form-control @error('organization') is-invalid @enderror"
                                       name="organization" required
                                       autocomplete="organization">

                                <div id="org-msg"></div>
                                @error('organization')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>

                                @enderror
                                <div class="ctl-drop-down">

                                </div>
                            </div>

                            <div class="form-group">
                                <label for="password"
                                       class="mb-0">{{ __('Password') }}</label>

                                <input id="password" type="password"
                                       class="form-control @error('password') is-invalid @enderror" name="password"
                                       required autocomplete="new-password">

                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="password-confirm"
                                       class="m-0">{{ __('Confirm Password') }}</label>

                                <input id="password-confirm" type="password" class="form-control"
                                       name="password_confirmation" required autocomplete="new-password">

                            </div>

                            <div class="form-group row mb-4">
                                <button type="submit" class="nav-link">
                                    REGISTER
                                </button>
                            </div>

                            <hr>

                            <div class="form-group col mt-1 text-center">
                                <label class="control-label col text-center">Or Login With <span
                                            style="color: #C96756; margin-left: 3px"> Google</span></label>

                                <a href="{{ url('login/google') }}"
                                   class="col">
                                    <img src="img/goo_singup.png" style="width: 24px" alt="">
                                </a>
                            </div>

                            <input type="hidden" name="type" value="owner">
                        </form>
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
