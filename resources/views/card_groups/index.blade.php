@extends('layouts.app')

@section('navbar')
@component('components.navbar')
@endcomponent
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-header">
                    {{ __('Card groups') }}
                    <span class="float-right ml-2">
                        <a class="btn btn-success btn-sm" href="{{ route('user.cardgroups.create') }}" role="button" data-toggle="tooltip" data-placement="top" title="{{ __('Create a new') }}">
                            <i class="fas fa-plus"></i>
                        </a>
                    </span>
                    <span class="float-right">
                        <form action="{{ route('user.cardgroups.search') }}" method="post">
                            @csrf
                            <div class="input-group input-group-sm">
                                <input type="text" name="dataToSearch" class="form-control" placeholder="{{ __('Filter') }}">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="submit" data-toggle="tooltip" data-placement="top" title="{{ __('Search') }}"><i class="fas fa-search"></i></button>
                                    <a class="btn btn-outline-secondary" href="{{ route('user.cardgroups.index') }}" role="button" data-toggle="tooltip" data-placement="top" title="{{ __('Clear and return') }}"><i class="fas fa-undo-alt"></i></a>
                                </div>
                            </div>
                        </form>
                    </span>
                </div>

                <div class="card-body table-responsive">
                    <table class="table table-sm table-bordered table-hover table-borderless text-center" style="height: 100px;">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Description') }}</th>
                                <th>{{ __('Linked cards') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $index)
                            <tr>
                                <td scope="row" class="align-middle">{{ $index->id }}</td>
                                <td class="align-middle">@if($index->name != null)@if($index->owner != 1){{ Str::limit($index->name, 50) }}@else {{ Str::limit($index->name, 50) }} <i class="fas text-warning fa-star align-top" style="font-size: 0.5rem"></i> @endif @endif</td>
                                <td class="align-middle">@if($index->description != null){{ Str::limit($index->description, 50) }} @endif</td>
                                <td class="align-middle">@if($index->cards()->get()->pluck('url') != null) {{ count($index->cards()->get()->pluck('url')) }} @endif</td>
                                <td class="align-middle">
                                    @if($index->owner != 1)
                                        @if(count($index->cards()->get()->pluck('url')) !== 0)
                                            <button type="button" class="btn button-without-style mr-1 ml-1" data-toggle="tooltip" data-placement="top" title="{{ __('Go to pages') }}" onclick="loadPages({{ $index->cards()->get()->pluck('url') }})"><i class="fas fa-location-arrow text-dark"></i></button>
                                        @endif
                                        <a href="{{ route('user.cardgroups.edit', $index->id) }}" class="d-inline"><button type="button" class="button-without-style mr-1 ml-1" data-toggle="tooltip" data-placement="top" title="{{ __('Edit') }}"><i class="fas text-dark fa-edit"></i></button></a>
                                        <form action="{{ route('user.cardgroups.destroy', $index->id) }}" class="d-inline" method="POST">
                                            @csrf
                                            {{ method_field('DELETE') }}
                                            <button type="submit" class="button-without-style ml-1" data-toggle="tooltip" data-placement="top" title="{{ __('Delete') }}"><i class="fas text-dark fa-trash"></i></button>
                                        </form>
                                    @else
                                        @if(count($index->cards()->get()->pluck('url')) !== 0)
                                            <button type="button" class="btn button-without-style mr-1 ml-1" data-toggle="tooltip" data-placement="top" title="{{ __('Go to pages') }}" onclick="loadPages({{ $index->cards()->get()->pluck('url') }})"><i class="fas fa-location-arrow text-dark"></i></button>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer text-muted">
                    <div class="d-flex justify-content-center">
                        {{ $data->links() }}
                        
                    </div>
                    <span class="d-flex justify-content-center">
                        {{ __('Showing') }} {{ $data->count() }} {{ __('of') }} {{ $data->total() }} {{ __('results') }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
@component('components.modal', ['id'=>'showCopiedFeature'])

    <div class="text-center">
        <span class="font-weight-bolder" id="copiedElement"></span>
    </div>
    
    @slot('footer')
        <button type="button" class="btn btn-primary" data-dismiss="modal">Confirmar</button>
    @endslot
@endcomponent
@endsection

@section('extra-scripts')
<script type="text/javascript">
    var clipboard = new ClipboardJS('.btn');

    clipboard.on('success', function(e) {
        $("#copiedElement").html("Elemento copiado com sucesso!");
        $("#showCopiedFeature").modal("show");
        e.clearSelection();
    });

    clipboard.on('error', function(e) {
        alert("erro ao copiar elemento.");
    });

    function loadPages(urls){
        $.each(urls, function(k, v){
            console.log(k + " - " + v);
            window.open(v);
        });
    }
</script>
@endsection