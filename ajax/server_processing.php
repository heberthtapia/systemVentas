<?php

/*
 * DataTables example server-side processing script.
 *
 * Please note that this script is intentionally extremely simply to show how
 * server-side processing can be implemented, and probably shouldn't be used as
 * the basis for a large complex system. It is suitable for simple use cases as
 * for learning.
 *
 * See http://datatables.net/usage/server-side for full details on the server-
 * side processing requirements of DataTables.
 *
 * @license MIT - http://datatables.net/license_mit
 */

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Easy set variables
 */

// DB table to use
$table = 'persona';

// Table's primary key
$primaryKey = 'idpersona';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(

	array( 'db' => 'nombre', 'dt' => 0 ),
	array( 'db' => 'tipo_documento',  'dt' => 1 ),
	array( 'db' => 'email',   'dt' => 2 ),
	array( 'db' => 'telefono',     'dt' => 3 ),
	array( 'db' => 'direccion_departamento',     'dt' => 4 ),
	array(
		'db' => 'idpersona',
		'dt' => 5,
		'formatter' => function( $d, $row ) {
			return ('<button class="btn btn-warning" data-toggle="tooltip" title="Editar" onclick="cargarDataCliente('.$d.')"><i class="fa fa-pencil"></i> </button>&nbsp;'.
					'<button class="btn btn-danger" data-toggle="tooltip" title="Eliminar" onclick="eliminaCliente('.$d.')"><i class="fa fa-trash"></i> </button>');
		} )
	/*array(
		'db'        => 'direccion_departamento',
		'dt'        => 4,
		'formatter' => function( $d, $row ) {
			return date( 'jS M y', strtotime($d));
		}
	)
	array(
		'db'        => 'direccion_provincia',
		'dt'        => 5,
		'formatter' => function( $d, $row ) {
			return '$'.number_format($d);
		}
	)*/
);

// SQL server connection information
$sql_details = array(
	'user' => 'root',
	'pass' => 'mysql',
	'db'   => 'solventas',
	'host' => 'localhost'
);
/*$sql_details = array(
	'user' => 'dymmasivos',
	'pass' => '}n#OFI[LS#~e',
	'db'   => 'dymmasivos',
	'host' => 'localhost'
);
$sql_details = array(
	'user' => 'demofarma',
	'pass' => '-yWSSZx?0Myz',
	'db'   => 'demofarma',
	'host' => 'localhost'
);

*/

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */

require( 'ssp.class.php' );

$whereCustom = "tipo_persona = 'Cliente'";

echo $_REQUEST[$search[0]];

echo json_encode(
	SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $whereCustom )
);


