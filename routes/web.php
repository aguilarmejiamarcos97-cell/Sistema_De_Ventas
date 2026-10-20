<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\VentaController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('categorias', [CategoriaController::class, 'getCategorias'])->name('getcategorias');
Route::post('create/categoria', [CategoriaController::class, 'createCategoria'])->name('categoria.create');
Route::put('update/categoria/{id}', [CategoriaController::class, 'updateCategoria'])->name('categoria.update');
Route::delete('delete/categoria/{id}', [CategoriaController::class, 'deleteCategoria'])->name('categoria.delete');

Route::get('productos', [ProductoController::class, 'getProductos'])->name('getproductos');
Route::post('create/producto', [ProductoController::class, 'createProducto'])->name('producto.create');
Route::put('update/producto/{id}', [ProductoController::class, 'updateProducto'])->name('producto.update');
Route::delete('delete/producto/{id}', [ProductoController::class, 'deleteProducto'])->name('producto.delete');

Route::get('clientes', [ClienteController::class, 'getClientes'])->name('getclientes');
Route::post('create/cliente', [ClienteController::class, 'createCliente'])->name('cliente.create');
Route::put('update/cliente/{id}', [ClienteController::class, 'updateCliente'])->name('cliente.update');
Route::delete('delete/cliente/{id}', [ClienteController::class, 'deleteCliente'])->name('cliente.delete');

Route::get('ventas', [VentaController::class, 'getVentas'])->name('getventas');
Route::post('create/venta', [VentaController::class, 'createVenta'])->name('venta.create');
Route::put('update/venta/{id}', [VentaController::class, 'updateVenta'])->name('venta.update');
Route::delete('delete/venta/{id}', [VentaController::class, 'deleteVenta'])->name('venta.delete');