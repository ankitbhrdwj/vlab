#!/bin/bash
dest=eth0
if [ $1 ]; then
	dest=$1
fi

time=`date +%y%m%d%H%M%S`
sudo cp /etc/network/interfaces /etc/network/interfaces.$time

cd /etc/network/
file=interfaces$time
sudo cat > ~/$file <<EOF
auto lo
iface lo inet loopback

auto br0
iface br0 inet dhcp
        bridge_ports $dest
        bridge_stp off
        bridge_fd 0
        bridge_maxwait 0
EOF

sudo mv ~/$file /etc/network/interfaces
sudo /etc/init.d/networking restart
#sudo service network-manager restart
#see=`sudo brctl show | grep -w "^br0.*$dest"`

#if [[ ! $see ]]; then
#	sudo brctl addbr br0
#	sudo brctl addif br0 $dest
#fi
