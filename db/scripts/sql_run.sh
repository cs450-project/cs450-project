#!/bin/bash

# 0: Check DB status
# 1: Query MySql for latest migration
# 2: Check sql dir for migrations newer than query result
# 3..: Execute newer sql *in order*
 
################################################################################
# script and sql calls
################################################################################

DB_HOST=$1
DB_USER=$2
DB_PASSWORD=$3
DB_NAME=$4

if [[ -z $REMOTE_HOST ]]; then
	SSH_PREFIX=""
	SSH_SUFFIX=""
else
	SSH_PREFIX="ssh -t -o StrictHostKeyChecking=no -i sshkey ${REMOTE_USER}@${REMOTE_HOST} \""
	SSH_SUFFIX="\""
fi

MYSQL_CLIENT="mysql --protocol=tcp -h ${DB_HOST} -u ${DB_USER} --password=${DB_PASSWORD} --database=${DB_NAME}"

#checking mysql connection
mysql_db_statuscheck(){
	echo "`date` :Checking DB connectivity...";
	echo "`date` :Trying to connect to the ODU MySQL Database..."
	EXEC_SQL="-Ns --execute='SELECT 1;'"
	cmd="${SSH_PREFIX} ${MYSQL_CLIENT} ${EXEC_SQL} ${SSH_SUFFIX}"
	bash -c "${cmd}"
	if [[ $? -eq 0 ]]
	then
		echo "`date` :Status: UP. Able to Connect..."
	else
		echo "`date` :Status: DOWN . Not able to Connect."
		echo "`date`:Not able to connect to database with Username:
		"${DB_USER}" HostName: ""${DB_HOST}" " SID: "${DB_NAME}"."
		echo "`date` :Exiting Script Run..."
		exit 1
	fi
}

# run mysql function
runmysqls() {
	echo "`date` :Checking DB and table status..."

	# Exits if failure
	mysql_db_statuscheck

	echo "`date` :DB status check completed"
	echo "`date` :Connecting To ${DB_USER}/******@${DB_NAME}";
	
	# latest_migration will be an int
	EXEC_SQL='-Ns -e "SELECT MAX(version) FROM migrations;"'
	cmd="${SSH_PREFIX} ${MYSQL_CLIENT} ${EXEC_SQL} ${DB_NAME} ${SSH_SUFFIX}"
	latest_migration=`sh -c "$cmd"`
	if [[ "${latest_migration}"  == *"ERROR 1146"* ]]; then
		echo "`date` :Running initial migration"
		latest_migration=0
	fi

	for file in `ls ./sql`; do
		file_migration_no=`echo "$file" |cut -c1-3`
		if [[ $((10#$latest_migration + 1 )) -lt $((10#$file_migration_no + 1)) ]]; then
			echo "`date` :Executing migration $file_migration_no from file $file...";
			echo "`date` :__________________________________________";
			echo "`date` :SQL OUTPUT:";
			echo "`date` :__________________________________________";
			EXEC_SQL="-s --execute=\"`cat ./sql/${file}`\""
			cmd="${SSH_PREFIX} ${MYSQL_CLIENT} ${EXEC_SQL} 2>&1 ${SSH_SUFFIX}"
			sqlout=`sh -c "$cmd"`
			if [[ $? -eq 0 ]]; then 
				EXEC_SQL="-s --execute='INSERT INTO migrations (version) VALUES (${file_migration_no});'"
				cmd="${SSH_PREFIX} ${MYSQL_CLIENT} ${EXEC_SQL} ${SSH_SUFFIX}"
				bash -c "$cmd"
			else
				echo ${sqlout}
				exit 1
			fi
		fi
	done
}

# main function
Main() {
	echo "`date` :Starting sql auto run script."
	runmysqls
	echo "`date` :sql auto run script execution completed."
}
Main
