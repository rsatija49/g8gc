#!/bin/bash

# Script to automatically push the contents on github

G8_PUSH_VER=`cat /var/www/lo/g8_ver.txt`
G8_BRANCH_NAME='master'

# DB Config details
G8_DB_HOST='localhost'
G8_DB_USER='root'
G8_DB_PASS='12qwaszx'
G8_DB='g8_core'
# If you need to ignore any table data, enter upto 3 
# Note, the table structure will be exported, if ignored or not
G8_IGNORE_TBL1=""
G8_IGNORE_TBL2=""
G8_IGNORE_TBL3=""

# No changes below this line
# ################################################################################
G8_NEW_PUSH_VER=$(echo $G8_PUSH_VER + 0.01 | bc)
G8_NEW_PUSH=$(echo $G8_PUSH_VER + 0.01 | bc)

if [ ! -z "$G8_IGNORE_TBL1" ]; then
	G8_IGN_TBL1=" --ignore-table=$G8_DB.$G8_IGNORE_TBL1 "
fi

if [ ! -z "$G8_IGNORE_TBL2" ]; then
	G8_IGN_TBL2=" --ignore-table=$G8_DB.$G8_IGNORE_TBL2 "
fi

if [ ! -z "$G8_IGNORE_TBL3" ]; then
	G8_IGN_TBL3=" --ignore-table=$G8_DB.$G8_IGNORE_TBL3 "
fi

# Ask him for version number
read -n 4 -p "Enter the version number to be pushed ($G8_NEW_PUSH_VER) : " G8_NEW_PUSH_VER
G8_NEW_PUSH_VER=${G8_NEW_PUSH_VER:-$G8_NEW_PUSH}
echo -e "\r\nYou entered $G8_NEW_PUSH_VER"
echo "$G8_NEW_PUSH_VER" > /var/www/lo/g8_ver.txt

# Now lets get branch name
read -p "Enter the branch name [$G8_BRANCH_NAME]: " G8_branch
G8_branch=${G8_branch:-$G8_BRANCH_NAME}
echo $G8_branch

# Lets get comments from user
G8_comment=
while [[ $G8_comment = "" ]]; do
   read -p "Enter comments for this update : " G8_comment
done
G8_COMMIT_COMMENT="[$G8_NEW_PUSH_VER]: $G8_comment"	
echo "Commits will be sent with these comments: $G8_COMMIT_COMMENT"
cd /var/www/lo

echo "Now exporting database..."
# mysql --host=$G8_DB_HOST --user=$G8_DB_USER --password=$G8_DB_PASS $G8_DB -e 'show tables';
#mysqldump --host=$G8_DB_HOST --user=$G8_DB_USER --password=$G8_DB_PASS $G8_IGN_TBL1 $G8_IGN_TBL2 $G8_IGN_TBL3  $G8_DB > "/var/www/lo/install/g8_core-$G8_NEW_PUSH_VER.sql"
#echo "Now taking the table structure of tables, that we had ignored.."
#mysqldump --host=$G8_DB_HOST --user=$G8_DB_USER --password=$G8_DB_PASS  --no-data $G8_DB $G8_IGNORE_TBL1 $G8_IGNORE_TBL2 $G8_IGNORE_TBL3 >>  "/var/www/lo/install/g8_core-$G8_NEW_PUSH_VER.sql"
#echo "Compressing the files..."
#cd /var/www/lo/install/
#gzip "g8_core-$G8_NEW_PUSH_VER.sql"

# Now changing contents of README.md
# Change version

# First replace the Version number line with your tag
sed -i "s/^LifeOn_Ver/##1234567890##\nLifeOn_Ver/g" "/var/www/lo/README.md"
# Then replace that tag with your text
grep -v "LifeOn_Ver" /var/www/lo/README.md > /tmp/g8_tmp
sed  -i "s/##1234567890##/LifeOn_Ver: $G8_NEW_PUSH_VER/g" "/tmp/g8_tmp"
cat /tmp/g8_tmp > /var/www/lo/README.md


#Now replace date
G8_DT=`TZ=Asia/Kolkata date   "+%a, %d-%b-%Y %H:%M:%S %Z"`
sed -i "s/^Update_date/##1234567890##\nUpdate_date/g" "/var/www/lo/README.md"
# Then replace that tag with your text
grep -v "Update_date" /var/www/lo/README.md > /tmp/g8_tmp
sed  -i "s/##1234567890##/Update_date: $G8_DT/g" "/tmp/g8_tmp"
cat /tmp/g8_tmp > /var/www/lo/README.md


#Now replace hostname
G8_HST=`hostname`
sed -i "s/^Update_host/##1234567890##\nUpdate_host/g" "/var/www/lo/README.md"
# Then replace that tag with your text
grep -v "Update_host" /var/www/lo/README.md > /tmp/g8_tmp
sed  -i "s/##1234567890##/Update_host: $G8_HST/g" "/tmp/g8_tmp"
cat /tmp/g8_tmp > /var/www/lo/README.md



# Here we do a bit of cleanup.
chown www-data.www-data -R /var/www/lo
#TBD - to find and delete .bak files


# Now lets package this for pushing
cd /var/www/lo
git checkout $G8_branch
git add -A
git commit -m "\"$G8_COMMIT_COMMENT\""
git push -u origin $G8_branch

# Now lets keep a backup of this version
