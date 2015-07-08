#!/bin/bash
Domname=$1
mac=`virsh dumpxml $Domname | grep mac\ address | cut -d "'" -f2`
ip=`/usr/sbin/arp -an | grep $mac | cut -d"(" -f2 | cut -d")" -f1`
ip1=""
if [ "$ip" == "$ip1" ]; then
	nmap -sP 10.129.28.0/17
	ip=/usr/sbin/arp -an | grep $mac | cut -d"(" -f2 | cut -d")" -f1
fi
echo $ip
