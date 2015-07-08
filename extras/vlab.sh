#!/bin/bash
sudo chown www-data /var/run/libvirt/libvirt-sock
sudo chmod 644 /var/log/syslog
#cat /var/log/syslog | grep DHCPREQUEST | grep dhcpd > /var/www/vlab/mac2ip
