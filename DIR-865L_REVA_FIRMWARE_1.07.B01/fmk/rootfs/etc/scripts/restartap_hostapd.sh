#!/bin/sh
echo "aaaaaaaa" > /dev/console
killall hostapd > /dev/null 2>&1; sleep 1
xmldbc -P /etc/services/WIFI/hostapdcfg.php > /var/topology.conf
hostapd /var/topology.conf &
xmldbc -X /runtime/hostapd_restartap
