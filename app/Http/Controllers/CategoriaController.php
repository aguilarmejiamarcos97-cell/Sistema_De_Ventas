<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function getCategorias()
    {
        $categorias = Categoria::orderBy('id')->get();

        return view('categorias', compact('categorias'));
    }

    public function createCategoria(Request $request)
    {
        $request->validate([
            'inputCategoria' => 'required|max:100',
        ], [
            'inputCategoria.required' => 'El nombre de la categoría es obligatorio.',
        ]);

        Categoria::create(['categoria' => $request->inputCategoria]);

        return redirect()->route('getcategorias')->with('mensaje', 'Categoría registrada correctamente.');
    }

    public function updateCategoria(Request $request, $id)
    {
        $request->validate([
            'inputCategoria' => 'required|max:100',
        ], [
            'inputCategoria.required' => 'El nombre de la categoría es obligatorio.',
        ]);

        $categoria = Categoria::findOrFail($id);
        $categoria->categoria = $request->inputCategoria;
        $categoria->save();

        return redirect()->route('getcategorias')->with('mensaje', 'Categoría actualizada correctamente.');
    }

    public function deleteCategoria($id)
    {
        try {
            Categoria::findOrFail($id)->delete();
        } catch (\Exception $e) {
            return redirect()->route('getcategorias')->with('error', 'No se puede eliminar la categoría porque tiene productos.');
        }

        return redirect()->route('getcategorias')->with('mensaje', 'Categoría eliminada correctamente.');
    }
}