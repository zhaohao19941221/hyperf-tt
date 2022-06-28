#!/bin/bash

cd $(dirname $0)

op=$1
if [[ $op == "start" ]]; then
  exec php bin/hyperf.php start
else
  echo "invalid op"
  exit 1
fi
