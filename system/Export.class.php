<?php

	class Export{
		
		private $db;
		
		public function __construct($db){
		
			$this->db = $db;
			
		}
		
		public function SQL2XML($sql_statement){
		
			$sxe = new SimpleXMLElement('<XMLExport></XMLExport>');
			$sxe_crs = $sxe->addChild('record');

			function array_walk_simplexml(&$value, $key, &$sx) {
				$sx->addChild($key, $value);
			}

			$stmt = $this->db->query($sql_statement);

			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$sx_cr = $sxe_crs->addChild('record');
				array_walk($row, 'array_walk_simplexml', $sx_cr);
			}

			echo $sxe->asXML();
			
		}
		
		public function SQL2CSV($sql_statement){
		
			$select = $sql_statement;

			$export = mysql_query ( $select ) or die ( "Sql error : " . mysql_error( ) );

			$fields = mysql_num_fields ( $export );

			for ( $i = 0; $i < $fields; $i++ )
			{
				$header .= mysql_field_name( $export , $i ) . "\t";
			}

			while( $row = mysql_fetch_row( $export ) )
			{
				$line = '';
				foreach( $row as $value )
				{                                            
					if ( ( !isset( $value ) ) || ( $value == "" ) )
					{
						$value = "\t";
					}
					else
					{
						$value = str_replace( '"' , '""' , $value );
						$value = '"' . $value . '"' . "\t";
					}
					$line .= $value;
				}
				$data .= trim( $line ) . "\n";
			}
			$data = str_replace( "\r" , "" , $data );

			if ( $data == "" )
			{
				$data = "\n(0) Records Found!\n";                        
			}

			header("Content-type: application/octet-stream");
			header("Content-Disposition: attachment; filename=Export.xls");
			header("Pragma: no-cache");
			header("Expires: 0");
			print "$header\n$data";
			
		}
		
		public function SQL2HTML($sql_statement){
			
		
			
		}
	
	}
