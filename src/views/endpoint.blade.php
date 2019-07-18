## {{$endpoint->getName()}}

`{{$endpoint->getMethod()}} {{$endpoint->getUri()}}`

{!! $endpoint->getDescription() !!}

@if ($endpoint->isDeprecated())
@if ($message = $endpoint->getDeprecationMessage())
<aside class="warning"><strong>Deprecated:</strong> {{$message}}</aside>
@else
<aside class="warning"><strong>Deprecated</strong></aside>
@endif
@endif

@if (count($endpoint->getParams()) > 0)
### Parameters

| Name | Located in | Description | Type |
| ---- | ---------- | ----------- | ---- |
@foreach ($endpoint->getParams() as $param)
|{{$param->name}}|{{$param->in}}|{{$param->description}}|{{$param->type}}|
@endforeach
@endif

@if ($endpoint->getReturn()->getType() == CodeOrange\Jot\Docs\ReturnValue::$JSON)
> Response

```json
{!! $endpoint->getReturn()->getValue() !!}
```

@else()
### Response

{{$endpoint->getReturn()->getValue()}}
@endif
