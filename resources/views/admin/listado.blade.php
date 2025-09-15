@extends('layouts.admin')

@section('content')
    <div class="container mt-4">
        <div class="card shadow-lg rounded">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center flex-wrap">
                <h2 class="mb-0 fs-6">📋 Listado de Rifas</h2>
                <a href="{{ route('admin.crearSorteo') }}" class="btn btn-light btn-sm mt-2 mt-md-0">
                    <i class="bi bi-plus-circle"></i> Nueva Rifa
                </a>
            </div>

            <div class="card-body p-2 p-md-4">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                    </div>
                @endif

                @if($rifas->isEmpty())
                    <div class="alert alert-info text-center">No hay rifas registradas aún.</div>
                @else
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle">
                            <thead class="table-dark text-center small">
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th class="d-none d-sm-table-cell">Precio</th>
                                    <th class="d-none d-md-table-cell">Total Boletos</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="small">
                                @foreach($rifas as $rifa)
                                    <tr>
                                        <td class="text-center">{{ $rifa->id }}</td>
                                        <td>{{ $rifa->nombre }}</td>
                                        <td class="text-success fw-bold d-none d-sm-table-cell">
                                            ${{ number_format($rifa->precio_boleto, 2) }}</td>
                                        <td class="d-none d-md-table-cell">{{ $rifa->boletos_count }}</td>
                                        <td class="text-center">
                                            @if($rifa->estado == 'activa')
                                                <span class="badge bg-success">Activa</span>
                                            @else
                                                <span class="badge bg-secondary">Inactiva</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex flex-wrap justify-content-center gap-1">
                                                <a href="{{ route('admin.boletos', $rifa->id) }}" class="btn btn-info btn-sm"
                                                    title="Ver/Marcar boletos">
                                                    <i class="bi bi-ticket-fill"></i>
                                                </a>



                                                <a href="{{ route('admin.editar', $rifa->id) }}" class="btn btn-warning btn-sm">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>

                                                <form action="{{ route('admin.destroy', $rifa->id) }}" method="POST"
                                                    class="d-inline"
                                                    onsubmit="return confirm('¿Seguro que deseas eliminar esta rifa?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    <div class="d-flex justify-content-center mt-3">
                        {{ $rifas->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection