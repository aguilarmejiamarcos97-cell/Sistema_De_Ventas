<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function getProductos()
    {
        $productos = Producto::join('categorias', 'productos.categoria_id', '=', 'categorias.id')
            ->select('productos.id', 'productos.producto', 'productos.precio', 'productos.stock', 'productos.categoria_id', 'categorias.categoria')
            ->orderBy('productos.id')
            ->get();

        $categorias = Categoria::all();

        return view('productos', compact('productos', 'categorias'));
    }

    public function createProducto(Request $request)
    {
        $request->validate([
            'inputProducto' => 'required|max:100',
            'inputPrecio' => 'required|numeric|min:0',
            'inputStock' => 'required|integer|min:0',
            'inputCategoria_id' => 'required|exists:categorias,id',
        ], [
            'inputProducto.required' => 'El nombre del producto es obligatorio.',
            'inputPrecio.required' => 'El precio es obligatorio.',
            'inputPrecio.numeric' => 'El precio debe ser un número.',
            'inputStock.required' => 'El stock es obligatorio.',
            'inputStock.integer' => 'El stock debe ser un número entero.',
            'inputCategoria_id.required' => 'Debes elegir una categoría.',
            'inputCategoria_id.exists' => 'La categoría elegida no existe.',
        ]);

        $producto = new Producto();
        $producto->producto = $request->inputProducto;
        $producto->precio = $request->inputPrecio;
        $producto->stock = $request->inputStock;
        $producto->categoria_id = $request->inputCategoria_id;
        $producto->save();

        return redirect()->route('getproductos')->with('mensaje', 'Producto registrado correctamente.');
    }

    public function updateProducto(Request $request, $id)
    {
        $request->validate([
            'inputProducto' => 'required|max:100',
            'inputPrecio' => 'required|numeric|min:0',
            'inputStock' => 'required|integer|min:0',
            'inputCategoria_id' => 'required|exists:categorias,id',
        ], [
            'inputProducto.required' => 'El nombre del producto es obligatorio.',
            'inputPrecio.required' => 'El precio es obligatorio.',
            'inputPrecio.numeric' => 'El precio debe ser un número.',
            'inputStock.required' => 'El stock es obligatorio.',
            'inputStock.integer' => 'El stock debe ser un número entero.',
            'inputCategoria_id.required' => 'Debes elegir una categoría.',
            'inputCategoria_id.exists' => 'La categoría elegida no existe.',
        ]);

        $producto = Producto::find($id);
        $producto->producto = $request->inputProducto;
        $producto->precio = $request->inputPrecio;
        $producto->stock = $request->inputStock;
        $producto->categoria_id = $request->inputCategoria_id;
        $producto->save();

        return redirect()->route('getproductos')->with('mensaje', 'Producto actualizado correctamente.');
    }

    public function deleteProducto($id)
    {
        $producto = Producto::find($id);

        try {
            $producto->delete();
        } catch (\Exception $e) {
            return redirect()->route('getproductos')->with('error', 'No se puede eliminar el producto porque tiene ventas.');
        }

        return redirect()->route('getproductos')->with('mensaje', 'Producto eliminado correctamente.');
    }
}