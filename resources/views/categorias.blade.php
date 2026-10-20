<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Categorias</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <x-navbar />

    <div class="container mt-4">
        <h1 class="text-center">Categorias</h1>

        @if (session('mensaje'))
            <div class="alert alert-success">{{ session('mensaje') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    {{ $error }}<br>
                @endforeach
            </div>
        @endif

        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalNuevo">Nueva categoría</button>

        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Categoria</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categorias as $categoria)
                    <tr>
                        <td>{{ $categoria->id }}</td>
                        <td>{{ $categoria->categoria }}</td>
                        <td>
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editar{{ $categoria->id }}">Actualizar</button>

                            <form action="{{ route('categoria.delete', $categoria->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar esta categoria?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm">Eliminar</button>
                            </form>

                            <div class="modal fade" id="editar{{ $categoria->id }}">
                                <div class="modal-dialog">
                                    <form class="modal-content" action="{{ route('categoria.update', $categoria->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <h5>Actualizar categoria</h5>
                                            <input type="text" class="form-control" name="inputCategoria" value="{{ $categoria->categoria }}">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-warning">Actualizar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="modalNuevo">
        <div class="modal-dialog">
            <form class="modal-content" action="{{ route('categoria.create') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <h5>Nueva categoria</h5>
                    <input type="text" class="form-control" name="inputCategoria" placeholder="Nombre de la categoria">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>