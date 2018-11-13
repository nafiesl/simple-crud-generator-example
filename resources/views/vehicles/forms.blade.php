@if (Request::get('action') == 'create')
@can('create', new App\Vehicle)
    <form method="POST" action="{{ route('vehicles.store') }}" accept-charset="UTF-8">
        {{ csrf_field() }}
        <div class="form-group">
            <label for="name" class="control-label">{{ __('vehicle.name') }}</label>
            <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required>
            {!! $errors->first('name', '<span class="invalid-feedback" role="alert">:message</span>') !!}
        </div>
        <div class="form-group">
            <label for="description" class="control-label">{{ __('vehicle.description') }}</label>
            <textarea id="description" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" name="description" rows="4">{{ old('description') }}</textarea>
            {!! $errors->first('description', '<span class="invalid-feedback" role="alert">:message</span>') !!}
        </div>
        <input type="submit" value="{{ __('vehicle.create') }}" class="btn btn-success">
        <a href="{{ route('vehicles.index') }}" class="btn btn-link">{{ __('app.cancel') }}</a>
    </form>
@endcan
@endif
@if (Request::get('action') == 'edit' && $editableVehicle)
@can('update', $editableVehicle)
    <form method="POST" action="{{ route('vehicles.update', $editableVehicle) }}" accept-charset="UTF-8">
        {{ csrf_field() }} {{ method_field('patch') }}
        <div class="form-group">
            <label for="name" class="control-label">{{ __('vehicle.name') }}</label>
            <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name', $editableVehicle->name) }}" required>
            {!! $errors->first('name', '<span class="invalid-feedback" role="alert">:message</span>') !!}
        </div>
        <div class="form-group">
            <label for="description" class="control-label">{{ __('vehicle.description') }}</label>
            <textarea id="description" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" name="description" rows="4">{{ old('description', $editableVehicle->description) }}</textarea>
            {!! $errors->first('description', '<span class="invalid-feedback" role="alert">:message</span>') !!}
        </div>
        <input name="page" value="{{ request('page') }}" type="hidden">
        <input name="q" value="{{ request('q') }}" type="hidden">
        <input type="submit" value="{{ __('vehicle.update') }}" class="btn btn-success">
        <a href="{{ route('vehicles.index', Request::only('q', 'page')) }}" class="btn btn-link">{{ __('app.cancel') }}</a>
        @can('delete', $editableVehicle)
            <a href="{{ route('vehicles.index', ['action' => 'delete', 'id' => $editableVehicle->id] + Request::only('page', 'q')) }}" id="del-vehicle-{{ $editableVehicle->id }}" class="btn btn-danger float-right">{{ __('app.delete') }}</a>
        @endcan
    </form>
@endcan
@endif
@if (Request::get('action') == 'delete' && $editableVehicle)
@can('delete', $editableVehicle)
    <div class="card">
        <div class="card-header">{{ __('vehicle.delete') }}</div>
        <div class="card-body">
            <label class="control-label text-primary">{{ __('vehicle.name') }}</label>
            <p>{{ $editableVehicle->name }}</p>
            <label class="control-label text-primary">{{ __('vehicle.description') }}</label>
            <p>{{ $editableVehicle->description }}</p>
            {!! $errors->first('vehicle_id', '<span class="invalid-feedback" role="alert">:message</span>') !!}
        </div>
        <hr style="margin:0">
        <div class="card-body text-danger">{{ __('vehicle.delete_confirm') }}</div>
        <div class="card-footer">
            <form method="POST" action="{{ route('vehicles.destroy', $editableVehicle) }}" accept-charset="UTF-8" onsubmit="return confirm(&quot;Are you sure to delete this?&quot;)" class="del-form float-right" style="display: inline;">
                {{ csrf_field() }} {{ method_field('delete') }}
                <input name="vehicle_id" type="hidden" value="{{ $editableVehicle->id }}">
                <input name="page" value="{{ request('page') }}" type="hidden">
                <input name="q" value="{{ request('q') }}" type="hidden">
                <button title="Delete this item" type="submit" class="btn btn-danger">{{ __('app.delete_confirm_button') }}</button>
            </form>
            <a href="{{ route('vehicles.index', Request::only('q', 'page')) }}" class="btn btn-link">{{ __('app.cancel') }}</a>
        </div>
    </div>
@endcan
@endif
