#!/bin/bash
set -e

# Update and install dependencies
apt-get update -y
apt-get install -y ca-certificates curl gnupg lsb-release git ufw

# Add Docker's official GPG key & repository
install -m 0755 -d /etc/apt/keyrings
curl -fsSL https://download.docker.com/linux/ubuntu/gpg -o /etc/apt/keyrings/docker.asc
chmod a+r /etc/apt/keyrings/docker.asc

echo "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.asc] https://download.docker.com/linux/ubuntu $(. /etc/os-release && echo "$VERSION_CODENAME") stable" | \
  tee /etc/apt/sources.list.d/docker.list > /dev/null

apt-get update -y
apt-get install -y docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin

# Enable and start Docker
systemctl enable docker
systemctl start docker
usermod -aG docker ubuntu

# Configure Kernel for Redis overcommit memory
sysctl vm.overcommit_memory=1
echo "vm.overcommit_memory = 1" >> /etc/sysctl.conf

# Setup UFW Firewall
ufw default deny incoming
ufw default allow outgoing
ufw allow 22/tcp
ufw allow 80/tcp
ufw allow 443/tcp
ufw allow 9999/tcp
ufw allow 9090/tcp
ufw --force enable

# Prepare Application Directory
mkdir -p /opt/app
chown -R ubuntu:ubuntu /opt/app

echo "Docker and infrastructure environment ready for deployment."
