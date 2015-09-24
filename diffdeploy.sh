#!/bin/bash

ROOT_PATH=/home/eggmatte/
DIFS_TEMP=$ROOT_PATH/comdiffs
REPO_PATH=$ROOT_PATH/eggsrepo

cd $REPO_PATH
git log -n1 | grep commit | awk '{print $2}' > $ROOT_PATH/comdiffs
echo "first rev "$(cat $ROOT_PATH/comdiffs)
git pull
git log -n1 | grep commit | awk '{print $2}' >> $ROOT_PATH/comdiffs
echo "last rev "$(tail -n1 $ROOT_PATH/comdiffs)

git diff --name-only $(head -n1 $ROOT_PATH/comdiffs) $(tail -n1 $ROOT_PATH/comdiffs) > $ROOT_PATH/cpfiles

while read -r cpfile; do
  echo "copying "$cpfile
  cp ./$cpfile $ROOT_PATH/$cpfile
done < $ROOT_PATH/cpfiles




