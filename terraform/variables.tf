variable "aws_region" {
  description = "AWS region to deploy resources in"
  type        = string
  default     = "us-east-1"
}

variable "project_name" {
  description = "Project name tag prefix for resources"
  type        = string
  default     = "competitor-analyzer"
}

variable "environment" {
  description = "Deployment environment (e.g. production, staging, tutorial)"
  type        = string
  default     = "tutorial"
}

variable "instance_type" {
  description = "EC2 instance type (t3.small recommended for FrankenPHP + Octane + MySQL + Redis)"
  type        = string
  default     = "t3.small"
}

variable "ssh_public_key" {
  description = "Public SSH key for EC2 instance access"
  type        = string
  default     = ""
}

variable "allowed_ssh_cidr" {
  description = "CIDR block allowed to SSH into the instance (use your IP e.g. 1.2.3.4/32 for security)"
  type        = string
  default     = "0.0.0.0/0"
}
