@extends("layouts.app")

@inject("Type", "App\Models\Type")

@section("content")
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">@if(isset($title)) {{ $title }} @endif</div>

                <div class="panel-body">
                    @include("layouts.messages")

                    @if(!is_null($dataset))
                        @if(isset($title))
                            <h2>{{ $title }}</h2>
                        @endif

                        @if($dataset->total() > 0)
                            <table class="table table-striped table-hover table-responsive">
                                <thead>
                                    <tr class="active">
                                        @foreach($columns as $column)
                                            <th>{{ $column["title"] }}</th>
                                        @endforeach

                                        <th style="width:130px;">@lang("general.actions")</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($dataset as $data)
                                        @php
                                            if(isset($data->type_id)) {
                                                if((int)$data->type_id === $Type::INCOME) {
                                                    $color_class = "success";
                                                    $type_icon = "<i class='fa fa-arrow-up' aria-hidden='true'></i>";
                                                } else {
                                                    $color_class = NULL;
                                                }

                                                if((int)$data->type_id === $Type::EXPENDITURE) {
                                                    $color_class = "danger";
                                                    $type_icon = "<i class='fa fa-arrow-down' aria-hidden='true'></i>";
                                                }
                                            } else {
                                                $color_class = NULL;
                                                $type_icon = NULL;
                                            }
                                        @endphp

                                        <tr class="{{ $color_class or NULL }}">
                                            @foreach($columns as $column)
                                                <td>@if(isset($column["id"]) && $column["id"] === "type"){!! $type_icon or NULL !!} @endif{!! $column["value"]($data) !!}</td>
                                            @endforeach

                                            {{-- Akcje --}}
                                            <td>
                                                @if(($is_actions_restricted && (int)$data->user_id === Auth::User()->id) || !$is_actions_restricted)
                                                    {{ Html::link(route($route_name . ".editform", $data->id), trans("general.edit"), ["class" => "btn btn-sm btn-primary"]) }}
                                                    {{ Form::button(trans("general.delete"), ["class" => "btn btn-sm btn-danger", "data-toggle" => "modal", "data-target" => "#delete-modal", "data-id" => $data->id, "data-name" => $data->name]) }}
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif

                        <div class="text-center">
                            @if($dataset->total() > $dataset->perPage())
                                {{ $dataset->links() }}
                                <br>
                            @endif

                            <a href="{{ route($route_name . ".addform") }}" class="btn btn-success" role="button"><i class="fa fa-plus"></i> @lang("general.add")</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@if($dataset->total() > 0)
    @include("deletemodal")
@endif

@endsection
