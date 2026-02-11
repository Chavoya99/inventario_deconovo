<?php

namespace Database\Seeders;

use App\Models\Producto;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {   

        //Productos Perdura
        $productosPerdura = [
            ['producto' => "DURACRIL PERDURA 1 LT",'maximo' => 3],
            ['producto' => "DURACRIL PERDURA GALON",'maximo' => 3],
            ['producto' => "JUNTEADOR PLATINO PERDURA 10 KG",'maximo' => 15],
            ['producto' => "JUNTEADOR SIN ARENA CAFÉ PERDURA 5 KG",'maximo' => 5],
            ['producto' => "JUNTEADOR SIN ARENA CAPUCHINO PERDURA 5 KG",'maximo' => 5],
            ['producto' => "JUNTEADOR SIN ARENA CHAMPAGNE PERDURA 5 KG",'maximo' => 10],
            ['producto' => "JUNTEADOR SIN ARENA PLATA PERDURA 5 KG",'maximo' => 25],
            ['producto' => "PEGA AZULEJO BLANCO PERDURA 20 KG",'maximo' => 75],
            ['producto' => "PEGAPISO Y MARMOL GRIS PERDURA 20 KG",'maximo' => 125],
            ['producto' => "SELLACRIL 1/2 LITRO PERDURA",'maximo' => 3],
            ['producto' => "SELLACRIL GALON PERDURA",'maximo' => 2],
            ['producto' => "SELLACRIL LITRO PERDURA",'maximo' => 3],

        ];

        foreach($productosPerdura as $producto){
            Producto::create([
                'producto' => $producto['producto'],
                'proveedor_id' => 1,
                'unidad' => 'Pza',
                'existencia' => 0,
                'maximo' => $producto['maximo'],
                'pedir' => $producto['maximo'],
                'precio_venta' => 100
            ]);
        }


        //Productos Pegaduro
        $productosPegaduro = [
            ['producto' => "JUNTEADOR ARENA 10 KG", 'maximo' => 15],
            ['producto' => "JUNTEADOR BEIGE 10 KG", 'maximo' => 15],
            ['producto' => "JUNTEADOR BLANCO 10 KG", 'maximo' => 10],
            ['producto' => "JUNTEADOR CAFÉ 10 KG", 'maximo' => 15],
            ['producto' => "JUNTEADOR CAOBA 10 KG", 'maximo' => 15],
            ['producto' => "JUNTEADOR GRIS CLARO 10 KG", 'maximo' => 15],
            ['producto' => "JUNTEADOR NEGRO 10 KG", 'maximo' => 15],
            ['producto' => "JUNTEADOR SIN ARENA ARENA 5 KG", 'maximo' => 15],
            ['producto' => "JUNTEADOR SIN ARENA BLANCO 5 KG", 'maximo' => 15],
            ['producto' => "JUNTEADOR SIN ARENA CAFÉ 5 KG", 'maximo' => 10],
            ['producto' => "JUNTEADOR SIN ARENA CAOBA 5 KG", 'maximo' => 10],
            ['producto' => "JUNTEADOR SIN ARENA CREMA 5 KG", 'maximo' => 10],
            ['producto' => "JUNTEADOR SIN ARENA GRIS CLARO 5 KG", 'maximo' => 15],
            ['producto' => "JUNTEADOR SIN ARENA NEGRO 5 KG", 'maximo' => 10],
            ['producto' => "PEGA AZULEJO BLANCO ZAZ PEGADURO 20 KG", 'maximo' => 75],
            ['producto' => "VITRODURO BLANCO 20 KG", 'maximo' => 50],
            ['producto' => "PEGACERAMICA GRIS PEGADURO 20 KG", 'maximo' => 125],
            ['producto' => "PEGAPISO GRIS ZAZ PEGADURO 20 KG", 'maximo' => 150],
        ];

        foreach($productosPerdura as $producto){
            Producto::create([
                'producto' => $producto['producto'],
                'proveedor_id' => 2,
                'unidad' => 'Pza',
                'existencia' => 0,
                'maximo' => $producto['maximo'],
                'pedir' => $producto['maximo'],
                'precio_venta' => 100
            ]);
        }

        //ProductosHydroflud
        $productosHydroflud = [
            ['producto' => "CONTRA M-HI474 PUSH MONEDA CROMO P/LAV", 'maximo' => 5],
            ['producto' => "CONTRACANASTA M-AW88 P/FREG 4 1/2 ABS", 'maximo' => 3],
            ['producto' => "CONTRACANASTA M-HI430 4 1/2\" ACERO INOX", 'maximo' => 3],
            ['producto' => "CONTRA M-HI474S PUSH MONEDA SATIN P/LAV", 'maximo' => 5],
            ['producto' => "CUBIERTA M-HI131 MONOM CROMADA 8\"", 'maximo' => 5],
            ['producto' => "CUBIERTA M-HI131S MET SATIN MONOMANDO 8\"", 'maximo' => 5],
            ['producto' => "CUBIERTA M-HI132 MONOM CROMADA 4\"", 'maximo' => 5],
            ['producto' => "CUBIERTA M-HI132S MONOM SATIN 4\"", 'maximo' => 5],
            ['producto' => "CUBRE TALADRO M-HI234 CROMO", 'maximo' => 10],
            ['producto' => "MANGUERA M-HI252M P/MONOM 40 CM", 'maximo' => 6],
            ['producto' => "MANGUERA M-HIMG04 P/LAV 40 CMS", 'maximo' => 30],
            ['producto' => "MANGUERA M-HIMG05 P/FREG 55 CMS", 'maximo' => 20],
            ['producto' => "MANGUERA M-HIMG06 P/WC 35 CMS", 'maximo' => 20],
            ['producto' => "MEZCLAD M-AW110 LAV 4\" CROMO", 'maximo' => 3],
            ['producto' => "MEZCLAD M-AW112 LAV 4\" CROMO", 'maximo' => 3],
            ['producto' => "MEZCLAD M-HI560 LAV 4\" CROMO", 'maximo' => 2],
            ['producto' => "MEZCLAD M-AW113 LAV 4\" CROMO", 'maximo' => 3],
            ['producto' => "MEZCLAD M-AW118 FREG CUELLO FLEX ABS", 'maximo' => 3],
            ['producto' => "MEZCLAD M-AW68 FREG CROMO 31CM ABS", 'maximo' => 3],
            ['producto' => "MEZCLAD M-AW72 LAV CROMO", 'maximo' => 3],
            ['producto' => "MEZCLAD M-AW76 LAV CROMO 19CMS ABS", 'maximo' => 3],
            ['producto' => "MEZCLAD M-AW78 LAV CROMO", 'maximo' => 3],
            ['producto' => "MEZCLAD M-AW90 COCINA CUELLO FLEX", 'maximo' => 3],
            ['producto' => "MEZCLAD M-AW96 P/FREG CROMO CUERPO ZINC", 'maximo' => 3],
            ['producto' => "MEZCLAD M-HI481S FREG SATIN 8\"", 'maximo' => 2],
            ['producto' => "MEZCLAD M-HI85024S FREG SATIN 8\"", 'maximo' => 2],
            ['producto' => "MEZCLAD M-HI85024N FREG NEGRA 8\"", 'maximo' => 1],
            ['producto' => "MONOM M-AW21 LAV NEGRO NILO 16CM ABS", 'maximo' => 2],
            ['producto' => "MONOM M-AW27 LAV MISURI CROMO 19 CM", 'maximo' => 2],
            ['producto' => "MONOM M-AW32 FREG CROMO ARCANSAS", 'maximo' => 2],
            ['producto' => "MONOM M-HI202 CROMO LAV 19 CM", 'maximo' => 2],
            ['producto' => "MONOM M-HI410 CROMO LAV", 'maximo' => 2],
            ['producto' => "MONOM M-HI62 LAV CROMO PORTUS CHICO", 'maximo' => 2],
            ['producto' => "MONOM M-HI62N LAV PORTUS NEGRO CHICO", 'maximo' => 2],
            ['producto' => "MONOM M-HI62S LAV SATIN PORTUS CHICO", 'maximo' => 2],
            ['producto' => "MONOM M-HI63 LAV CROMO PORTUS 23 CM", 'maximo' => 2],
            ['producto' => "MONOM M-HI64 LAV CROMO PORTUS GRANDE", 'maximo' => 2],
            ['producto' => "MONOM M-HI64N LAV NEGRO PORTUS", 'maximo' => 2],
            ['producto' => "MONOM M-HI64S LAV SATIN PORTUS GRANDE", 'maximo' => 2],
            ['producto' => "MONOM M-HI6813 FREG CROMO ESTONIA", 'maximo' => 2],
            ['producto' => "MONOM M-HI6813S FREG SATIN ESTONIA", 'maximo' => 2],
            ['producto' => "MONOM M-HI82 LAV CROMO TIVOLI", 'maximo' => 2],
            ['producto' => "MONOM M-HI94 FREG CROMO GENOVA", 'maximo' => 2],
            ['producto' => "MONOM M-HI94S FREG SATIN GENOVA", 'maximo' => 2],
            ['producto' => "MONOM M-HI95 FREG Y LAV AVELLINO", 'maximo' => 2],
            ['producto' => "BRAZO M-HIBLN37 C/CHAP \"L\" NEGRO 37 CM", 'maximo' => 4],
            ['producto' => "BRAZO M-HIBLC37 C/CHAP \"L\" CROMO 37 CM", 'maximo' => 10],
            ['producto' => "BRAZO M-HIBLS37 C/CHAP \"L\" SATIN 37 CM", 'maximo' => 10],
            ['producto' => "BRAZO M-HIBCC15 C/CHAP CODO CROMO 15.5 CM", 'maximo' => 15],
            ['producto' => "BRAZO M-HIBBC50 C/CHAP BASTON CROMO 52 CM", 'maximo' => 5],
            ['producto' => "REGADE M-HI8CN25 CUADRADA NEGRO 8\"", 'maximo' => 2],
            ['producto' => "REGADE M-HI8CS12 CUADRADA SATIN 8\"", 'maximo' => 2],
            ['producto' => "REGADE M-HI8CC11 CUADRADA CROMO 8\"", 'maximo' => 3],
            ['producto' => "REGADE M-HI8RC09 RED CROMO 8\"", 'maximo' => 3],
            ['producto' => "REGADE M-HI8RN26 NEGRA RED 8\"", 'maximo' => 2],
            ['producto' => "REGADE M-HI8RS10 SATIN RED 8\"", 'maximo' => 2],
            ['producto' => "REGADE M-HI6CC07 CUADRADA CROMO 6\"", 'maximo' => 3],
            ['producto' => "REGADE M-HI6CS08 CRUADRADA SATIN 6\"", 'maximo' => 2],
            ['producto' => "TARJA M-HIT20 TINA SENCILLA 48X48 ACERO INOX", 'maximo' => 3],
            ['producto' => "TARJA M-HIT21 DOBLE TINA 84X84 ACERO INOX", 'maximo' => 3],
            ['producto' => "TARJA M-HIT22 80X50 DER ACERO INOX HIDROF", 'maximo' => 3],
            ['producto' => "TARJA M-HIT23 80X50 IZQ ACERO INOX HIDROF", 'maximo' => 2],
            ['producto' => "LLAVE ANGULAR 1/4 M-HI-VA04", 'maximo' => 10],
            ['producto' => "LLAVE ANGULAR 1/2 M-HI-VA03", 'maximo' => 10],
            ['producto' => "JUEGO DE ACCESORIOS M-HI384", 'maximo' => 2],
            ['producto' => "JUEGO DE ACCESORIOS M-HI384-S", 'maximo' => 2],
            ['producto' => "MANER M-HIM01 CROMO ZINC", 'maximo' => 2],
            ['producto' => "MANER M-HIM08 CROMO ZINC", 'maximo' => 2],
            ['producto' => "MANER M-HIM08S SATIN ZINC", 'maximo' => 2],
            ['producto' => "MANER M-HIM15 CROMO ZINC", 'maximo' => 2],
            ['producto' => "MANER M-HIM15S SATIN ZINC", 'maximo' => 2],
            ['producto' => "MANER M-HIM18 CROMO ZINC", 'maximo' => 2],
            ['producto' => "MANER M-HIM18S SATIN ZINC", 'maximo' => 2],
            ['producto' => "MANER M-HIM09 CROMO ZINC", 'maximo' => 4],
            ['producto' => "MANER M-HIM08N NEGRO ZINC", 'maximo' => 2],
            ['producto' => "MANER M-HIM17N NEGRO ZINC", 'maximo' => 2],
        ];

        foreach($productosHydroflud as $producto){
            Producto::create([
                'producto' => $producto['producto'],
                'proveedor_id' => 3,
                'unidad' => 'Pza',
                'existencia' => 0,
                'maximo' => $producto['maximo'],
                'pedir' => $producto['maximo'],
                'precio_venta' => 100
            ]);
        }

        

    }
}
