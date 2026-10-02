terraform {
  required_version = ">= 1.5.0"

  required_providers {
    docker = {
      source  = "kreuzwerker/docker"
      version = "~> 3.0.2"
    }
  }
}

locals {
  # Parse .env file into a Key => Value Map
  env_map = {
    for line in split("\n", file("${path.module}/../.env")) :
    split("=", line)[0] => join("=", slice(split("=", line), 1, length(split("=", line))))
    if trimspace(line) != "" && !startswith(trimspace(line), "#") && length(split("=", line)) >= 2
  }

  # Convert .env map into ["KEY=VALUE"] list for Docker
  env_list = [
    for k, v in local.env_map : "${k}=${v}"
    if !contains(["DB_HOST", "REDIS_HOST", "DB_PORT", "REDIS_PORT"], k)
  ]
}

provider "docker" {
  host = "unix:///var/run/docker.sock"
}

# -----------------------------------------------------------------------------
# 1. Docker Network
# -----------------------------------------------------------------------------
resource "docker_network" "competator_network" {
  name = "competator-network-resource"
}

# -----------------------------------------------------------------------------
# 2. Data Volumes
# -----------------------------------------------------------------------------
resource "docker_volume" "database_volume" {
  name = "competator-database-volume"
}

resource "docker_volume" "redis_volume" {
  name = "competator-cache-volume"
}

# -----------------------------------------------------------------------------
# 3. MySQL Database Container
# -----------------------------------------------------------------------------
resource "docker_image" "mysql" {
  name         = "mysql:8.0"
  keep_locally = true
}

resource "docker_container" "competator_db_container" {
  name  = "competator_db_container"
  image = docker_image.mysql.image_id

  env = [
    "MYSQL_DATABASE=${local.env_map["DB_DATABASE"]}",
    "MYSQL_USER=${local.env_map["DB_USERNAME"]}",
    "MYSQL_PASSWORD=${local.env_map["DB_PASSWORD"]}",
    "MYSQL_ROOT_PASSWORD=${local.env_map["DB_PASSWORD"]}"
  ]

  ports {
    internal = 3306
    external = 3308
  }

  volumes {
    volume_name    = docker_volume.database_volume.name
    container_path = "/var/lib/mysql"
  }

  networks_advanced {
    name    = docker_network.competator_network.name
    aliases = ["db"]
  }
}

# -----------------------------------------------------------------------------
# 4. Redis Cache Container
# -----------------------------------------------------------------------------
resource "docker_image" "redis" {
  name         = "redis:alpine"
  keep_locally = true
}

resource "docker_container" "competator_redis" {
  name  = "competator_redis_container"
  image = docker_image.redis.image_id

  ports {
    internal = 6379
    external = 6380
  }

  volumes {
    volume_name    = docker_volume.redis_volume.name
    container_path = "/data"
  }

  networks_advanced {
    name    = docker_network.competator_network.name
    aliases = ["redis"]
  }
}

# -----------------------------------------------------------------------------
# 5. Build Laravel App Docker Image
# -----------------------------------------------------------------------------
resource "docker_image" "http_app" {
  name = "compitatortfapp:latest"
  build {
    context    = "${path.module}/.."
    dockerfile = "Dockerfile"
  }
}

# -----------------------------------------------------------------------------
# 6. Laravel FrankenPHP Octane App Container
# -----------------------------------------------------------------------------
resource "docker_container" "app" {
  name  = "http_app"
  image = docker_image.http_app.image_id

  # Combines .env variables with Docker internal network hosts
  env = concat(local.env_list, [
    "DB_HOST=db",
    "DB_PORT=3306",
    "REDIS_HOST=redis",
    "REDIS_PORT=6379"
  ])

  ports {
    internal = 9999
    external = 9999
  }

  networks_advanced {
    name = docker_network.competator_network.name
  }

  depends_on = [
    docker_container.competator_db_container,
    docker_container.competator_redis
  ]
}

# -----------------------------------------------------------------------------
# 7. Laravel Reverb WebSocket Container
# -----------------------------------------------------------------------------
resource "docker_container" "reverb" {
  name  = "competator_tf_reverb"
  image = docker_image.http_app.image_id

  command = ["php", "artisan", "reverb:start", "--host=0.0.0.0", "--port=9090"]

  env = concat(local.env_list, [
    "DB_HOST=db",
    "DB_PORT=3306",
    "REDIS_HOST=redis",
    "REDIS_PORT=6379"
  ])

  ports {
    internal = 9090
    external = 9090
  }

  networks_advanced {
    name = docker_network.competator_network.name
  }

  depends_on = [
    docker_container.competator_db_container,
    docker_container.competator_redis
  ]
}
