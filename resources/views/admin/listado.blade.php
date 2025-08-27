@extends('layouts.admin')

@section('content')
    <h1>Listado de Rifas</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Precio boleto</th>
                <th>Total boletos</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rifas as $rifa)
                <tr>
                    <td>{{ $rifa->id }}</td>
                    <td>{{ $rifa->nombre }}</td>
                    <td>${{ $rifa->precio_boleto }}</td>
                    <td>{{ $rifa->total_boletos }}</td>
                    <td>{{ $rifa->estado }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
