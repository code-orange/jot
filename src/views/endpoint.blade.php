## {{$endpoint->getName()}}

{{$endpoint->getDescription()}}

`{{$endpoint->getMethod()}} {{$endpoint->getUri()}}`

@if (count($endpoint->getParams()) > 0)
### Parameters

| Name | Located in | Description | Type |
| ---- | ---------- | ----------- | ---- |
@foreach ($endpoint->getParams() as $param)
|{{$param->name}}|{{$param->in}}|{{$param->description}}|{{$param->type}}|
@endforeach
@endif

@if ($endpoint->getReturn()->getType() == CodeOrange\Jot\Docs\ReturnValue::$JSON)
```json
{!! $endpoint->getReturn()->getValue() !!}
```

@else()
### Response

{{$endpoint->getReturn()->getValue()}}
@endif
