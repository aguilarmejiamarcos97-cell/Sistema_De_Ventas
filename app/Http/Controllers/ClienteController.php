<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function getClientes()
    {
        $clientes = Cliente::orderBy('id')->get();

        return view('clientes', compact('clientes'));
    }

    public function createCliente(Request $request)
    {
        $request->validate([
            'inputNombre' => 'required|max:100',
            'inputEmail' => 'required|email|unique:clientes,email',
            'inputDireccion' => 'required',
            'inputTelefono' => 'required|digits:10',
        ], [
            'inputNombre.required' => 'El nombre es obligatorio.',
            'inputNombre.max' => 'El nombre no puede tener más de 100 caracteres.',
            'inputEmail.required' => 'El email es obligatorio.',
            'inputEmail.email' => 'El email no es válido.',
            'inputEmail.unique' => 'Ese email ya está registrado.',
            'inputDireccion.required' => 'La dirección es obligatoria.',
            'inputTelefono.required' => 'El teléfono es obligatorio.',
            'inputTelefono.digits' => 'El teléfono debe tener exactamente 10 dígitos.',
        ]);

        $cliente = new Cliente();
        $cliente->nombre = $request->inputNombre;
        $cliente->email = $request->inputEmail;
        $cliente->direccion = $request->inputDireccion;
        $cliente->telefono = $request->inputTelefono;
        $cliente->save();

        return redirect()->route('getclientes')->with('mensaje', 'Cliente registrado correctamente.');
    }

    public function updateCliente(Request $request, $id)
    {
        $request->validate([
            'inputNombre' => 'required|max:100',
            'inputEmail' => 'required|email|unique:clientes,email,' . $id,
            'inputDireccion' => 'required',
            'inputTelefono' => 'required|digits:10',
        ], [
            'inputNombre.required' => 'El nombre es obligatorio.',
            'inputNombre.max' => 'El nombre no puede tener más de 100 caracteres.',
            'inputEmail.required' => 'El email es obligatorio.',
            'inputEmail.email' => 'El email no es válido.',
            'inputEmail.unique' => 'Ese email ya está registrado.',
            'inputDireccion.required' => 'La dirección es obligatoria.',
            'inputTelefono.required' => 'El teléfono es obligatorio.',
            'inputTelefono.digits' => 'El teléfono debe tener exactamente 10 dígitos.',
        ]);

        $cliente = Cliente::find($id);
        $cliente->nombre = $request->inputNombre;
        $cliente->email = $request->inputEmail;
        $cliente->direccion = $request->inputDireccion;
        $cliente->telefono = $request->inputTelefono;
        $cliente->save();

        return redirect()->route('getclientes')->with('mensaje', 'Cliente actualizado correctamente.');
    }

    public function deleteCliente($id)
    {
        $cliente = Cliente::find($id);

        try {
            $cliente->delete();
        } catch (\Exception $e) {
            return redirect()->route('getclientes')->with('error', 'No se puede eliminar el cliente porque tiene ventas.');
        }

        return redirect()->route('getclientes')->with('mensaje', 'Cliente eliminado correctamente.');
    }
}