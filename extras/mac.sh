#!/bin/bash
mac=$1
#mac=`virsh dumpxml $Domname | grep mac\ address | cut -d "'" -f2`
ip=`/usr/sbin/arp -an | grep $mac | cut -d"(" -f2 | cut -d")" -f1`
ip1=""
if [ "$ip" == "$ip1" ]; then
	ip=`cat /var/www/vlab/mac2ip | grep DHCPREQUEST | grep dhcpd | grep $mac | tail -n 1 |  cut -d" " -f8`
fi
echo $ip

