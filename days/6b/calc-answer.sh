#!/bin/bash
awk '{ for (i=1; i<=NF; i++) sum += $i } END { print sum }' ./tmp/*