#!/bin/bash

# bluetoothctl scan on

while true
do
	bluetoothctl devices | wc -l 
	sleep 1
done
