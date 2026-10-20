<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\Producto;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{
    public function getVentas()
    {
        $ventas = Venta::join('clientes', 'ventas.cliente_id', '=', 'clientes.id')
            ->select('ventas.id', 'ventas.cliente_id', 'ventas.fecha_venta', 'ventas.estado', 'ventas.total', 'clientes.nombre')
            ->orderBy('ventas.id')
            ->get();

        $detalles = DetalleVenta::join('productos', 'detalle_ventas.producto_id', '=', 'productos.id')
            ->select('detalle_ventas.venta_id', 'detalle_ventas.cantidad', 'detalle_ventas.precio_unitario', 'productos.producto')
            ->get();

        $clientes = Cliente::all();
        $productos = Producto::all();

        return view('ventas', compact('ventas', 'detalles', 'clientes', 'productos'));
    }

    public function createVenta(Request $request)
    {
        $request->validate([
            'inputCliente_id' => 'required|exists:clientes,id',
            'inputFecha_venta' => 'required|date',
        ], [
            'inputCliente_id.required' => 'Debes elegir un cliente.',
            'inputCliente_id.exists' => 'El cliente elegido no existe.',
            'inputFecha_venta.required' => 'La fecha es obligatoria.',
            'inputFecha_venta.date' => 'La fecha no es válida.',
        ]);

        $productos_ids = $request->input('inputProducto_id', []);
        $cantidades = $request->input('inputCantidad', []);

        try {
            DB::transaction(function () use ($request, $productos_ids, $cantidades) {
                $venta = new Venta();
                $venta->cliente_id = $request->inputCliente_id;
                $venta->fecha_venta = $request->inputFecha_venta;
                $venta->estado = 'pendiente';
                $venta->total = 0;
                $venta->save();

                $totalVenta = 0;
                $lineas = 0;

                foreach ($productos_ids as $i => $producto_id) {
                    if ($producto_id == '') {
                        continue;
                    }

                    $cantidad = (int) $cantidades[$i];

                    if ($cantidad < 1) {
                        throw new \Exception('La cantidad de cada producto debe ser mayor a 0.');
                    }

                    $producto = Producto::find($producto_id);

                    if ($cantidad > $producto->stock) {
                        throw new \Exception('No hay stock suficiente de ' . $producto->producto . '. Stock disponible: ' . $producto->stock);
                    }

                    $totalDetalle = $cantidad * $producto->precio;

                    $detalle = new DetalleVenta();
                    $detalle->venta_id = $venta->id;
                    $detalle->producto_id = $producto->id;
                    $detalle->cantidad = $cantidad;
                    $detalle->precio_unitario = $producto->precio;
                    $detalle->total = $totalDetalle;
                    $detalle->save();

                    $producto->stock = $producto->stock - $cantidad;
                    $producto->save();

                    $totalVenta = $totalVenta + $totalDetalle;
                    $lineas = $lineas + 1;
                }

                if ($lineas == 0) {
                    throw new \Exception('Debes elegir al menos un producto.');
                }

                $venta->total = $totalVenta;
                $venta->save();
            });
        } catch (\Exception $e) {
            return redirect()->route('getventas')->with('error', $e->getMessage());
        }

        return redirect()->route('getventas')->with('mensaje', 'Venta registrada correctamente.');
    }

    public function updateVenta(Request $request, $id)
    {
        $request->validate([
            'inputCliente_id' => 'required|exists:clientes,id',
            'inputFecha_venta' => 'required|date',
            'inputEstado' => 'required|in:pendiente,confirmada,cancelada',
        ], [
            'inputCliente_id.required' => 'Debes elegir un cliente.',
            'inputCliente_id.exists' => 'El cliente elegido no existe.',
            'inputFecha_venta.required' => 'La fecha es obligatoria.',
            'inputFecha_venta.date' => 'La fecha no es válida.',
            'inputEstado.required' => 'Debes elegir un estado.',
            'inputEstado.in' => 'El estado elegido no es válido.',
        ]);

        $venta = Venta::find($id);

        if ($venta->estado == 'cancelada') {
            return redirect()->route('getventas')->with('error', 'Una venta cancelada ya no se puede modificar.');
        }

        DB::transaction(function () use ($request, $venta) {
            if ($request->inputEstado == 'cancelada') {
                $this->devolverStock($venta->id);
            }

            $venta->cliente_id = $request->inputCliente_id;
            $venta->fecha_venta = $request->inputFecha_venta;
            $venta->estado = $request->inputEstado;
            $venta->save();
        });

        return redirect()->route('getventas')->with('mensaje', 'Venta actualizada correctamente.');
    }

    public function deleteVenta($id)
    {
        $venta = Venta::find($id);

        if ($venta->estado == 'confirmada') {
            return redirect()->route('getventas')->with('error', 'No se puede eliminar una venta confirmada. Primero cancélala.');
        }

        DB::transaction(function () use ($venta) {
            if ($venta->estado == 'pendiente') {
                $this->devolverStock($venta->id);
            }

            DetalleVenta::where('venta_id', $venta->id)->delete();
            $venta->delete();
        });

        return redirect()->route('getventas')->with('mensaje', 'Venta eliminada correctamente.');
    }

    private function devolverStock($venta_id)
    {
        $detalles = DetalleVenta::where('venta_id', $venta_id)->get();

        foreach ($detalles as $detalle) {
            $producto = Producto::find($detalle->producto_id);
            $producto->stock = $producto->stock + $detalle->cantidad;
            $producto->save();
        }
    }
}