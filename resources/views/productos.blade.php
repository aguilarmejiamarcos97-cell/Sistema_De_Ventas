<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Productos</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <x-navbar />

    <div class="container mt-4">
        <h1 class="text-center">Productos</h1>

        @if (session('mensaje'))
            <div class="alert alert-success">
                {{ session('mensaje') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    {{ $error }} <br>
                @endforeach
            </div>
        @endif

        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalNuevo">
            Nuevo producto
        </button>

        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Categoria</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($productos as $producto)
                    <tr>
                        <td>{{ $producto->id }}</td>
                        <td>{{ $producto->producto }}</td>
                        <td>${{ $producto->precio }}</td>
                        <td>{{ $producto->stock }}</td>
                        <td>{{ $producto->categoria }}</td>
                        <td>
                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditar{{ $producto->id }}">
                                Actualizar
                            </button>

                            <form action="{{ route('producto.delete', $producto->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este producto?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    Eliminar
                                </button>
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
                <form action="{{ route('producto.create') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <h5>Nuevo producto</h5>

                        <label>Producto</label>
                        <input type="text" class="form-control mb-2" name="inputProducto">

                        <label>Precio</label>
                        <input type="number" step="0.01" class="form-control mb-2" name="inputPrecio">

                        <label>Stock</label>
                        <input type="number" class="form-control mb-2" name="inputStock">

                        <label>Categoria</label>
                        <select class="form-select" name="inputCategoria_id">
                            <option value="">Elige una categoria</option>
                            @foreach ($categorias as $categoria)
                                <option value="{{ $categoria->id }}">{{ $categoria->categoria }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @foreach ($productos as $producto)
        <div class="modal fade" id="modalEditar{{ $producto->id }}">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('producto.update', $producto->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <h5>Actualizar producto</h5>

                            <label>Producto</label>
                            <input type="text" class="form-control mb-2" name="inputProducto" value="{{ $producto->producto }}">

                            <label>Precio</label>
                            <input type="number" step="0.01" class="form-control mb-2" name="inputPrecio" value="{{ $producto->precio }}">

                            <label>Stock</label>
                            <input type="number" class="form-control mb-2" name="inputStock" value="{{ $producto->stock }}">

                            <label>Categoria</label>
                            <select class="form-select" name="inputCategoria_id">
                                @foreach ($categorias as $categoria)
                                    @if ($categoria->id == $producto->categoria_id)
                                        <option value="{{ $categoria->id }}" selected>{{ $categoria->categoria }}</option>
                                    @else
                                        <option value="{{ $categoria->id }}">{{ $categoria->categoria }}</option>
                                    @endif
                                @endforeach
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