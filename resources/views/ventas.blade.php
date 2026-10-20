<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ventas</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <x-navbar />

    <div class="container mt-4">
        <h1 class="text-center">Ventas</h1>

        @if (session('mensaje'))
            <div class="alert alert-success">{{ session('mensaje') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    {{ $error }} <br>
                @endforeach
            </div>
        @endif

        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalNuevo">Nueva venta</button>

        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Fecha</th>
                    <th>Productos</th>
                    <th>Estado</th>
                    <th>Total</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($ventas as $venta)
                    <tr>
                        <td>{{ $venta->id }}</td>
                        <td>{{ $venta->nombre }}</td>
                        <td>{{ $venta->fecha_venta }}</td>
                        <td>
                            @foreach ($detalles as $detalle)
                                @if ($detalle->venta_id == $venta->id)
                                    {{ $detalle->cantidad }} x {{ $detalle->producto }} (${{ $detalle->precio_unitario }}) <br>
                                @endif
                            @endforeach
                        </td>
                        <td>{{ $venta->estado }}</td>
                        <td>${{ $venta->total }}</td>
                        <td>
                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditar{{ $venta->id }}">Actualizar</button>

                            <form action="{{ route('venta.delete', $venta->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar esta venta?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="modalNuevo">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('venta.create') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <h5>Nueva venta</h5>

                        <label>Cliente</label>
                        <select class="form-select mb-2" name="inputCliente_id">
                            <option value="">Elige un cliente</option>
                            @foreach ($clientes as $cliente)
                                <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                            @endforeach
                        </select>

                        <label>Fecha</label>
                        <input type="date" class="form-control mb-3" name="inputFecha_venta" value="{{ date('Y-m-d') }}">

                        <label>Productos (deja vacios los renglones que no uses)</label>
                        @for ($i = 1; $i <= 5; $i++)
                            <div class="row mb-2">
                                <div class="col-8">
                                    <select class="form-select" name="inputProducto_id[]">
                                        <option value="">Producto {{ $i }}</option>
                                        @foreach ($productos as $producto)
                                            <option value="{{ $producto->id }}">{{ $producto->producto }} (stock: {{ $producto->stock }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-4">
                                    <input type="number" class="form-control" name="inputCantidad[]" placeholder="Cantidad">
                                </div>
                            </div>
                        @endfor
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @foreach ($ventas as $venta)
        <div class="modal fade" id="modalEditar{{ $venta->id }}">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('venta.update', $venta->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <h5>Actualizar venta</h5>

                            <label>Cliente</label>
                            <select class="form-select mb-2" name="inputCliente_id">
                                @foreach ($clientes as $cliente)
                                    @if ($cliente->id == $venta->cliente_id)
                                        <option value="{{ $cliente->id }}" selected>{{ $cliente->nombre }}</option>
                                    @else
                                        <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                                    @endif
                                @endforeach
                            </select>

                            <label>Fecha</label>
                            <input type="date" class="form-control mb-2" name="inputFecha_venta" value="{{ $venta->fecha_venta }}">

                            <label>Estado</label>
                            <select class="form-select" name="inputEstado">
                                @if ($venta->estado == 'pendiente')
                                    <option value="pendiente" selected>Pendiente</option>
                                @else
                                    <option value="pendiente">Pendiente</option>
                                @endif

                                @if ($venta->estado == 'confirmada')
                                    <option value="confirmada" selected>Confirmada</option>
                                @else
                                    <option value="confirmada">Confirmada</option>
                                @endif

                                @if ($venta->estado == 'cancelada')
                                    <option value="cancelada" selected>Cancelada</option>
                                @else
                                    <option value="cancelada">Cancelada</option>
                                @endif
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-warning">Actualizar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
</body>
</html>