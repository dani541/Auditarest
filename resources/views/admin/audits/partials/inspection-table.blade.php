@if(isset($data))
<div class="table-responsive">
    <table class="table table-hover table-sm">
        <thead class="table-light">
            <tr>
                <th>Elemento</th>
                <th>Estado</th>
                <th>Observaciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data->toArray() as $key => $value)
                @if(!in_array($key, $exclude ?? []))
                <tr>
                    <td>{{ ucwords(str_replace('_', ' ', $key)) }}</td>
                    <td>
                        @if(is_bool($value))
                            @if($value)
                            <span class="badge bg-success">Cumple</span>
                            @else
                            <span class="badge bg-danger">No Cumple</span>
                            @endif
                        @elseif(is_string($value) && in_array(strtolower($value), ['si', 'no', 'sí']))
                            @if(strtolower($value) === 'si' || strtolower($value) === 'sí')
                            <span class="badge bg-success">Sí</span>
                            @else
                            <span class="badge bg-danger">No</span>
                            @endif
                        @else
                            {{ $value ?? 'N/A' }}
                        @endif
                    </td>
                    <td>
                        @if(isset($data->observations) && !in_array('observations', $exclude ?? []))
                            {{ $data->observations }}
                        @else
                            N/A
                        @endif
                    </td>
                </tr>
                @endif
            @endforeach
        </tbody>
    </table>
</div>
@endif