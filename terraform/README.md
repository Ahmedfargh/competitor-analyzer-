# 🚀 Infrastructure as Code (Terraform Tutorial for Docker Deployment)

This directory contains the automated Terraform infrastructure to provision an AWS cloud server (Ubuntu 24.04 LTS) pre-configured with **Docker Engine**, **Docker Compose Plugin**, **UFW Firewall**, and all necessary security groups to run this Laravel FrankenPHP + Octane + Reverb + MySQL + Redis application.

---

## 📁 Architecture Overview

- **VPC & Networking**: Custom VPC (`10.0.0.0/16`), Public Subnet (`10.0.1.0/24`), Internet Gateway, and Route Tables.
- **Security Groups**:
  - `22`: SSH Access.
  - `80` & `443`: Standard Web traffic (HTTP/HTTPS).
  - `9999`: Laravel Octane / FrankenPHP Web Server.
  - `9090`: Laravel Reverb WebSockets Server.
- **Compute**: EC2 instance (`t3.small` or configurable) with Elastic IP for a fixed public IP address.
- **Automated Provisioning (`user_data.sh`)**: Auto-installs Docker, Compose, sets kernel optimizations (`vm.overcommit_memory = 1` for Redis), and configures firewall rules on first boot.

---

## 🛠️ Step-by-Step Tutorial

### 1. Prerequisites
- [Terraform CLI (>= 1.5.0)](https://developer.hashicorp.com/terraform/downloads) installed.
- AWS CLI configured with credentials:
  ```bash
  aws configure
  ```

### 2. Configure Variables
Copy the example variables file:
```bash
cd terraform
cp terraform.tfvars.example terraform.tfvars
```

Edit `terraform.tfvars`:
```hcl
aws_region       = "us-east-1"
project_name     = "competitor-analyzer"
instance_type    = "t3.small"
ssh_public_key   = "ssh-ed25519 AAAAC3Nza... your_email@example.com"
```

### 3. Initialize & Plan
```bash
# Initialize Terraform and download AWS provider plugins
terraform init

# Review the execution plan
terraform plan
```

### 4. Deploy Infrastructure
```bash
# Apply and provision resources
terraform apply -auto-approve
```

Once finished, Terraform will output:
```text
server_public_ip       = "54.x.x.x"
ssh_connection_command = "ssh ubuntu@54.x.x.x"
app_url                = "http://54.x.x.x:9999"
```

---

## 🐳 Deploying the Application via Docker

1. **SSH into the new server**:
   ```bash
   ssh ubuntu@<SERVER_PUBLIC_IP>
   ```

2. **Clone your repository**:
   ```bash
   cd /opt/app
   git clone git@github.com:Ahmedfargh/competitor-analyzer-.git .
   ```

3. **Configure Environment**:
   ```bash
   cp .env.example .env
   # Update APP_URL=http://<SERVER_PUBLIC_IP>:9999
   ```

4. **Launch with Docker Compose**:
   ```bash
   docker compose up -d --build
   ```

5. **Run Migrations & Seeders**:
   ```bash
   docker compose exec app php artisan migrate --force
   ```

---

## 🧹 Destroying Resources (Cleanup)
When you're done testing, destroy the resources to avoid cloud costs:
```bash
terraform destroy -auto-approve
```
