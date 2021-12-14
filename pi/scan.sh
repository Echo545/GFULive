#!/bin/bash


while true
do
	timeout 300 bluetoothctl scan on
	sleep 1
	bluetoothctl scan off
	sleep 1
	python3 /home/pi/scanner.py
done

