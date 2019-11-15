#!/bin/sh
curl -s "$1" | grep -r href |  cut -d '"' -f 2
