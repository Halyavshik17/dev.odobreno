<form
    action="{{ isset($item) ? route(config('theme.rprefix') . '.update', $item->id) : route(config('theme.rprefix') . '.store') }}"
    method="POST" class="needs-validation modal-content" novalidate="novalidate" enctype="multipart/form-data"
    onsubmit="submitFormAxios(event)">
    @csrf
    @if (isset($item))
        @method('PUT')
    @endif
    <div class="card-header my-3 p-2 border-bottom">
        <h4>{{ config('theme.title') }}</h4>
    </div>
    <div class="modal-body">
        <table class="table table-hover table-striped">
            <tr>
                <th>
                    <label for="name" class="">
                        @localize('Name')
                        <span class="text-danger">*</span>
                    </label>
                </th>
                <td>
                    <input type="text" class="form-control" name="name" id="name"
                        value="{{ isset($item) ? $item->name : old('name') }}" placeholder="@localize('Name')">
                </td>
            </tr>
            <tr>
                <th>
                    <label for="description" class="">
                        @localize('Description')
                    </label>
                </th>
                <td>
                    <textarea class="form-control" name="description" id="description" placeholder="@localize('Description')">{{ isset($item) ? $item->description : old('description') }}</textarea>
                </td>
            </tr>

            @if($is_creatable)
                @if(can('setting_management'))
                    <div class="form-group row my-2">
                        <label for="license_type_ids" class="col-sm-5 col-form-label">@localize('Company') <i class="text-danger">*</i></label>
                        <div class="col-sm-7">
                            <select class="form-control basic-single" required name="company_id" id="company_id" data-select2-id="company" tabindex="-1" aria-hidden="true">
                                <option value="" selected="selected">@localize('Please Select')</option>
                                @foreach ($companies as $company)
                                    <option value="{{ $company->id }}"
                                        {{ isset($item) ? ($item->company_id == $company->id ? 'selected' : '') : '' }}>
                                        {{ $company->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @else
                    @if(isset($company->id))
                        <div class="form-group row my-2">
                            <label for="company_id" class="col-sm-5 col-form-label">@localize('Company')</label>
                            <div class="col-sm-7">
                                <input name="company_id" id="company_id" class="form-control" type="text" value="{{ $company->name }}" readonly>
                                {{-- <input type="hidden" name="company_id" value="{{ $company->id }}"> --}}
                            </div>
                        </div>
                    @endif
                @endif
            @else
                @if(isset($company->id))
                    <div class="form-group row my-2">
                        <label for="company_id" class="col-sm-5 col-form-label">@localize('Company')</label>
                        <div class="col-sm-7">
                            <input name="company_id" id="company_id" class="form-control" type="text" value="{{ $company->name }}" readonly>
                            {{-- <input type="hidden" name="company_id" value="{{ $company->id }}"> --}}
                        </div>
                    </div>
                @endif
            @endif
        </table>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">@localize('Close')</button>
        <button class="btn btn-success" type="submit">@localize('Save')</button>
    </div>
</form>
