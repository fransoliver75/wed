<?php

	const APP_URL="http://localhost/pepe/";
	const APP_NAME="ventas";
	const APP_SESSION_NAME="POS";

	/*----------  Tipos de documentos  ----------*/
	const DOCUMENTOS_USUARIOS=["DUI","DNI","Cedula","Licencia","Pasaporte","Otro"];


	/*----------  Tipos de unidades de productos  ----------*/
	const PRODUCTO_UNIDAD=["Unidad","Libra","Kilogramo","Caja","Paquete","Lata","Galon","Botella","Tira","Sobre","Bolsa","Saco","Tarjeta","Otro"];

/*----------  Configuración de moneda  ----------*/
const MONEDA_SIMBOLO="S/";              // Símbolo del Sol peruano
const MONEDA_NOMBRE="PEN";              // Código ISO de la moneda de Perú (Nuevo Sol)
const MONEDA_DECIMALES="2";             // Número de decimales
const MONEDA_SEPARADOR_MILLAR=",";      // Separador de miles
const MONEDA_SEPARADOR_DECIMAL=".";     // Separador de decimales



	/*----------  Marcador de campos obligatorios (Font Awesome) ----------*/
	const CAMPO_OBLIGATORIO='&nbsp; <i class="fas fa-edit"></i> &nbsp;';

	/*----------  Zona horaria  ----------*/
	date_default_timezone_set("America/Lima");

	/*
		Configuración de zona horaria de tu país, para más información visita
		http://php.net/manual/es/function.date-default-timezone-set.php
		http://php.net/manual/es/timezones.php
	*/