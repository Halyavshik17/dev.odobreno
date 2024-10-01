<x-app-layout>
    <x-card>
        <x-slot name='actions'>
            <a href="{{ route(config('theme.rprefix') . '.index') }}" class="btn btn-success btn-sm"><i
                    class="fa fa-list"></i>&nbsp;{{ localize('User List') }}</a>
        </x-slot>
        <div class="row">
            <div class="col-sm-12">
                <form enctype="multipart/form-data"
                    action="{{ isset($user) ? route(config('theme.rprefix') . '.update', $user->id) : route(config('theme.rprefix') . '.store') }}"
                    method="POST" class="needs-validation" enctype="multipart/form-data">
                    @csrf
                    @isset($user)
                        @method('PUT')
                    @endisset
                    <fieldset class="mb-5 py-3 px-4 ">
                        <legend>{{ localize('Personal Info') }}:</legend>
                        <div class=" row">
                            <div class="col-md-6">
                                <div class="form-group pt-1 pb-1">
                                    <label for="name" class="font-black">{{ localize('Name') }}</label>
                                    <input type="text" class="form-control" name="name" id="name"
                                        placeholder="{{ localize('Enter Name') }}"
                                        value="{{ isset($user) ? $user->name : old('name') }}" required>
                                    @error('name')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group pt-1 pb-1">
                                    <label for="email" class="font-black">{{ localize('Email') }}</label>
                                    <input type="email" class="form-control" name="email" id="email"
                                        placeholder="{{ localize('Enter Email') }}"
                                        value="{{ isset($user) ? $user->email : old('email') }}" required>
                                    @error('email')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group pt-1 pb-1">
                                    <label for="phone" class="font-black">{{ localize('Phone') }}</label>
                                    <input type="number" class="form-control arrow-hidden" name="phone"
                                        id="phone" placeholder="{{ localize('Enter phone') }}"
                                        value="{{ isset($user) ? $user->phone : old('phone') }}">
                                    @error('phone')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group pt-1 pb-1">
                                    <label for="gender" class="font-black">{{ localize('Gender') }}</label>
                                    <select class="form-control show-tick" name="gender" id="gender">
                                        <option selected disabled>--{{ localize('Select Gender') }}--</option>
                                        @foreach (App\Models\User::genderList() as $gender)
                                            <option {{ isset($user) ? selected($user->gender, $gender) : null }}>
                                                {{ $gender }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('gender')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group pt-1 pb-1">
                                    <label for="age" class="font-black">{{ localize('Age') }}</label>
                                    <input type="number" class="form-control arrow-hidden" name="age"
                                        id="age" placeholder="{{ localize('Enter your age') }}"
                                        value="{{ isset($user) ? $user->age : old('age') }}">
                                    @error('age')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 py-1">
                                <div class="form-group pt-1 pb-1">
                                    <label for="address" class="font-black">{{ localize('Address') }}</label>
                                    <textarea name="address" id="address" class="form-control" placeholder="{{ localize('Enter your address') }}">{{ isset($item) ? $item->address : old('address') }}</textarea>
                                    @error('address')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    
                    {{-- Информация об организации --}}
                    <fieldset class="mb-5 py-3 px-4 ">
                        <legend>{{ localize('Company Info') }}:</legend>

                        @php
                            $fields = [
                                'company_inn' => [
                                    'label' => 'INN', 'type' => 'number', 'value' => isset($company) ? $company->inn : old('company_inn'),
                                    'extra_html' => 
                                        '<div id="similar-companies" style="display: none;">
                                            <h6 class="mb-3">Похожие организации:</h4>
                                            <ul id="similar-companies-list" class="list-group">
                                                <!-- вывод организаций да-да тех самых -->
                                            </ul>
                                        </div>'
                                ],
                                'company_name' => ['label' => 'Company Name', 'type' => 'text', 'value' => isset($company) ? $company->name : old('company_name'),],
                                'company_ogrn' => ['label' => 'OGRN', 'type' => 'number', 'value' => isset($company) ? $company->ogrn : old('company_ogrn'), ],
                                'company_kpp' => ['label' => 'KPP', 'type' => 'number', 'value' => isset($company) ? $company->kpp : old('company_kpp'), ],
                                'company_address_legal' => [ 'label' => 'Address Legal', 'type' => 'text', 'value' => isset($company) ? $company->address_legal : old('company_address_legal'), ],
                                'company_address_phys' => [ 'label' => 'Address Physical', 'type' => 'text', 'value' => isset($company) ? $company->address_phys : old('company_address_phys'), ],
                                'company_phone_number' => [ 'label' => 'Phone number', 'type' => 'tel', 'value' => isset($company) ? $company->phone_number : old('company_phone_number'), ],
                            ];
                        @endphp

                        <div class=" row">
                            @foreach($fields as $field => $params)
                                <div class="col-md-6">
                                    <div class="form-group pt-1 pb-1">
                                        <label for="{{ $field }}" class="font-black">{{ localize($params['label']) }}</label>
                                        <input type="{{ $params['type'] }}" class="form-control arrow-hidden" name="{{ $field }}" id="{{ $field }}" value="{{ $params['value'] }}" placeholder="{{ localize('Enter ' . $params['label']) }}" required>
                                        @if(isset($params['extra_html']))
                                            {!! $params['extra_html'] !!}
                                        @endif
                                        @error($field)
                                            <p class="text-danger pt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </fieldset>

                    <fieldset class="mb-5 py-3 px-4 ">
                        <legend>{{ localize('Account Info') }}:</legend>
                        <div class="row">
                            <div class="col-md-6 pt-1 pb-1">
                                <div class="form-group">
                                    <label for="role" class="font-black">{{ localize('User Role') }}</label>
                                    <select class="form-control show-tick" name="role" id="role" required>
                                        <option selected disabled>--{{ localize('Select User Role') }}--</option>
                                        @foreach (Modules\Role\Entities\Role::all() as $role)
                                            <option
                                                @isset($item) @selected($item->roles()->pluck('id')->first() == $role->id)
                                            @endisset
                                                value="{{ $role->id }}">
                                                {{ $role->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('role')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6 pt-1 pb-1">
                                <div class="form-group">
                                    <label for="status" class="font-black">@localize('Account Status')</label>
                                    <select class="form-control show-tick" name="status" id="status" required>
                                        <option selected disabled>--{{ localize('Select Account Status') }}--</option>
                                        @foreach (App\Models\User::statusList() as $status)
                                            <option {{ isset($item) ? selected($item->status, $status) : null }}>
                                                {{ $status }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('user_status_id')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 pt-1 pb-1">
                                <div class="form-group">
                                    <label for="password" class="font-black">{{ localize('Password') }}</label>
                                    <input type="password" class="form-control" name="password" id="password"
                                        placeholder="{{ localize('Enter Password') }}"
                                        {{ isset($item) ? '' : 'required' }} autocomplete="new-password">
                                    @error('password')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 pt-1 pb-1">
                                <div class="form-group">
                                    <label for="password_confirmation"
                                        class="font-black">{{ localize('Confirm Password') }}</label>
                                    <input type="password" class="form-control" name="password_confirmation"
                                        id="password_confirmation" placeholder="{{ localize('Retype Password') }}"
                                        {{ isset($item) ? '' : 'required' }} autocomplete="new-password">
                                    @error('password_confirmation')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 pt-1 pb-1">
                                <div class="form-group">
                                    <label for="avatar" class="font-black">{{ localize('Avatar') }}</label>
                                    <input type="file" class="form-control" name="avatar" id="avatar"
                                        onchange="get_img_url(this, '#avatar_image');"
                                        placeholder="{{ localize('Select avatar image') }}">
                                    <img id="avatar_image" src="{{ isset($item) ? $item->profile_photo_url : '' }}"
                                        width="120px" class="mt-1">
                                    @error('avatar')
                                        <p class="text-danger pt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-12 ">
                                <div class="form-group pt-1 pb-1">
                                    <button type="submit"
                                        class="btn btn-success btn-round">{{ localize('Save') }}</button>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </x-card>
    @push('css')
        <link href="{{ module_asset('User/css/user.min.css') }}" rel="stylesheet">
    @endpush
</x-app-layout>

<script>
$(document).ready(function() {
    $('#company_inn').on('input', function() {
        var inn = $(this).val();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: '{{ route("admin.user.getcompanies") }}',
            data: {company_inn: inn},
            success: function(data) {
                var companies = data;
                console.log(data);
                var html = '';

                $.each(companies, function(index, company) {
                    html += '<li class="list-group-item" data-inn="' + company.inn + '" data-name="' + company.name.replace(/"/g, '&quot;') + '" data-ogrn="' + company.ogrn + '" data-kpp="' + company.kpp + '" data-address-legal="' + company.address_legal + '" data-address-phys="' + company.address_phys + '" data-phone-number="' + company.phone_number + '">' + company.name + ' (' + company.inn + ')</li>';
                });
                
                $('#similar-companies-list').html(html);

                if (inn.length > 0) {
                    $('#similar-companies').show();
                } else {
                    $('#similar-companies').hide();
                }
            }
        });
    });
});

$('#similar-companies-list').on('click', 'li', function() {
    var inn = $(this).data('inn');
    var name = $(this).data('name');
    var ogrn = $(this).data('ogrn');
    var kpp = $(this).data('kpp');
    var addressLegal = $(this).data('address-legal');
    var addressPhys = $(this).data('address-phys');
    var phoneNumber = $(this).data('phone-number');

    $('#company_inn').val(inn);
    $('#company_name').val(name);
    $('#company_ogrn').val(ogrn);
    $('#company_kpp').val(kpp);
    $('#company_address_legal').val(addressLegal);
    $('#company_address_phys').val(addressPhys);
    $('#company_phone_number').val(phoneNumber);

    $('#company_name, #company_ogrn, #company_kpp, #company_address_legal, #company_address_phys, #company_phone_number').removeClass('error').addClass('valid');
    $('#company_name-error, #company_ogrn-error, #company_kpp-error, #company_address_legal-error, #company_address_phys-error, #company_phone_number-error').hide();
});

$('#company_inn').on('input', function() {
    var isNotEmpty = $(this).val().trim() !== '';
    if (isNotEmpty) {
        $('#company_name, #company_ogrn, #company_kpp, #company_address_legal, #company_address_phys, #company_phone_number').attr('readonly', false);
        $('#company_name, #company_ogrn, #company_kpp, #company_address_legal, #company_address_phys, #company_phone_number').val('');

        $('#company_name, #company_ogrn, #company_kpp, #company_address_legal, #company_address_phys, #company_phone_number').removeClass('valid').addClass('error');
        $('#company_name-error, #company_ogrn-error, #company_kpp-error, #company_address_legal-error, #company_address_phys-error, #company_phone_number-error').show();
    } else {
        $('#company_name, #company_ogrn, #company_kpp, #company_address_legal, #company_address_phys, #company_phone_number').attr('readonly', true);
        $('#company_name, #company_ogrn, #company_kpp, #company_address_legal, #company_address_phys, #company_phone_number').val('');
    }
});

$('#similar-companies-list').addClass('overflow-auto').css('max-height', '100px');
$('#similar-companies-list').css('overflow-y', 'auto').css('overflow-x', 'hidden');
</script>