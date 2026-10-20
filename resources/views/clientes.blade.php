<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Clientes</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <x-navbar />

    <div class="container mt-4">
        <h1 class="text-center">Clientes</h1>

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
            Nuevo cliente
        </button>

        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Direccion</th>
                    <th>Telefono</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($clientes as $cliente)
                    <tr>
                        <td>{{ $cliente->id }}</td>
                        <td>{{ $cliente->nombre }}</td>
                        <td>{{ $cliente->email }}</td>
                        <td>{{ $cliente->direccion }}</td>
                        <td>{{ $cliente->telefono }}</td>
                        <td>
                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditar{{ $cliente->id }}">
                                Actualizar
                            </button>

                            <form action="{{ route('cliente.delete', $cliente->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este cliente?')">
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
                <form action="{{ route('cliente.create') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <h5>Nuevo cliente</h5>

                        <label>Nombre</label>
                        <input type="text" class="form-control mb-2" name="inputNombre">

                        <label>Email</label>
                        <input type="text" class="form-control mb-2" name="inputEmail">

                        <label>Direccion</label>
                        <input type="text" class="form-control mb-2" name="inputDireccion">

                        <label>Telefono</label>
                        <input type="text" class="form-control mb-2" name="inputTelefono">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @foreach ($clientes as $cliente)
        <div class="modal fade" id="modalEditar{{ $cliente->id }}">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('cliente.update', $cliente->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <h5>Actualizar cliente</h5>

                            <label>Nombre</label>
                            <input type="text" class="form-control mb-2" name="inputNombre" value="{{ $cliente->nombre }}">

                            <label>Email</label>
                            <input type="text" class="form-control mb-2" name="inputEmail" value="{{ $cliente->email }}">

                            <label>Direccion</label>
                            <input type="text" class="form-control mb-2" name="inputDireccion" value="{{ $cliente->direccion }}">

                            <label>Telefono</label>
                            <input type="text" class="form-control mb-2" name="inputTelefono" value="{{ $cliente->telefono }}">
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