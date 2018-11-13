@extends('layouts.app')

@section('title', __('vehicle.list'))

@section('content')
<div class="mb-3">
    <div class="float-right">
        @can('create', new App\Vehicle)
            <a href="{{ route('vehicles.index', ['action' => 'create']) }}" class="btn btn-success">{{ __('vehicle.create') }}</a>
        @endcan
    </div>
    <h1 class="page-title">{{ __('vehicle.list') }} <small>{{ __('app.total') }} : {{ $vehicles->total() }} {{ __('vehicle.vehicle') }}</small></h1>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <form method="GET" action="" accept-charset="UTF-8" class="form-inline">
                    <div class="form-group">
                        <label for="q" class="control-label">{{ __('vehicle.search') }}</label>
                        <input placeholder="{{ __('vehicle.search_text') }}" name="q" type="text" id="q" class="form-control mx-sm-2" value="{{ request('q') }}">
                    </div>
                    <input type="submit" value="{{ __('vehicle.search') }}" class="btn btn-secondary">
                    <a href="{{ route('vehicles.index') }}" class="btn btn-link">{{ __('app.reset') }}</a>
                </form>
            </div>
            <table class="table table-sm table-responsive-sm">
                <thead>
                    <tr>
                        <th class="text-center">{{ __('app.table_no') }}</th>
                        <th>{{ __('vehicle.name') }}</th>
                        <th>{{ __('vehicle.description') }}</th>
                        <th class="text-center">{{ __('app.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($vehicles as $key => $vehicle)
                    <tr>
                        <td class="text-center">{{ $vehicles->firstItem() + $key }}</td>
                        <td>{{ $vehicle->name }}</td>
                        <td>{{ $vehicle->description }}</td>
                        <td class="text-center">
                            @can('update', $vehicle)
                                <a href="{{ route('vehicles.index', ['action' => 'edit', 'id' => $vehicle->id] + Request::only('page', 'q')) }}" id="edit-vehicle-{{ $vehicle->id }}">{{ __('app.edit') }}</a>
                            @endcan
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="card-body">{{ $vehicles->appends(Request::except('page'))->render() }}</div>
        </div>
    </div>
    <div class="col-md-4">
        @if(Request::has('action'))
        @include('vehicles.forms')
        @endif
    </div>
</div>
@endsection
