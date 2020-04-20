#!/usr/bin/env sh

DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
DT=`date '+%Y%m%d%H%M'`

cd $DIR

docker build \
  -f config/docker/app/Dockerfile.onpremise \
  -t camprgmbh/campr:latest \
  -t camprgmbh/campr:$DT \
  .

if [ $? -eq 0 ];
then
  echo "Build success!"
  docker push camprgmbh/campr:$DT
  docker push camprgmbh/campr:latest
fi;
