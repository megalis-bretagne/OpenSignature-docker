#!/bin/bash


if [ "$#" -eq 0 ]; then
    command="start"
else
    command=$1
fi

if [ "$command" = "start" ]; then
    apache2-foreground
elif [ "$command" = "poolsign" ]; then
    echo "Executing initcrontab"
    incrond -n
else
    echo "Unrecognized command: $command"
    exit 1
fi
