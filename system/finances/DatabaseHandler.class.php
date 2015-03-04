<?php

# This file is part of Koha.
#
# Copyright (C) 2014  xkravec
#
# Koha is free software; you can redistribute it and/or modify it
# under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 3 of the License, or
# (at your option) any later version.
#
# Koha is distributed in the hope that it will be useful, but
# WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with Koha; if not, see <http://www.gnu.org/licenses>.

class DatabaseHandler{

	private $db;

	function __construct($db){
		$this->db = $db;
	}
	
	/**
	 * Returns branches
	 * @return	array
	 */
	public function getBranches() {
	   $stmt = $this->db->query("SELECT `branchcode`, `branchname`
								 FROM `branches`");
	   return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	
}
	
