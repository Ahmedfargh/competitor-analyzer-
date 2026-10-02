output "server_public_ip" {
  description = "Public Elastic IP address of the Docker server"
  value       = aws_eip.server_eip.public_ip
}

output "ssh_connection_command" {
  description = "Command to SSH into the provisioned server"
  value       = "ssh ubuntu@${aws_eip.server_eip.public_ip}"
}

output "app_url" {
  description = "URL to access the Laravel FrankenPHP application"
  value       = "http://${aws_eip.server_eip.public_ip}:9999"
}

output "reverb_ws_url" {
  description = "URL for Laravel Reverb WebSocket connections"
  value       = "ws://${aws_eip.server_eip.public_ip}:9999 (proxy) or ws://${aws_eip.server_eip.public_ip}:9090"
}
