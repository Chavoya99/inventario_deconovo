
<x-app-layout>

<div class="bg-neutral-primary-soft shadow-xs rounded-base border border-default">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ 'Revisar reporte '. strtoupper($proveedor->nombre)}}
        </h2>
    </x-slot>
    <div class="px-4 md:px-8 lg:px-12 py-4 md:px-8 lg:px-20">
        <x-back-button/>
        <livewire-reporte-estatus :reporte="$reporte"/>
        <form action="{{route('generar_orden_compra', ['reporte_id' => $reporte->id])}}" method="POST"
        onsubmit="return confirm('¿Generar orden de compra?')">
            @csrf
            <button
                type="submit"
                class="
                    my-5
                    block mx-auto
                    px-4 py-2 text-sm font-medium rounded
                    text-white bg-sky-600
                    hover:bg-sky-800
                    transition
                    disabled:opacity-50 hover:bg-sky-600 disabled:cursor-not-allowed
                "
                title="Generar orden de compra"
                @if($reporte->status != 'aprobado') disabled @endif
                >
                Generar orden de compra
            </button>
        
            <livewire-revisar-reporte-tabla :productos=$productos :productos_cero=$productos_cero :reporte=$reporte :reporte_id="$reporte->id" :key="$reporte->id"/>
        </form>
    </div>
</div>

</x-app-layout>

<script>

    function campos_requeridos($index){
        let input_nombre = document.getElementById("nombre_producto_" + $index);
        let input_pedir = document.getElementById("pedir_producto_" + $index);
        let input_precio_proveedor = document.getElementById("precio_proveedor_" + $index);
        let input_precio_venta = document.getElementById("precio_venta_" + $index);
        let input_utilidad = document.getElementById("producto_utilidad_" + $index);
        
        [input_nombre, input_pedir, input_precio_proveedor, input_precio_venta, input_utilidad]
        .forEach(input => input.toggleAttribute('required'));    
    }


    document.addEventListener('input', function(e)
    {
        if (
            e.target.classList.contains('precio-proveedor-input') ||
            e.target.classList.contains('utilidad-input')
        ) {
            calcularFila(e.target);
        }
    });
    

    function calcularFila(elemento)
    {
        let fila = elemento.closest('tr');

        let precioProveedor = fila.querySelector('.precio-proveedor-input');
        let utilidad = fila.querySelector('.utilidad-input');
        let precioVenta = fila.querySelector('.precio-venta-input');
        let contenido = fila.querySelector('.contenido-input');

        let costo = parseFloat(precioProveedor.value);
        let porcentaje = parseFloat(utilidad.value);
        let contenido_valor = parseFloat(contenido.value);

        if (!isNaN(costo) && !isNaN(porcentaje) && porcentaje < 100)
        {
            let resultado = (costo  * contenido_valor)/ (1 - (porcentaje / 100));
            precioVenta.value = resultado.toFixed(2);

            // actualizar Livewire
            precioVenta.dispatchEvent(new Event('input', { bubbles: true }));
        }
        else
        {
            precioVenta.value = '';
        }
    }

</script>