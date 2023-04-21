
/* Build query string for db select statement */
/* condition[0] is list of cols, condition[1] is list of vals */
function db_select_query(table_name, column_names, condition) {
	new_query = '';
	new_query += 'SELECT ';

	for(let i=0; i<column_names.length; i++) {
		if(i != 0) {
			new_query += ', ';
		}

		// if(isNaN(values[i])) {
		// 	new_query += column_names[i] + ' = "' + values[i] + '"';
		// } else {
		// 	new_query += column_names[i] + ' = ' + values[i];
		// }
		new_query += column_names[i];
	}
	new_query += ' FROM ' + table_name;

    if(condition) {
        new_query += ' WHERE ';

		for(j=0; j<condition[0].length; j++) {
			if(j != 0) {
				new_query += ' and ';
			}

			new_query += condition[0][j] + ' = "' + condition[1][j] + '"';
		}
    }

	return new_query + ';';
}


/* Build query string for db update statement */
function db_update_query(table_name, column_names, values, condition) {
	new_query = '';
	new_query += 'UPDATE '+table_name+' SET ';

	for(let i=0; i<column_names.length; i++) {
		if(i != 0) {
			new_query += ', ';
		}

		// if(isNaN(values[i])) {
		// 	new_query += column_names[i] + ' = "' + values[i] + '"';
		// } else {
		// 	new_query += column_names[i] + ' = ' + values[i];
		// }
		new_query += column_names[i] + ' = "' + values[i] + '"';
	}

    if(condition) {
        new_query += ' WHERE '+condition;
    }

	return new_query + ';';
}


/* Build query string for db insert statement */
function db_insert_query(table_name, column_names, values) {
	new_query = '';
	new_query += 'INSERT INTO '+table_name+' (';

	for(let i in column_names) {
		if(i != 0) {
			new_query += ', ';
		}
		new_query += column_names[i];
	}
	new_query += ') VALUES (';

	for(let i in values) {
		if(i != 0) {
			new_query += ', ';
		}
		// if(isNaN(values[i])) {
		// 	new_query += '"' + values[i] + '"';
		// } else {
		// 	new_query += values[i] ;
		// }
		new_query += '"' + values[i] + '"';
	}
	new_query += ');';

	return new_query;
}


module.exports = {db_insert_query, db_select_query, db_update_query};