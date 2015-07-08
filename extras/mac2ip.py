#!/usr/bin/python
# mac2ip.py script to get IP address for guests machines
#
import sys
mac2ip={}
mac=""
ip=""
mac1=sys.argv[1]
file = open('/var/lib/dhcp/dhcpd.leases', 'r')
for line in file:
    if "lease" in line:
	if "{" in line:
		ip=line.split()[1]
    if "hardware ethernet" in line: 
	mac=line.split()[2].rstrip(";")
	mac2ip[mac]=ip
if mac1 in mac2ip:
	print mac2ip[mac1]
else:
	print 0
