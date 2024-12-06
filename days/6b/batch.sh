#!/bin/bash
mkdir -p tmp
php solve.php offset=0 > ./tmp/1.txt &
php solve.php offset=500 > ./tmp/2.txt &
php solve.php offset=1000 > ./tmp/3.txt &
php solve.php offset=1500 > ./tmp/4.txt &
php solve.php offset=2000 > ./tmp/5.txt &
php solve.php offset=2500 > ./tmp/6.txt &
php solve.php offset=3000 > ./tmp/7.txt &
php solve.php offset=3500 > ./tmp/8.txt &
php solve.php offset=4000 > ./tmp/9.txt &
php solve.php offset=4500 > ./tmp/10.txt &
php solve.php offset=5000 > ./tmp/11.txt &
php solve.php offset=5500 > ./tmp/12.txt &