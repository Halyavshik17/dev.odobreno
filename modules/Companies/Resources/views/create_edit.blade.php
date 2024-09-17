<form action="{{ config('theme.update') ?? route(config('theme.rprefix') . '.store') }}" method="POST" class="needs-validation modal-content" novalidate="novalidate" enctype="multipart/form-data" onsubmit="submitFormAxios(event)">
    @csrf
    @if (config('theme.update'))
        @method('PUT')
    @endif
    <fieldset class="mb-5 py-3 px-4">
        <legend>{{ config('theme.title') }}:</legend>
        <div class="row">
            {{-- ИНН организации --}}
            <div class="col-md-6">
                <div class="form-group pt-1 pb-1">
                    <label for="inn" class="font-black">{{ localize('INN') }}</label>
                    <input type="number" class="form-control arrow-hidden" name="inn" id="inn" value="{{ $item->inn ?? old('inn') }}" placeholder="{{ localize('Enter INN') }}" required>
                    @error('inn')
                        <p class="text-danger pt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            {{-- Название организации --}}
            <div class="col-md-6">
                <div class="form-group pt-1 pb-1">
                    <label for="name" class="font-black">{{ localize('Company Name') }}</label>
                    <input type="text" class="form-control" name="name" id="name" value="{{ $item->name ?? old('name') }}" placeholder="{{ localize('Enter Company Name') }}" required>
                    @error('name')
                        <p class="text-danger pt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            {{-- Номер телефона организации --}}
            <div class="col-md-6">
                <div class="form-group pt-1 pb-1">
                    <label for="ogrn" class="font-black">{{ localize('OGRN') }}</label>
                    <input type="number" class="form-control arrow-hidden" name="ogrn" id="ogrn" value="{{ $item->ogrn ?? old('ogrn') }}" placeholder="{{ localize('Enter OGRN') }}" required>
                    @error('ogrn')
                        <p class="text-danger pt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            {{-- ОГРН организации --}}
            <div class="col-md-6">
                <div class="form-group pt-1 pb-1">
                    <label for="kpp" class="font-black">{{ localize('KPP') }}</label>
                    <input type="number" class="form-control arrow-hidden" name="kpp" id="kpp" value="{{ $item->kpp ?? old('kpp') }}" placeholder="{{ localize('Enter KPP') }}" required>
                    @error('kpp')
                        <p class="text-danger pt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            {{-- Юридический адрес организации --}}
            <div class="col-md-6">
                <div class="form-group pt-1 pb-1">
                    <label for="address_legal" class="font-black">{{ localize('Address Legal') }}</label>
                    <input type="text" class="form-control" name="address_legal" id="address_legal" value="{{ $item->address_legal ?? old('address_legal') }}" placeholder="{{ localize('Enter Address Legal') }}" required>
                    @error('address_legal')
                        <p class="text-danger pt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            {{-- Фактический адрес организации --}}
            <div class="col-md-6">
                <div class="form-group pt-1 pb-1">
                    <label for="address_phys" class="font-black">{{ localize('Address Physical') }}</label>
                    <input type="text" class="form-control" name="address_phys" id="address_phys" value="{{ $item->address_phys ?? old('address_phys') }}" placeholder="{{ localize('Enter Address Physical') }}" required>
                    @error('address_phys')
                        <p class="text-danger pt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            {{-- Номер телефона организации --}}
            <div class="col-md-6">
                <div class="form-group pt-1 pb-1">
                    <label for="phone_number" class="font-black">{{ localize('Phone Number') }}</label>
                    <input type="tel" class="form-control" name="phone_number" id="phone_number" value="{{ $item->phone_number ?? old('phone_number') }}" placeholder="{{ localize('Enter Phone Number') }}" required>
                    @error('phone_number')
                        <p class="text-danger pt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
    </fieldset>
    <div class="modal-footer text-capitalize">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">{{ _localize('Close') }}</button>
        <button class="btn btn-success" type="submit">{{ _localize('Save') }}</button>
    </div>
</form>
