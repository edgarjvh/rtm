@extends('layouts.app')

@section('style-register')
    <link rel="stylesheet" href="{{asset('css/register-profile.css')}}">
@endsection

@section('content')
    <div class="profile-container">

        <div class="reg-options">
            <div class="opt col">
                <div class="title">
                    Now, your organization
                </div>
                <img src="img/org.png" alt="">
            </div>

            <div class="opt">
                <form method="POST" action="{{ route('save-organization') }}" autocomplete="off">
                    @csrf

                    <input type="hidden" id="owner" name="owner" value="{{$owner}}">

                    @if ($owner == 1)
                        <div class="form-group" style="position: relative;">
                            <label for="txt-organization"
                                   class="m-0">Organization Name</label>

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

                        <div class="form-group submit">
                            <button type="submit" class="nav-link" id="next-btn" disabled>
                                NEXT
                            </button>
                        </div>
                    @else
                        <div class="form-group" style="position: relative;">
                            <label for="cbo-organization"
                                   class="m-0">Select your organization</label>

                            <select name="organization" id="cbo-organization" class="form-control" required>
                                <option value="0">---</option>
                                @if(count($orgs) > 0)

                                    @foreach($orgs as $org)
                                        <option value="{{$org->id}}">{{$org->name}}</option>
                                    @endforeach

                                @endif

                            </select>
                        </div>

                        <div class="form-group submit">
                            <button type="submit" class="nav-link" id="next-btn" disabled>
                                NEXT
                            </button>
                        </div>


                    @endif
                </form>
            </div>
        </div>
    </div>

    <script>

        $(document).on('change', '#cbo-organization', function (e) {
            let value = $(this).val();

            $('#next-btn').prop('disabled', Number(value) <= 0);

        });

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
                                <div class="dd-item border-bottom bg-danger text-center">
                                    <label for="" class="m-0" data-org="` + nameMatches + `"><i>` + nameMatches + `</i> , is already taken</label>
                                </div>
                                `
                                );

                                for (let i = 0; i < dd.find('.dd-item').length; i++) {
                                    let item = dd.find('.dd-item').eq(i);

                                    if (item.find('label').text() === nameMatches) {
                                        item.remove();
                                        break;
                                    }
                                }

                                $('#next-btn').prop('disabled', true);
                            } else {
                                dd.html(
                                    `
                                <div class="dd-item border-bottom bg-success text-center">
                                    <label for="" class="m-0" data-org="` + inputText + `">` + inputText + ` is available!</label>
                                </div>
                                `
                                );

                                $('#next-btn').prop('disabled', false);
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

                            $('#next-btn').prop('disabled', false);
                        }
                    },
                    error: function (a, b, c) {
                        console.log('this ios an ajax error');
                    }
                });
            } else {
                $('#org-msg').html('');
                $('.ctl-drop-down').hide();

                $('#next-btn').prop('disabled', true);
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