
<x-app-layout>

<div class="bg-neutral-primary-soft shadow-xs rounded-base border border-default">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ 'Revisar reporte '. strtoupper($proveedor->nombre)}}
        </h2>
    </x-slot>
    <div class="px-4 md:px-8 lg:px-12 py-4 md:px-8 lg:px-10">
        <x-back-button/>
        <livewire:reporte-estatus :reporte="$reporte"/>
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
        
            <livewire:revisar-reporte-tabla :productos=$productos :productos_cero=$productos_cero :reporte=$reporte :reporte_id="$reporte->id" :key="$reporte->id"/>
        </form>
    </div>
</div>

</x-app-layout>

<script>
    document.querySelectorAll('.drag-scroll').forEach(el => {
    let down=false,startX,scrollLeft;
    el.addEventListener('mousedown',e=>{down=true;startX=e.pageX-el.offsetLeft;scrollLeft=el.scrollLeft});
    el.addEventListener('mouseleave',()=>down=false);
    el.addEventListener('mouseup',()=>down=false);
    el.addEventListener('mousemove',e=>{
    if(!down) return;
    e.preventDefault();
    el.scrollLeft=scrollLeft-((e.pageX-el.offsetLeft)-startX);
    });
    });

    function campos_requeridos($index){
        let input_nombre = document.getElementById("nombre_producto_" + $index);
        let input_pedir = document.getElementById("pedir_producto_" + $index);
        let input_precio_proveedor = document.getElementById("precio_proveedor_" + $index);

        let input_precio_venta_1 = document.getElementById("precio_venta_1_" + $index);
        let input_utilidad_1 = document.getElementById("producto_utilidad_1_" + $index);

        let input_precio_venta_2 = document.getElementById("precio_venta_2_" + $index);
        let input_utilidad_2 = document.getElementById("producto_utilidad_2_" + $index);

        let input_precio_venta_3 = document.getElementById("precio_venta_3_" + $index);
        let input_utilidad_3 = document.getElementById("producto_utilidad_3_" + $index);

        let input_precio_venta_4 = document.getElementById("precio_venta_4_" + $index);
        let input_utilidad_4 = document.getElementById("producto_utilidad_4_" + $index);
        let unidad = document.getElementById("unidad_" + $index);
        let contenido = document.getElementById("contenido_" + $index);
        
        [input_nombre, input_pedir, input_precio_proveedor,contenido,
        unidad,  input_precio_venta_1, input_utilidad_1,
        input_precio_venta_2, input_utilidad_2,
        input_precio_venta_3, input_utilidad_3,
        input_precio_venta_4, input_utilidad_4]
        .forEach(input => input.toggleAttribute('required'));    
    }

    document.addEventListener('input', function(e) {

        if (
            e.target.classList.contains('precio-proveedor-input') ||
            e.target.classList.contains('utilidad-input') ||
            e.target.classList.contains('contenido-input')
        ) {
            calcularFila(e.target);
        }

    });

    function calcularFila(elemento)
    {
        let fila = elemento.closest('tr');

        let precioProveedor = fila.querySelector('.precio-proveedor-input');
        let contenido = fila.querySelector('.contenido-input');

        let utilidades = fila.querySelectorAll('.utilidad-input');
        let preciosVenta = fila.querySelectorAll('.precio-venta-input');

        let costo = parseFloat(precioProveedor.value);
        let contenido_valor = contenido ? parseFloat(contenido.value) : 1;

        utilidades.forEach((utilidadInput, i) => {

            let porcentaje = parseFloat(utilidadInput.value);
            let precioVentaInput = preciosVenta[i];

            if (!isNaN(costo) && !isNaN(porcentaje) && porcentaje < 100)
            {
                let resultado = Math.ceil(costo / (1 - (porcentaje / 100))) * contenido_valor;

                precioVentaInput.value = resultado.toFixed(2);

                // actualizar Livewire
                precioVentaInput.dispatchEvent(new Event('input', { bubbles: true }));
            }
            else
            {
                precioVentaInput.value = '';
            }

        });
    }

</script>