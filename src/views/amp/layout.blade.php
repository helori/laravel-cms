@extends($layout_view)
@section('content')   

<div id="crud-form">
    <div class="container">

        <h1>{{ $edit_title }}</h1>

        @if($edit_help)
            <div class="alert alert-success">{!! $edit_help !!}</div>
        @endif

        <form method="post" action="{{ $route_url }}/{{ isset($item) ? 'update-item/'.$item->id : 'store-item' }}" class="form-horizontal" enctype="multipart/form-data">
            
            {!! csrf_field() !!}

            @foreach($edit_fields as $i => $field)
                @if($field['type'] == 'separator')
                    <hr>
                @elseif(view()->exists('laravel-crudui::field-'.$field["type"]))
                    <div class="form-group">
                        <label for="{{ $field['name'] }}" class="control-label col-sm-4">{{ $field['title'] }} :</label>
                        <div class="col-sm-8">
                            @include('laravel-crudui::field-'.$field["type"], ['fieldData' => $item])
                            @if(isset($field['help']) && $field['help'])
                                <p class="help-block">{{ $field['help'] }}</p>
                            @endif
                        </div>
                    </div>
                @endif
            @endforeach

            <div class="form-group">
                <div class="col-sm-8 col-sm-offset-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save"></i> Enregistrer
                    </button>
                    <a type="button" class="btn btn-default" href="{{ $route_url }}/items">
                        <i class="fa fa-close"></i> Fermer
                    </a>
                </div>
            </div>

        </form>

    </div>
</div>
@endsection