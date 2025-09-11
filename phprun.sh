#!/bin/bash

if [ -z "$1" ]; then
  echo "error: donne le chemin d'un fichier php en argument"
  exit 1
fi

docker compose exec php php "$@"
