#!/usr/bin/env sh

DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
DT=`date '+%Y%m%d%H%M'`

cd $DIR

docker build \
  -f config/docker/app/Dockerfile.onpremise \
  -t lab.campr.cloud:4567/campr/on-premise/workspaces:latest \
  -t lab.campr.cloud:4567/campr/on-premise/workspaces:$DT \
  .

if [ $? -eq 0 ];
then
  docker push lab.campr.cloud:4567/campr/on-premise/workspaces:$DT
  docker push lab.campr.cloud:4567/campr/on-premise/workspaces:latest
fi;
