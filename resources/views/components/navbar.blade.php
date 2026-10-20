<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">Sistema de ventas</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuPrincipal">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="menuPrincipal">
            <ul class="navbar-nav ms-auto">
               <li class="nav-item">
    <a class="nav-link" href="{{ route('getcategorias') }}">Categorias</a>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ route('getproductos') }}">Productos</a>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ route('getclientes') }}">Clientes</a>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ route('getventas') }}">Ventas</a>
</li>
            </ul>
        </div>
    </div>
</nav>