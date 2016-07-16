#!/bin/bash

### Purpose : Run fullsolr werbserver and redis
### Maintainer: Tarikur Rahaman
### Create Date: 01-02-2016
### 52.36.205.20
### 192.168.2.106

##### Check apache Service / port 80  Status. #####

##### Stop and remove all running container name Redis and fullsolr_web if those are runnning. #####

STATUS=`docker ps -a | egrep "redis|web" | awk '{print $1}'`

if [ -n "$STATUS" ];then
	docker ps -a | egrep "redis|web" | awk '{print $1}'| xargs docker rm -f > /dev/null
fi

SERVICE=$(ps ax | grep -v grep | grep apache2)
if  [ -n "$SERVICE" ];then
	echo "Apache service is running on your host machine or ### port 80 ### is busy somehow. Please Stop it first then run again this script"
	exit 1
fi

##### Run Redis and Webserver. Web server will link redis container and it will mount project Dirctory and virtualhost file. #####
docker run -d -h web -v $(pwd):/var/www/html  -e SOLR_SERVER_PORT='8983' -e SITE_NAME='RENTALHOMES' -e SOLR_SERVER_IP='52.36.205.20' -e RENTBYOWNER_LB_ID='awseb-e-d-AWSEBLoa-156NJ1W7PL0ZH' -e RENTALHOMES_LB_ID='awseb-e-z-AWSEBLoa-IR6S6RX8U5A4' -e RENTERS_LB_ID='awseb-e-n-AWSEBLoa-1F5K6L8TPARUH' -p 80:80 --name web  rashidw3/httpd:production > /dev/null

fullsolr=$(docker inspect --format '{{ .NetworkSettings.IPAddress }}' web)



echo "####################################################"
echo "#Your site IP address is : $fullsolr"

echo "# OR put '  $fullsolr    local.rentalhomes.com  ' at your etc/hosts file  and  then save and browse http://local.rentalhomes.com"
echo "####################################################"