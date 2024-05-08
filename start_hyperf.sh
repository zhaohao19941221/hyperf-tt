#!/bin/bash

# shellcheck disable=SC2046
cd $(dirname "$0") || exit

op=$1
if [[ $op == "start" ]]; then
  exec php bin/hyperf.php start
else
  echo "start hyperf-tt err"
  exit 1
fi
