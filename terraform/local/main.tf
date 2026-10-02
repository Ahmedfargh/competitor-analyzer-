terraform {
  required_version = ">= 1.5.0"

  required_providers {
    docker = {
      source  = "kreuzwerker/docker"
      version = "~> 3.0.2"
    }
  }
}

provider "docker" {
  host = "unix:///var/run/docker.sock"
}

# -----------------------------------------------------------------------------
# Local Docker Network
# -----------------------------------------------------------------------------
resource "docker_network" "laravel_network" {
  name = "laravel-terraform-network"
}

# -----------------------------------------------------------------------------
# Local Docker Volumes
# -----------------------------------------------------------------------------
resource "docker_volume" "mysql_data" {
  name = "mysql_terraform_data"
}

resource "docker_volume" "redis_data" {
  name = "redis_terraform_data"
}

# -----------------------------------------------------------------------------
# MySQL 8.0 Container
# -----------------------------------------------------------------------------
resource "docker_image" "mysql" {
  name         = "mysql:8.0"
  keep_locally = true
}

resource "docker_container" "db" {
  name  = "competator_tf_db"
  image = docker_image.mysql.image_id

  env = [
    "MYSQL_DATABASE=laravel_compitator_db",
    "MYSQL_USER=app_user",
    "MYSQL_PASSWORD=secret_password",
    "MYSQL_ROOT_PASSWORD=secret_password"
  ]

  ports {
    internal = 3306
    external = 3308
  }

  volumes {
    volume_name    = docker_volume.mysql_data.name
    container_path = "/var/lib/mysql"
  }

  networks_advanced {
    name    = docker_network.laravel_network.name
    aliases = ["db"]
  }
}

# -----------------------------------------------------------------------------
# Redis Container
# -----------------------------------------------------------------------------
resource "docker_image" "redis" {
  name         = "redis:alpine"
  keep_locally = true
}

resource "docker_container" "redis" {
  name  = "competitor_tf_redis"
  image = docker_image.redis.image_id

  ports {
    internal = 6379
    external = 6380
  }

  volumes {
    volume_name    = docker_volume.redis_data.name
    container_path = "/data"
  }

  networks_advanced {
    name    = docker_network.laravel_network.name
    aliases = ["redis"]
  }
}

# -----------------------------------------------------------------------------
# Build Local Application Image
# -----------------------------------------------------------------------------
resource "docker_image" "app" {
  name = "compitator-tf-app:latest"
  build {
    context    = "${path.module}/../.."
    dockerfile = "Dockerfile"
  }
}

# -----------------------------------------------------------------------------
# Laravel FrankenPHP App Container
# -----------------------------------------------------------------------------
resource "docker_container" "app" {
  name  = "laravel_tf_app"
  image = docker_image.app.image_id

  env = [
    "APP_ENV=local",
    "APP_KEY=base64:LZ9d22zRGA/VtHX+NkvERUBmay0X4UvnLbc6cyk27QY=",
    "APP_DEBUG=true",
    "APP_URL=http://localhost:9999",
    "DB_CONNECTION=mysql",
    "DB_HOST=db",
    "DB_PORT=3306",
    "DB_DATABASE=laravel_compitator_db",
    "DB_USERNAME=app_user",
    "DB_PASSWORD=secret_password",
    "REDIS_HOST=redis",
    "REDIS_PORT=6379",
    "OCTANE_SERVER=frankenphp"
  ]

  ports {
    internal = 9999
    external = 9999
  }

  networks_advanced {
    name = docker_network.laravel_network.name
  }

  depends_on = [
    docker_container.db,
    docker_container.redis
  ]
}

# -----------------------------------------------------------------------------
# Laravel Reverb Container
# -----------------------------------------------------------------------------
resource "docker_container" "reverb" {
  name  = "competator_tf_reverb"
  image = docker_image.app.image_id

  command = ["php", "artisan", "reverb:start", "--host=0.0.0.0", "--port=9090"]

  env = [
    "DB_HOST=db",
    "DB_PORT=3306",
    "REDIS_HOST=redis",
    "REDIS_PORT=6379",
    "REVERB_APP_ID=100001",
    "REVERB_APP_KEY=competator_key",
    "REVERB_APP_SECRET=competator_secret",
    "REVERB_HOST=0.0.0.0",
    "REVERB_PORT=9090",
    "REVERB_SCHEME=http"
  ]

  ports {
    internal = 9090
    external = 9090
  }

  networks_advanced {
    name = docker_network.laravel_network.name
  }

  depends_on = [
    docker_container.db,
    docker_container.redis
  ]
}
