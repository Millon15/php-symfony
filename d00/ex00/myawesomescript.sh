#!/bin/sh

curl -s "$1" | grep href |  cut -d \" -f 2
